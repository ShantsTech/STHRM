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

use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\ControllerTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestForwardableExceptionSubscriber extends AbstractEventSubscriber
{
    use ControllerTrait;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onExceptionEvent', 0],
            ],
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onExceptionEvent(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof RequestForwardableException) {
            $response = $this->forward(
                $exception->getController(),
                $exception->getAttributes(),
                $exception->getQuery()
            );
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }
}
