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

use ShantsHRM\Authentication\Auth\AuthProviderChain;
use ShantsHRM\Authentication\Auth\LocalAuthProvider;
use ShantsHRM\Authentication\Auth\User as AuthUser;
use ShantsHRM\Authentication\Csrf\CsrfTokenManager;
use ShantsHRM\Authentication\Subscriber\AdministratorAccessSubscriber;
use ShantsHRM\Authentication\Subscriber\AuthenticationSubscriber;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Event\EventDispatcher;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\PluginConfigurationInterface;
use ShantsHRM\Framework\Services;
use Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage;

class AuthenticationPluginConfiguration implements PluginConfigurationInterface
{
    use ServiceContainerTrait;

    /**
     * @inheritDoc
     */
    public function initialize(Request $request): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->getContainer()->get(Services::EVENT_DISPATCHER);
        $dispatcher->addSubscriber(new AuthenticationSubscriber());
        $dispatcher->addSubscriber(new AdministratorAccessSubscriber());
        $this->getContainer()->register(Services::AUTH_USER)
            ->setFactory([AuthUser::class, 'getInstance']);

        $this->getContainer()->register(Services::CSRF_TOKEN_STORAGE, NativeSessionTokenStorage::class);
        $this->getContainer()->register(Services::CSRF_TOKEN_MANAGER, CsrfTokenManager::class);
        /** @var AuthProviderChain $authProviderChain */
        $authProviderChain = $this->getContainer()->get(Services::AUTH_PROVIDER_CHAIN);
        $authProviderChain->addProvider(new LocalAuthProvider());
    }
}
