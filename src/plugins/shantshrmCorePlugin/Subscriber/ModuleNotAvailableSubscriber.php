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

namespace ShantsHRM\Core\Subscriber;

use ShantsHRM\Core\Api\V2\Exception\ForbiddenException;
use ShantsHRM\Core\Controller\Common\DisabledModuleController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Service\ModuleService;
use ShantsHRM\Core\Traits\Service\TextHelperTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ModuleNotAvailableSubscriber extends AbstractEventSubscriber
{
    use TextHelperTrait;

    /**
     * @var ModuleService|null
     */
    protected ?ModuleService $moduleService = null;

    /**
     * Get Module Service
     * @return ModuleService|null
     */
    public function getModuleService(): ModuleService
    {
        if (is_null($this->moduleService)) {
            $this->moduleService = new ModuleService();
        }
        return $this->moduleService;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestEvent', 200],
            ],
        ];
    }

    /**
     * @param RequestEvent $event
     * @throws ForbiddenException
     * @throws RequestForwardableException
     * @return void
     */
    public function onRequestEvent(RequestEvent $event): void
    {
        if ($event->isMainRequest()) {
            $disabledModules = $this->getModuleService()->getModuleDao()->getDisabledModuleList();
            foreach ($disabledModules as $disabledModule) {
                if ($this->getTextHelper()->strStartsWith($event->getRequest()->getPathInfo(), '/' . $disabledModule['name'])) {
                    throw new RequestForwardableException(DisabledModuleController::class . '::handle');
                }
                if ($this->getTextHelper()->strStartsWith($event->getRequest()->getPathInfo(), '/api/v2/' . $disabledModule['name'])) {
                    throw new ForbiddenException('Unauthorized');
                }
            }
        }
    }
}
