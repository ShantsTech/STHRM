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
use ShantsHRM\Entity\CurrencyType;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\EmployeeSalary;
use ShantsHRM\Entity\PayGrade;
use ShantsHRM\Entity\PayPeriod;

class EmployeeSalaryDecorator
{
    use EntityManagerHelperTrait;

    /**
     * @var EmployeeSalary
     */
    protected EmployeeSalary $employeeSalary;

    /**
     * @param EmployeeSalary $employeeSalary
     */
    public function __construct(EmployeeSalary $employeeSalary)
    {
        $this->employeeSalary = $employeeSalary;
    }

    /**
     * @return EmployeeSalary
     */
    protected function getEmployeeSalary(): EmployeeSalary
    {
        return $this->employeeSalary;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getEmployeeSalary()->setEmployee($employee);
    }

    /**
     * @param int|null $id
     */
    public function setPayPeriodById(?int $id): void
    {
        /** @var PayPeriod|null $payPeriod */
        $payPeriod = is_null($id) ? null : $this->getReference(PayPeriod::class, $id);
        $this->getEmployeeSalary()->setPayPeriod($payPeriod);
    }

    /**
     * @param int|null $id
     */
    public function setPayGradeById(?int $id): void
    {
        /** @var PayGrade|null $payGrade */
        $payGrade = is_null($id) ? null : $this->getReference(PayGrade::class, $id);
        $this->getEmployeeSalary()->setPayGrade($payGrade);
    }

    /**
     * @param string $id
     */
    public function setCurrencyTypeById(string $id): void
    {
        /** @var CurrencyType|null $currencyType */
        $currencyType = $this->getReference(CurrencyType::class, $id);
        $this->getEmployeeSalary()->setCurrencyType($currencyType);
    }
}
