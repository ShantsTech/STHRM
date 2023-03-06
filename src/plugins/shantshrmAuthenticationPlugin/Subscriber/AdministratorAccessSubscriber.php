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

namespace ShantsHRM\Authentication\Subscriber;

use ShantsHRM\Authentication\Controller\AdminPrivilegeController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\Service\TextHelperTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AdministratorAccessSubscriber extends AbstractEventSubscriber
{
    use AuthUserTrait;
    use TextHelperTrait;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                ['onControllerEvent', -10000]
            ]
        ];
    }

    /**
     * @param ControllerEvent $controllerEvent
     * @return void
     */
    public function onControllerEvent(ControllerEvent $controllerEvent): void
    {
        if ($controllerEvent->getController()[0] instanceof AdminPrivilegeController) {
            return;
        }
        if ($controllerEvent->getController()[0] instanceof AbstractVueController) {
            $this->getAuthUser()->setHasAdminAccess(false);
        }
    }
}
