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
use ShantsHRM\Entity\Leave;
use ShantsHRM\Entity\LeaveComment;
use ShantsHRM\Entity\User;

class LeaveCommentDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    /**
     * @var LeaveComment
     */
    private LeaveComment $leaveComment;

    /**
     * @param LeaveComment $leaveComment
     */
    public function __construct(LeaveComment $leaveComment)
    {
        $this->leaveComment = $leaveComment;
    }

    /**
     * @return LeaveComment
     */
    protected function getLeaveComment(): LeaveComment
    {
        return $this->leaveComment;
    }

    /**
     * @param int $empNumber
     */
    public function setCreatedByEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getLeaveComment()->setCreatedByEmployee($employee);
    }

    /**
     * @param int $userId
     */
    public function setCreatedByUserById(int $userId): void
    {
        /** @var User|null $user */
        $user = $this->getReference(User::class, $userId);
        $this->getLeaveComment()->setCreatedBy($user);
    }

    /**
     * @param int $id
     */
    public function setLeaveById(int $id): void
    {
        /** @var Leave|null $leave */
        $leave = $this->getReference(Leave::class, $id);
        $this->getLeaveComment()->setLeave($leave);
    }

    /**
     * @return string Y-m-d
     */
    public function getCreatedAtDate(): string
    {
        $dateTime = $this->getLeaveComment()->getCreatedAt();
        return $this->getDateTimeHelper()->formatDate($dateTime);
    }

    /**
     * @return string H:i
     */
    public function getCreatedAtTime(): string
    {
        $dateTime = $this->getLeaveComment()->getCreatedAt();
        return $this->getDateTimeHelper()->formatDateTimeToTimeString($dateTime);
    }
}
