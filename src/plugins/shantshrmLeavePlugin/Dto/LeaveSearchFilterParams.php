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

namespace ShantsHRM\Leave\Dto;

use ShantsHRM\Core\Dto\FilterParams;

class LeaveSearchFilterParams extends FilterParams
{
    public const ALLOWED_SORT_FIELDS = ['leave.date'];

    /**
     * @var int|null
     */
    private ?int $leaveRequestId = null;

    public function __construct()
    {
        $this->setSortField('leave.date');
    }

    /**
     * @return int|null
     */
    public function getLeaveRequestId(): ?int
    {
        return $this->leaveRequestId;
    }

    /**
     * @param int|null $leaveRequestId
     */
    public function setLeaveRequestId(?int $leaveRequestId): void
    {
        $this->leaveRequestId = $leaveRequestId;
    }
}