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

namespace ShantsHRM\Leave\Api\Model;

use ShantsHRM\Entity\Leave;
use ShantsHRM\Entity\LeaveComment;

class OverlapLeaveModel extends LeaveModel
{
    private Leave $leave;

    /**
     * @param Leave $leave
     */
    public function __construct(Leave $leave)
    {
        $this->leave = $leave;
        parent::__construct($leave);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $normalizedLeave = parent::toArray();
        if ($this->leave->getDecorator()->getLastComment() instanceof LeaveComment) {
            return $normalizedLeave;
        }

        $this->setEntity($this->leave->getLeaveRequest());
        $this->setFilters(
            [
                ['getDecorator', 'getLastComment', 'getId'],
                ['getDecorator', 'getLastComment', 'getComment'],
                ['getDecorator', 'getLastComment', 'getDecorator', 'getCreatedAtDate'],
                ['getDecorator', 'getLastComment', 'getDecorator', 'getCreatedAtTime'],
            ]
        );
        $this->setAttributeNames(
            [
                ['lastComment', 'id'],
                ['lastComment', 'comment'],
                ['lastComment', 'date'],
                ['lastComment', 'time'],
            ]
        );
        return array_merge($normalizedLeave, parent::toArray());
    }
}
