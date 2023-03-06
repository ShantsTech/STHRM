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

namespace ShantsHRM\Time\Subscriber;

use ShantsHRM\Core\Api\V2\Exception\ForbiddenException;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\Service\TextHelperTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use ShantsHRM\Time\Controller\TimesheetPeriodConfigController;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TimesheetPeriodSubscriber extends AbstractEventSubscriber
{
    use TextHelperTrait;
    use ConfigServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestEvent', 100],
            ],
        ];
    }

    /**
     * @param RequestEvent $event
     * @return void
     * @throws RequestForwardableException
     * @throws ForbiddenException
     */
    public function onRequestEvent(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $isTimeControllerPath = $this->getTextHelper()->strStartsWith($event->getRequest()->getPathInfo(), '/time');
            $isTimeApiPath = $this->getTextHelper()->strStartsWith($event->getRequest()->getPathInfo(), '/api/v2/time');
            if ($isTimeControllerPath || $isTimeApiPath) {
                $status = $this->getConfigService()->isTimesheetPeriodDefined();

                if (!$status && $isTimeControllerPath) {
                    throw new RequestForwardableException(TimesheetPeriodConfigController::class . '::handle');
                } elseif (!$status && $isTimeApiPath &&
                    $this->getTextHelper()->strStartsWith(
                        $event->getRequest()->getPathInfo(),
                        '/api/v2/time/time-sheet-period'
                    )) {
                    return;
                } elseif (!$status && $isTimeApiPath) {
                    throw new ForbiddenException('Unauthorized');
                }  // else: Timesheet period defined
            }
        }
    }
}
