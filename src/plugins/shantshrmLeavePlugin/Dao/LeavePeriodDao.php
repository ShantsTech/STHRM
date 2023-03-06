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

use Exception;
use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\LeavePeriodHistory;
use ShantsHRM\Leave\Traits\Service\LeaveConfigServiceTrait;
use ShantsHRM\Leave\Traits\Service\LeaveEntitlementServiceTrait;
use ShantsHRM\Leave\Traits\Service\LeavePeriodServiceTrait;
use ShantsHRM\ORM\Exception\TransactionException;
use ShantsHRM\ORM\ListSorter;

class LeavePeriodDao extends BaseDao
{
    use LeavePeriodServiceTrait;
    use LeaveEntitlementServiceTrait;
    use LeaveConfigServiceTrait;
    use DateTimeHelperTrait;

    /**
     * @param LeavePeriodHistory $leavePeriodHistory
     * @return LeavePeriodHistory
     * @throws TransactionException
     */
    public function saveLeavePeriodHistory(LeavePeriodHistory $leavePeriodHistory): LeavePeriodHistory
    {
        $this->beginTransaction();
        try {
            $currentLeavePeriod = $this->getCurrentLeavePeriodStartDateAndMonth();

            $isLeavePeriodDefined = $this->getLeaveConfigService()->isLeavePeriodDefined();
            if ($isLeavePeriodDefined) {
                // Fetching current leave period before save new leave period
                $leavePeriodForToday = $this->getLeavePeriodService()->getCurrentLeavePeriodByDate(
                    $this->getDateTimeHelper()->getNow(),
                    true
                );
            } else {
                $this->getLeaveConfigService()->setLeavePeriodDefined(true);
            }
            $this->persist($leavePeriodHistory);

            if (!empty($currentLeavePeriod) && $isLeavePeriodDefined) {
                $oldStartMonth = $currentLeavePeriod->getStartMonth();
                $oldStartDay = $currentLeavePeriod->getStartDay();
                $newStartMonth = $leavePeriodHistory->getStartMonth();
                $newStartDay = $leavePeriodHistory->getStartDay();

                $strategy = $this->getLeaveEntitlementService()->getLeaveEntitlementStrategy();
                $strategy->handleLeavePeriodChange(
                    $leavePeriodForToday,
                    $oldStartMonth,
                    $oldStartDay,
                    $newStartMonth,
                    $newStartDay
                );
            }

            $this->commitTransaction();
        } catch (Exception $e) {
            $this->rollBackTransaction();
            throw new TransactionException($e);
        }

        return $leavePeriodHistory;
    }

    /**
     * @return LeavePeriodHistory|null
     */
    public function getCurrentLeavePeriodStartDateAndMonth(): ?LeavePeriodHistory
    {
        $q = $this->createQueryBuilder(LeavePeriodHistory::class, 'leavePeriod');
        $q->addOrderBy('leavePeriod.createdAt', ListSorter::DESCENDING);
        $q->addOrderBy('leavePeriod.id', ListSorter::DESCENDING);

        return $this->fetchOne($q);
    }

    /**
     * @return LeavePeriodHistory[]
     */
    public function getLeavePeriodHistoryList(): array
    {
        $q = $this->createQueryBuilder(LeavePeriodHistory::class, 'leavePeriod');
        $q->addOrderBy('leavePeriod.createdAt')
            ->addOrderBy('leavePeriod.id');

        return $q->getQuery()->execute();
    }
}