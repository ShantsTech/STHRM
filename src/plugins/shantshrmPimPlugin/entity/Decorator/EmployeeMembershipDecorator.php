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

namespace ShantsHRM\Entity\Decorator;

use ShantsHRM\Admin\Service\PayGradeService;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\EmployeeMembership;
use ShantsHRM\Entity\Membership;
use ShantsHRM\Framework\Services;

class EmployeeMembershipDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;
    use ServiceContainerTrait;

    /**
     * @var EmployeeMembership
     */
    protected EmployeeMembership $employeeMembership;

    /**
     * EmployeeMembershipDecorator constructor.
     * @param EmployeeMembership $employeeMembership
     */
    public function __construct(EmployeeMembership $employeeMembership)
    {
        $this->employeeMembership = $employeeMembership;
    }

    /**
     * @return EmployeeMembership
     */
    protected function getEmployeeMembership(): EmployeeMembership
    {
        return $this->employeeMembership;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getEmployeeMembership()->setEmployee($employee);
    }

    /**
     * @param int $membershipId
     */
    public function setMembershipByMembershipId(int $membershipId): void
    {
        /** @var Membership|null $membership */
        $membership = $this->getReference(Membership::class, $membershipId);
        $this->getEmployeeMembership()->setMembership($membership);
    }

    /**
     * @return string|null
     */
    public function getSubscriptionCommenceDate(): ?string
    {
        $date = $this->getEmployeeMembership()->getSubscriptionCommenceDate();
        return $this->getDateTimeHelper()->formatDate($date);
    }

    /**
     * @return string|null
     */
    public function getSubscriptionRenewalDate(): ?string
    {
        $date = $this->getEmployeeMembership()->getSubscriptionRenewalDate();
        return $this->getDateTimeHelper()->formatDate($date);
    }

    /**
     * @return string|null
     */
    public function getCurrencyName(): ?string
    {
        $currencyCode = $this->getEmployeeMembership()->getSubscriptionCurrency();
        /** @var PayGradeService $payGradeService */
        $payGradeService = $this->getContainer()->get(Services::PAY_GRADE_SERVICE);
        if (is_null($currencyCode)) {
            return null;
        }
        $currency = $payGradeService->getCurrencyById($currencyCode);
        return $currency ? $currency->getName() : null;
    }
}
