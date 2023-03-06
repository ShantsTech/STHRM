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

namespace ShantsHRM\Core\Report\DisplayField\Subordinate\Dto;

use ShantsHRM\Core\Report\DisplayField\NormalizableDTO;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\ReportingMethod;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;

class Subordinate extends NormalizableDTO
{
    use EntityManagerHelperTrait;
    use EmployeeServiceTrait;

    private ?int $empNumber = null;

    public function __construct(?int $empNumber)
    {
        $this->empNumber = $empNumber;
    }

    /**
     * @inheritDoc
     */
    public function toArray(array $fields): array
    {
        /** @var Employee $employee */
        $employee = $this->getReference(Employee::class, $this->empNumber);
        return $this->normalizeArray($employee->getSubordinates(), $fields);
    }

    /**
     * @inheritDoc
     * @param Employee $item
     */
    protected function callGetterOnItem($item, string $field, array $getter): ?string
    {
        if ($field === 'subReportingMethod') {
            $reportingMethod = $this->getEmployeeService()
                ->getEmployeeDao()
                ->getReportingMethod($item->getEmpNumber(), $this->empNumber);
            if ($reportingMethod instanceof ReportingMethod) {
                return $reportingMethod->getName();
            }
            return null;
        }
        return parent::callGetterOnItem($item, $field, $getter);
    }

    /**
     * @inheritDoc
     */
    protected function getFieldGetterMap(): array
    {
        return [
            'subordinateFirstName' => ['getFirstName'],
            'subordinateLastName' => ['getLastName'],
            'subReportingMethod' => [],
            'subordinateId' => ['getEmpNumber'],
        ];
    }
}
