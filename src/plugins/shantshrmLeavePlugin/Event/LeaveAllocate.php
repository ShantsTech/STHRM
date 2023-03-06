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

namespace ShantsHRM\Leave\Event;

use ShantsHRM\Entity\LeaveRequest;
use ShantsHRM\Entity\User;
use ShantsHRM\Entity\WorkflowStateMachine;
use ShantsHRM\Framework\Event\Event;
use ShantsHRM\Leave\Dto\LeaveRequest\DetailedLeaveRequest;

abstract class LeaveAllocate extends Event
{
    /**
     * @var LeaveRequest|DetailedLeaveRequest
     */
    private $leaveRequest;

    /**
     * @var WorkflowStateMachine
     */
    private WorkflowStateMachine $workflow;

    /**
     * @var User
     */
    private User $performer;

    /**
     * @param LeaveRequest|DetailedLeaveRequest $leaveRequest
     * @param WorkflowStateMachine $workflow
     * @param User $performer
     */
    public function __construct($leaveRequest, WorkflowStateMachine $workflow, User $performer)
    {
        $this->leaveRequest = $leaveRequest;
        $this->workflow = $workflow;
        $this->performer = $performer;
    }

    /**
     * @return DetailedLeaveRequest
     */
    public function getDetailedLeaveRequest(): DetailedLeaveRequest
    {
        if ($this->leaveRequest instanceof LeaveRequest) {
            $this->leaveRequest = new DetailedLeaveRequest($this->leaveRequest);
            $this->leaveRequest->fetchLeaves();
        }
        return $this->leaveRequest;
    }

    /**
     * @return WorkflowStateMachine
     */
    public function getWorkflow(): WorkflowStateMachine
    {
        return $this->workflow;
    }

    /**
     * @return User
     */
    public function getPerformer(): User
    {
        return $this->performer;
    }
}
