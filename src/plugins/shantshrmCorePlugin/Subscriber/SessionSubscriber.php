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

use ShantsHRM\Config\Config;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use ShantsHRM\Framework\Http\Session\Session;
use ShantsHRM\Framework\Services;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SessionSubscriber extends AbstractEventSubscriber
{
    use ServiceContainerTrait;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestEvent', 100000],
            ],
        ];
    }

    public function onRequestEvent(RequestEvent $event)
    {
        /** @var Session $session */
        $session = $this->getContainer()->get(Services::SESSION);

        if (time() - $session->getMetadataBag()->getLastUsed() > Config::get(Config::MAX_SESSION_IDLE_TIME)) {
            $session->invalidate();
        }
    }
}
