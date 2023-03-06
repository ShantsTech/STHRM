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
use ShantsHRM\Entity\Project;
use ShantsHRM\Entity\ProjectActivity;
use ShantsHRM\Entity\Timesheet;
use ShantsHRM\Entity\TimesheetItem;

class TimesheetItemDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    private TimesheetItem $timesheetItem;

    /**
     * @param TimesheetItem $timesheetItem
     */
    public function __construct(TimesheetItem $timesheetItem)
    {
        $this->timesheetItem = $timesheetItem;
    }

    /**
     * @return TimesheetItem
     */
    protected function getTimesheetItem(): TimesheetItem
    {
        return $this->timesheetItem;
    }

    /**
     * @param int $id
     */
    public function setProjectById(int $id): void
    {
        $project = $this->getReference(Project::class, $id);
        $this->getTimesheetItem()->setProject($project);
    }

    /**
     * @param int $id
     */
    public function setProjectActivityById(int $id): void
    {
        $projectActivity = $this->getReference(ProjectActivity::class, $id);
        $this->getTimesheetItem()->setProjectActivity($projectActivity);
    }

    /**
     * @param int $timesheetId
     */
    public function setTimesheetById(int $timesheetId): void
    {
        $timesheet = $this->getReference(Timesheet::class, $timesheetId);
        $this->getTimesheetItem()->setTimesheet($timesheet);
    }

    /**
     * @param int $employeeNumber
     */
    public function setEmployeeByEmployeeNumber(int $employeeNumber): void
    {
        $employee = $this->getReference(Employee::class, $employeeNumber);
        $this->getTimesheetItem()->setEmployee($employee);
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->getDateTimeHelper()->formatDate($this->getTimesheetItem()->getDate());
    }
}
