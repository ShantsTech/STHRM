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

use ShantsHRM\Authentication\Auth\User as AuthUser;
use ShantsHRM\Core\Service\EmailQueueService;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\LoggerTrait;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Framework\Event\AbstractEventSubscriber;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MailerSubscriber extends AbstractEventSubscriber
{
    use LoggerTrait;
    use AuthUserTrait;
    use EntityManagerHelperTrait;

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => [
                ['onTerminateEvent', 0],
            ],
        ];
    }

    /**
     * @param TerminateEvent $event
     */
    public function onTerminateEvent(TerminateEvent $event): void
    {
        if ($this->getAuthUser()->hasFlash(AuthUser::FLASH_SEND_EMAIL_FLAG)) {
            $this->getAuthUser()->getFlash(AuthUser::FLASH_SEND_EMAIL_FLAG);
            $timeStart = microtime(true);
            $this->getLogger()->info("MailerSubscriber >> Start: $timeStart");

            $emailQueueService = new EmailQueueService();
            $emailQueueService->sendAllPendingMails();

            $timeEnd = microtime(true);
            $executionTime = ($timeEnd - $timeStart);
            $this->getLogger()->info("MailerSubscriber >> End: $timeEnd");
            $this->getLogger()->info("MailerSubscriber >> Execution time: $executionTime");
        }
    }
}
