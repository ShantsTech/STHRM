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

namespace ShantsHRM\Dashboard\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\CollectionNormalizable;
use ShantsHRM\Core\Api\V2\Serializer\ModelConstructorArgsAwareInterface;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\Leave;

class EmployeesOnLeaveListModel implements CollectionNormalizable, ModelConstructorArgsAwareInterface
{
    use UserRoleManagerTrait;
    use AuthUserTrait;

    private array $leaves;

    /**
     * @param Leave[] $leaves
     */
    public function __construct(array $leaves)
    {
        $this->leaves = $leaves;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $normalizedLeaves = [];
        foreach ($this->leaves as $leave) {
            $normalizedLeave = [
                'id' => $leave->getId(),
                'date' => $leave->getDecorator()->getLeaveDate(),
                'lengthHours' => $leave->getLengthHours(),
                'employee' => [
                    'empNumber' => $leave->getEmployee()->getEmpNumber(),
                    'lastName' => $leave->getEmployee()->getLastName(),
                    'firstName' => $leave->getEmployee()->getFirstName(),
                    'middleName' => $leave->getEmployee()->getMiddleName(),
                    'employeeId' => $leave->getEmployee()->getEmployeeId(),
                    'terminationId' => $leave->getEmployee()->getEmployeeTerminationRecord() ? $leave->getEmployee()
                        ->getEmployeeTerminationRecord()->getId() : null
                ],
                'duration' => $leave->getDecorator()->getLeaveDuration(),
                'endTime' => $leave->getDecorator()->getEndTime(),
                'startTime' => $leave->getDecorator()->getStartTime(),
            ];
            if ($this->getUserRoleManager()
                ->isEntityAccessible(Employee::class, $leave->getEmployee()->getEmpNumber())
            ) {
                $normalizedLeave['leaveType'] = [
                    'id' => $leave->getLeaveType()->getId(),
                    'name' => $leave->getLeaveType()->getName(),
                    'deleted' => $leave->getLeaveType()->isDeleted()
                ];
            }
            $normalizedLeaves[] = $normalizedLeave;
        }
        return $normalizedLeaves;
    }
}
