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
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\EmployeeLicense;
use ShantsHRM\Entity\License;

class EmployeeLicenseDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    /**
     * @var EmployeeLicense
     */
    protected EmployeeLicense $employeeLicense;

    /**
     * @param EmployeeLicense $employeeLicense
     */
    public function __construct(EmployeeLicense $employeeLicense)
    {
        $this->employeeLicense = $employeeLicense;
    }

    /**
     * @return EmployeeLicense
     */
    protected function getEmployeeLicense(): EmployeeLicense
    {
        return $this->employeeLicense;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getEmployeeLicense()->setEmployee($employee);
    }

    /**
     * @param int $licenseId
     */
    public function setLicenseByLicenseId(int $licenseId): void
    {
        /** @var License|null $license */
        $license = $this->getReference(License::class, $licenseId);
        $this->getEmployeeLicense()->setLicense($license);
    }

    /**
     * @return string|null
     */
    public function getLicenseIssuedDate(): ?string
    {
        $date = $this->getEmployeeLicense()->getLicenseIssuedDate();
        return $this->getDateTimeHelper()->formatDate($date);
    }

    /**
     * @return string|null
     */
    public function getLicenseExpiryDate(): ?string
    {
        $date = $this->getEmployeeLicense()->getLicenseExpiryDate();
        return $this->getDateTimeHelper()->formatDate($date);
    }
}
