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

use ShantsHRM\Authentication\Auth\AuthProviderChain;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Console\Console;
use ShantsHRM\Framework\Console\ConsoleConfigurationInterface;
use ShantsHRM\Framework\Console\Scheduling\CommandInfo;
use ShantsHRM\Framework\Console\Scheduling\Schedule;
use ShantsHRM\Framework\Console\Scheduling\SchedulerConfigurationInterface;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Logger\LoggerFactory;
use ShantsHRM\Framework\PluginConfigurationInterface;
use ShantsHRM\Framework\Services;
use ShantsHRM\LDAP\Auth\LDAPAuthProvider;
use ShantsHRM\LDAP\Command\LDAPSyncUserCommand;
use ShantsHRM\LDAP\Dto\LDAPSetting;

class LDAPAuthenticationPluginConfiguration implements
    PluginConfigurationInterface,
    ConsoleConfigurationInterface,
    SchedulerConfigurationInterface
{
    use ServiceContainerTrait;
    use ConfigServiceTrait;

    /**
     * @inheritDoc
     */
    public function initialize(Request $request): void
    {
        $this->getContainer()->register(Services::LDAP_LOGGER)
            ->setFactory([LoggerFactory::class, 'getLogger'])
            ->addArgument('LDAP')
            ->addArgument('ldap.log');
        $ldapSettings = $this->getConfigService()->getLDAPSetting();
        if ($ldapSettings instanceof LDAPSetting && $ldapSettings->isEnable()) {
            /** @var AuthProviderChain $authProviderChain */
            $authProviderChain = $this->getContainer()->get(Services::AUTH_PROVIDER_CHAIN);
            $authProviderChain->addProvider(new LDAPAuthProvider());
        }
    }

    /**
     * @inheritDoc
     */
    public function registerCommands(Console $console): void
    {
        $console->add(new LDAPSyncUserCommand());
    }

    /**
     * @inheritDoc
     */
    public function schedule(Schedule $schedule): void
    {
        $ldapSettings = $this->getConfigService()->getLDAPSetting();
        if ($ldapSettings instanceof LDAPSetting && $ldapSettings->isEnable()) {
            $interval = 1;
            if ($ldapSettings->getSyncInterval() <= 23 && $ldapSettings->getSyncInterval() >= 1) {
                $interval = $ldapSettings->getSyncInterval();
            }

            $schedule->add(new CommandInfo('shantshrm:ldap-sync-user'))
                ->cron("0 */$interval * * *");
        }
    }
}
