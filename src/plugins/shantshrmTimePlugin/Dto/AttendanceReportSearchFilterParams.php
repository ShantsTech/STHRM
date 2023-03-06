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

namespace ShantsHRM\Time\Dto;

use ShantsHRM\Leave\Dto\DateRangeSearchFilterParams;
use ShantsHRM\Pim\Dto\Traits\SubunitIdChainTrait;

class AttendanceReportSearchFilterParams extends DateRangeSearchFilterParams
{
    use SubunitIdChainTrait;

    public const ALLOWED_SORT_FIELDS = ['employee.lastName'];

    /**
     * @var int[]|null
     */
    private ?array $employeeNumbers = null;

    /**
     * @var int|null
     */
    private ?int $jobTitleId = null;

    /**
     * @var int|null
     */
    private ?int $subUnitId = null;

    /**
     * @var int|null
     */
    private ?int $employmentStatusId = null;

    public function __construct()
    {
        $this->setSortField('employee.lastName');
    }

    /**
     * @return int|null
     */
    public function getJobTitleId(): ?int
    {
        return $this->jobTitleId;
    }

    /**
     * @param int|null $jobTitleId
     */
    public function setJobTitleId(?int $jobTitleId): void
    {
        $this->jobTitleId = $jobTitleId;
    }

    /**
     * @return int|null
     */
    public function getSubUnitId(): ?int
    {
        return $this->subUnitId;
    }

    /**
     * @param int|null $subUnitId
     */
    public function setSubUnitId(?int $subUnitId): void
    {
        $this->subUnitId = $subUnitId;
    }

    /**
     * @return int|null
     */
    public function getEmploymentStatusId(): ?int
    {
        return $this->employmentStatusId;
    }

    /**
     * @param int|null $employmentStatusId
     */
    public function setEmploymentStatusId(?int $employmentStatusId): void
    {
        $this->employmentStatusId = $employmentStatusId;
    }

    /**
     * @return int[]|null
     */
    public function getEmployeeNumbers(): ?array
    {
        return $this->employeeNumbers;
    }

    /**
     * @param int[]|null $employeeNumbers
     */
    public function setEmployeeNumbers(?array $employeeNumbers): void
    {
        $this->employeeNumbers = $employeeNumbers;
    }
}
