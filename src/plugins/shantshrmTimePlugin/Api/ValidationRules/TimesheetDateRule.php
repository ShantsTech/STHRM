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

namespace ShantsHRM\Time\Api\ValidationRules;

use DateTime;
use ShantsHRM\Core\Api\V2\Validator\Rules\AbstractRule;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Time\Traits\Service\TimesheetServiceTrait;

class TimesheetDateRule extends AbstractRule
{
    use TimesheetServiceTrait;
    use DateTimeHelperTrait;

    private $empNumber;

    /**
     * @param  int  $empNumber
     */
    public function __construct($empNumber)
    {
        $this->empNumber = $empNumber;
    }

    /**
     * @inheritDoc
     */
    public function validate($input): bool
    {
        return !(new DateTime($input) > $this->getDateTimeHelper()->getNow()) &&
            !($this->getTimesheetService()
                ->hasTimesheetForDate($this->empNumber, new DateTime($input)));
    }
}
