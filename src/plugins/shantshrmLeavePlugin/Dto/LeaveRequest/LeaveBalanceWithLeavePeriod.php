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

namespace ShantsHRM\Leave\Dto\LeaveRequest;

use ShantsHRM\Leave\Dto\LeavePeriod;
use ShantsHRM\Leave\Entitlement\LeaveBalance;

class LeaveBalanceWithLeavePeriod
{
    private LeaveBalance $leaveBalance;

    private ?LeavePeriod $leavePeriod = null;

    /**
     * @param LeaveBalance $leaveBalance
     * @param LeavePeriod|null $leavePeriod
     */
    public function __construct(LeaveBalance $leaveBalance, ?LeavePeriod $leavePeriod = null)
    {
        $this->leaveBalance = $leaveBalance;
        $this->leavePeriod = $leavePeriod;
    }

    /**
     * @return LeaveBalance
     */
    public function getLeaveBalance(): LeaveBalance
    {
        return $this->leaveBalance;
    }

    /**
     * @param LeaveBalance $leaveBalance
     */
    public function setLeaveBalance(LeaveBalance $leaveBalance): void
    {
        $this->leaveBalance = $leaveBalance;
    }

    /**
     * @return LeavePeriod|null
     */
    public function getLeavePeriod(): ?LeavePeriod
    {
        return $this->leavePeriod;
    }

    /**
     * @param LeavePeriod|null $leavePeriod
     */
    public function setLeavePeriod(?LeavePeriod $leavePeriod): void
    {
        $this->leavePeriod = $leavePeriod;
    }
}
