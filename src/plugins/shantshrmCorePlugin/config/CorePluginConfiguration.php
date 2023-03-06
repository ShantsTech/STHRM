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

use ShantsHRM\Config\Config;
use ShantsHRM\Core\Authorization\Helper\UserRoleManagerHelper;
use ShantsHRM\Core\Authorization\Manager\UserRoleManagerFactory;
use ShantsHRM\Core\Command\EnableTestLanguagePackCommand;
use ShantsHRM\Core\Command\RunScheduleCommand;
use ShantsHRM\Core\Helper\ClassHelper;
use ShantsHRM\Core\Registration\Subscriber\RegistrationEventPersistSubscriber;
use ShantsHRM\Core\Service\CacheService;
use ShantsHRM\Core\Service\ConfigService;
use ShantsHRM\Core\Service\DateTimeHelperService;
use ShantsHRM\Core\Service\MenuService;
use ShantsHRM\Core\Service\ModuleService;
use ShantsHRM\Core\Service\NormalizerService;
use ShantsHRM\Core\Service\NumberHelperService;
use ShantsHRM\Core\Service\ReportGeneratorService;
use ShantsHRM\Core\Service\TextHelperService;
use ShantsHRM\Core\Subscriber\ApiAuthorizationSubscriber;
use ShantsHRM\Core\Subscriber\ExceptionSubscriber;
use ShantsHRM\Core\Subscriber\GlobalConfigSubscriber;
use ShantsHRM\Core\Subscriber\MailerSubscriber;
use ShantsHRM\Core\Subscriber\ModuleNotAvailableSubscriber;
use ShantsHRM\Core\Subscriber\RequestBodySubscriber;
use ShantsHRM\Core\Subscriber\RequestForwardableExceptionSubscriber;
use ShantsHRM\Core\Subscriber\ScreenAuthorizationSubscriber;
use ShantsHRM\Core\Subscriber\SessionSubscriber;
use ShantsHRM\Core\Traits\EventDispatcherTrait;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Console\Console;
use ShantsHRM\Framework\Console\ConsoleConfigurationInterface;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Session\NativeSessionStorage;
use ShantsHRM\Framework\Http\Session\Session;
use ShantsHRM\Framework\PluginConfigurationInterface;
use ShantsHRM\Framework\Services;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpKernel\EventListener\SessionListener;
use Symfony\Component\HttpKernel\KernelEvents;

class CorePluginConfiguration implements PluginConfigurationInterface, ConsoleConfigurationInterface
{
    use ServiceContainerTrait;
    use EventDispatcherTrait;
    use ConfigServiceTrait;

    /**
     * @inheritDoc
     */
    public function initialize(Request $request): void
    {
        $isSecure = $request->isSecure();
        $path = $request->getBasePath();
        $options = [
            'name' => $isSecure ? 'shantshrm' : '_shantshrm',
            'cookie_secure' => $isSecure,
            'cookie_httponly' => true,
            'cookie_path' => $path,
            'cookie_samesite' => 'Strict',
        ];
        $sessionStorage = new NativeSessionStorage(
            $options,
            new NativeFileSessionHandler(Config::get(Config::SESSION_DIR))
        );
        $session = new Session($sessionStorage);
        $session->start();

        $this->getContainer()->set(Services::SESSION_STORAGE, $sessionStorage);
        $this->getContainer()->set(Services::SESSION, $session);
        $this->getContainer()->register(Services::CONFIG_SERVICE, ConfigService::class);
        $this->getContainer()->register(Services::NORMALIZER_SERVICE, NormalizerService::class);
        $this->getContainer()->register(Services::DATETIME_HELPER_SERVICE, DateTimeHelperService::class);
        $this->getContainer()->register(Services::TEXT_HELPER_SERVICE, TextHelperService::class);
        $this->getContainer()->register(Services::NUMBER_HELPER_SERVICE, NumberHelperService::class);
        $this->getContainer()->register(Services::CLASS_HELPER, ClassHelper::class);
        $this->getContainer()->register(Services::USER_ROLE_MANAGER)
            ->setFactory([UserRoleManagerFactory::class, 'getUserRoleManager']);
        $this->getContainer()->register(Services::USER_ROLE_MANAGER_HELPER, UserRoleManagerHelper::class);
        $this->getContainer()->register(Services::CACHE)->setFactory([CacheService::class, 'getCache']);
        $this->getContainer()->register(Services::MENU_SERVICE, MenuService::class);
        $this->getContainer()->register(Services::MODULE_SERVICE, ModuleService::class);
        $this->getContainer()->register(Services::REPORT_GENERATOR_SERVICE, ReportGeneratorService::class);

        $this->registerCoreSubscribers();
    }

    private function registerCoreSubscribers(): void
    {
        $this->getEventDispatcher()->addSubscriber(new ExceptionSubscriber());
        $this->getEventDispatcher()->addListener(
            KernelEvents::REQUEST,
            [new SessionListener($this->getContainer()), 'onKernelRequest'],
        );
        $this->getEventDispatcher()->addSubscriber(new SessionSubscriber());
        $this->getEventDispatcher()->addSubscriber(new RequestForwardableExceptionSubscriber());
        $this->getEventDispatcher()->addSubscriber(new ScreenAuthorizationSubscriber());
        $this->getEventDispatcher()->addSubscriber(new ApiAuthorizationSubscriber());
        $this->getEventDispatcher()->addSubscriber(new RequestBodySubscriber());
        $this->getEventDispatcher()->addSubscriber(new MailerSubscriber());
        $this->getEventDispatcher()->addSubscriber(new ModuleNotAvailableSubscriber());
        $this->getEventDispatcher()->addSubscriber(new GlobalConfigSubscriber());
        if ($this->getConfigService()->getInstanceIdentifier() !== null) {
            $this->getEventDispatcher()->addSubscriber(new RegistrationEventPersistSubscriber());
        }
    }

    /**
     * @inheritDoc
     */
    public function registerCommands(Console $console): void
    {
        $console->add(new RunScheduleCommand());
        if (Config::PRODUCT_MODE !== Config::MODE_PROD) {
            $console->add(new EnableTestLanguagePackCommand());
        }
    }
}
