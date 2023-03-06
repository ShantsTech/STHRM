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
use ShantsHRM\Entity\TimesheetActionLog;
use ShantsHRM\Entity\User;

class TimesheetActionLogDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    /**
     * @var TimesheetActionLog
     */
    protected TimesheetActionLog $timesheetActionLog;

    /**
     * @param TimesheetActionLog $timesheetActionLog
     */
    public function __construct(TimesheetActionLog $timesheetActionLog)
    {
        $this->timesheetActionLog = $timesheetActionLog;
    }

    /**
     * @return TimesheetActionLog
     */
    protected function getTimesheetActionLog(): TimesheetActionLog
    {
        return $this->timesheetActionLog;
    }

    /**
     * @return string Y-m-d date
     */
    public function getDate(): string
    {
        return $this->getDateTimeHelper()->formatDate($this->getTimesheetActionLog()->getDate());
    }

    /**
     * @return string e.g ['Submitted', 'Approved', 'Reset']
     */
    public function getActionLabel(): string
    {
        return ucwords(strtolower($this->timesheetActionLog->getAction()));
    }

    /**
     * @param int $userId
     * @return void
     */
    public function setUserId(int $userId): void
    {
        $user = $this->getReference(User::class, $userId);
        $this->getTimesheetActionLog()->setPerformedUser($user);
    }
}
