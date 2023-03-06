<?php
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 Shants Tech LLC., http://www.hrm.shants-tech.com
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

namespace ShantsHRM\Attendance\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\CollectionNormalizable;
use ShantsHRM\Core\Api\V2\Serializer\ModelConstructorArgsAwareInterface;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\Service\NumberHelperTrait;

class EmployeeAttendanceSummaryListModel implements CollectionNormalizable, ModelConstructorArgsAwareInterface
{
    use DateTimeHelperTrait;
    use NumberHelperTrait;

    /**
     * @var array
     */
    private array $employeeAttendanceSummaryRecords;

    public function __construct(array $employeeAttendanceSummaryRecords)
    {
        $this->employeeAttendanceSummaryRecords = $employeeAttendanceSummaryRecords;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->employeeAttendanceSummaryRecords as $employeeAttendanceRecord) {
            $result[] = [
                'empNumber' => $employeeAttendanceRecord['empNumber'],
                'lastName' => $employeeAttendanceRecord['lastName'],
                'firstName' => $employeeAttendanceRecord['firstName'],
                'middleName' => $employeeAttendanceRecord['middleName'],
                'employeeId' => $employeeAttendanceRecord['employeeId'],
                'terminationId' => $employeeAttendanceRecord['terminationId'],
                'sum' => [
                    'hours' => floor((float)$employeeAttendanceRecord['total'] / 3600),
                    'minutes' => ((float)$employeeAttendanceRecord['total'] / 60) % 60,
                    'label' => $this->getNumberHelper()->numberFormat(
                        (float)$employeeAttendanceRecord['total'] / 3600,
                        2
                    ),
                ],
            ];
        }
        return $result;
    }
}
