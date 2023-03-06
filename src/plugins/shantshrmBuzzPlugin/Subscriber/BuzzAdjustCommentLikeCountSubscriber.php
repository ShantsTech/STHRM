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
 * Boston, MA 02110-1301, USA
 */

namespace ShantsHRM\Buzz\Subscriber;

use ShantsHRM\Buzz\Traits\Service\BuzzServiceTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use ShantsHRM\Maintenance\Event\MaintenanceEvent;
use ShantsHRM\Maintenance\Event\PurgeEmployee;
use ShantsHRM\Pim\Event\EmployeeDeletedEvent;
use ShantsHRM\Pim\Event\EmployeeEvents;

class BuzzAdjustCommentLikeCountSubscriber extends AbstractEventSubscriber
{
    use BuzzServiceTrait;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MaintenanceEvent::PURGE_EMPLOYEE_END => 'onEmployeePurgingEnd',
            EmployeeEvents::EMPLOYEES_DELETED => 'onEmployeesDeleted',
        ];
    }

    /**
     * @param PurgeEmployee $purgeEmployee
     */
    public function onEmployeePurgingEnd(PurgeEmployee $purgeEmployee): void
    {
        $this->getBuzzService()->getBuzzDao()->adjustLikeAndCommentCountsOnShares();
        $this->getBuzzService()->getBuzzDao()->adjustLikeCountOnComments();
    }

    /**
     * @param EmployeeDeletedEvent $event
     */
    public function onEmployeesDeleted(EmployeeDeletedEvent $event): void
    {
        $this->getBuzzService()->getBuzzDao()->adjustLikeAndCommentCountsOnShares();
        $this->getBuzzService()->getBuzzDao()->adjustLikeCountOnComments();
    }
}
