<?php
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 ShantsHRM Inc., http://www.hrm.shants-tech.com
 *
 * ShantsHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * ShantsHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace ShantsHRM\Leave\Dao;

use DateInterval;
use DateTime;
use Exception;
use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\LeaveEntitlement;
use ShantsHRM\Leave\Dto\LeavePeriod;
use ShantsHRM\ORM\Exception\TransactionException;

class FIFOEntitlementConsumptionStrategyDao extends BaseDao
{
    use DateTimeHelperTrait;

    /**
     * @param LeavePeriod $leavePeriodForToday
     * @param int $oldMonth
     * @param int $oldDay
     * @param int $newMonth
     * @param int $newDay
     * @throws TransactionException
     */
    public function handleLeavePeriodChange(
        LeavePeriod $leavePeriodForToday,
        int $oldMonth,
        int $oldDay,
        int $newMonth,
        int $newDay
    ): void {
        $this->beginTransaction();
        try {
            $leavePeriodStartDate = $leavePeriodForToday->getStartDate();

            // If current leave period start date is 1/1 and new date is 1/1,
            if ($leavePeriodStartDate->format('n') == 1 &&
                $leavePeriodStartDate->format('j') == 1 &&
                $newMonth == 1 && $newDay == 1) {
                $newEndDateForCurrentPeriod = $leavePeriodStartDate->format('Y') . '-12-31';
            } else {
                $tmp = $this->getDateTimeHelper()->getNow();
                $tmp->setDate((int)$leavePeriodStartDate->format('Y') + 1, $newMonth, $newDay);
                $tmp->sub(new DateInterval('P1D'));
                $newEndDateForCurrentPeriod = $tmp->format('Y-m-d');
            }

            // Updating current period
            $q = $this->createQueryBuilder(LeaveEntitlement::class, 'e')
                ->update()
                ->set('e.toDate', ':newToDate')
                ->setParameter('newToDate', $newEndDateForCurrentPeriod)
                ->andWhere('e.deleted = :deleted')
                ->setParameter('deleted', false)
                ->andWhere('e.fromDate = :fromDate')
                ->setParameter('fromDate', $leavePeriodForToday->getStartDate())
                ->andWhere('e.toDate = :toDate')
                ->setParameter('toDate', $leavePeriodForToday->getEndDate());
            $q->getQuery()->execute();

            // Update future periods
            /** @var LeaveEntitlement[] $leaveEntitlements */
            $leaveEntitlements = $this->createQueryBuilder(LeaveEntitlement::class, 'e')
                ->andWhere('e.deleted = :deleted')
                ->setParameter('deleted', false)
                ->andWhere('e.fromDate > :fromDate')
                ->setParameter('fromDate', $leavePeriodForToday->getEndDate())
                ->getQuery()
                ->execute();

            foreach ($leaveEntitlements as $leaveEntitlement) {
                $month = $leaveEntitlement->getFromDate()->format('n');
                $day = $leaveEntitlement->getFromDate()->format('j');
                if ($month == $oldMonth && $day == $oldDay) {
                    $fromDate = new DateTime(
                        $leaveEntitlement->getFromDate()->format('Y') . '-' . $newMonth . '-' . $newDay
                    );
                    $leaveEntitlement->setFromDate($fromDate);
                    $toDate = clone $fromDate;
                    $toDate->add(DateInterval::createFromDateString('+1 year -1 day'));
                    $leaveEntitlement->setToDate($toDate);
                }
            }

            $this->getEntityManager()->flush();
            $this->commitTransaction();
        } catch (Exception $e) {
            $this->rollBackTransaction();
            throw new TransactionException($e);
        }
    }
}
