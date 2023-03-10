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

namespace ShantsHRM\Core\Report\DisplayField\License\Dto;

use ShantsHRM\Core\Report\DisplayField\NormalizableDTO;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Entity\Employee;

class License extends NormalizableDTO
{
    use EntityManagerHelperTrait;

    private ?int $empNumber = null;

    /**
     * @param int|null $empNumber
     */
    public function __construct(?int $empNumber)
    {
        $this->empNumber = $empNumber;
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $fields): ?array
    {
        /** @var Employee $employee */
        $employee = $this->getReference(Employee::class, $this->empNumber);
        return $this->normalizeArray($employee->getLicenses(), $fields);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldGetterMap(): array
    {
        return [
            'empLicenseType' => ['getLicense', 'getName'],
            'empLicenseIssuedDate' => ['getDecorator', 'getLicenseIssuedDate'],
            'empLicenseExpiryDate' => ['getDecorator', 'getLicenseExpiryDate'],
            'empLicenseCode' => ['getLicense', 'getId'],
            'getLicenseNo' => ['getLicenseNo'],
        ];
    }
}
