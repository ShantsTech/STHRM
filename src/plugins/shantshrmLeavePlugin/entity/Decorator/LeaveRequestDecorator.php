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
use ShantsHRM\Entity\LeaveRequest;
use ShantsHRM\Entity\LeaveRequestComment;
use ShantsHRM\Entity\LeaveType;
use ShantsHRM\ORM\ListSorter;

class LeaveRequestDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    /**
     * @var LeaveRequest
     */
    private LeaveRequest $leaveRequest;

    /**
     * @param LeaveRequest $leaveRequest
     */
    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    /**
     * @return LeaveRequest
     */
    protected function getLeaveRequest(): LeaveRequest
    {
        return $this->leaveRequest;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getLeaveRequest()->setEmployee($employee);
    }

    /**
     * @param int $id
     */
    public function setLeaveTypeById(int $id): void
    {
        /** @var LeaveType|null $leaveType */
        $leaveType = $this->getReference(LeaveType::class, $id);
        $this->getLeaveRequest()->setLeaveType($leaveType);
    }

    /**
     * @return string
     */
    public function getDateApplied(): string
    {
        return $this->getDateTimeHelper()->formatDate($this->getLeaveRequest()->getDateApplied());
    }

    /**
     * @return LeaveRequestComment|null
     */
    public function getLastComment(): ?LeaveRequestComment
    {
        return $this->getRepository(LeaveRequestComment::class)
            ->findOneBy(['leaveRequest' => $this->getLeaveRequest()->getId()], ['createdAt' => ListSorter::DESCENDING]);
    }
}
