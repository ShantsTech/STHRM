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

use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\EmpContract;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\EmployeeAttachment;
use ShantsHRM\Pim\Service\EmploymentContractService;

class EmpContractDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    /**
     * @var EmpContract
     */
    protected EmpContract $empContract;

    /**
     * @var EmploymentContractService|null
     */
    protected ?EmploymentContractService $employmentContractService = null;

    /**
     * @param EmpContract $empContract
     */
    public function __construct(EmpContract $empContract)
    {
        $this->empContract = $empContract;
    }

    /**
     * @return EmpContract
     */
    protected function getEmpContract(): EmpContract
    {
        return $this->empContract;
    }

    /**
     * @return EmploymentContractService
     */
    public function getEmploymentContractService(): EmploymentContractService
    {
        if (!$this->employmentContractService instanceof EmploymentContractService) {
            $this->employmentContractService = new EmploymentContractService();
        }
        return $this->employmentContractService;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        $date = $this->getEmpContract()->getStartDate();
        return $this->getDateTimeHelper()->formatDate($date);
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        $date = $this->getEmpContract()->getEndDate();
        return $this->getDateTimeHelper()->formatDate($date);
    }

    /**
     * @return EmployeeAttachment|null
     */
    public function getContractAttachment(): ?EmployeeAttachment
    {
        $empNumber = $this->getEmpContract()->getEmployee()->getEmpNumber();
        return $this->getEmploymentContractService()->getContractAttachment($empNumber);
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getEmpContract()->setEmployee($employee);
    }
}
