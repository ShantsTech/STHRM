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

namespace ShantsHRM\Authentication\Controller;

use ShantsHRM\Authentication\Auth\User as AuthUser;
use ShantsHRM\Authentication\Traits\CsrfTokenManagerTrait;
use ShantsHRM\Config\Config;
use ShantsHRM\Core\Authorization\Service\HomePageService;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\EventDispatcherTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\CorporateBranding\Traits\ThemeServiceTrait;
use ShantsHRM\Framework\Http\Request;

class LoginController extends AbstractVueController implements PublicControllerInterface
{
    use AuthUserTrait;
    use EventDispatcherTrait;
    use ThemeServiceTrait;
    use CsrfTokenManagerTrait;

    /**
     * @var HomePageService|null
     */
    protected ?HomePageService $homePageService = null;

    /**
     * @return HomePageService
     */
    public function getHomePageService(): HomePageService
    {
        if (!$this->homePageService instanceof HomePageService) {
            $this->homePageService = new HomePageService();
        }
        return $this->homePageService;
    }

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('auth-login');
        if ($this->getAuthUser()->hasFlash(AuthUser::FLASH_LOGIN_ERROR)) {
            $error = $this->getAuthUser()->getFlash(AuthUser::FLASH_LOGIN_ERROR);
            $component->addProp(
                new Prop(
                    'error',
                    Prop::TYPE_OBJECT,
                    $error[0] ?? []
                )
            );
        }

        $component->addProp(
            new Prop(
                'token',
                Prop::TYPE_STRING,
                $this->getCsrfTokenManager()->getToken('login')->getValue()
            )
        );
        $component->addProp(
            new Prop('login-logo-src', Prop::TYPE_STRING, $request->getBasePath() . '/images/ohrm_logo.png')
        );

        $assetsVersion = Config::get(Config::VUE_BUILD_TIMESTAMP);
        $loginBannerUrl = $request->getBasePath()
            . "/images/ohrm_branding.png?$assetsVersion";
        if (!is_null($this->getThemeService()->getImageETag('login_banner'))) {
            $loginBannerUrl = $request->getBaseUrl()
                . "/admin/theme/image/loginBanner?$assetsVersion";
        }
        $component->addProp(
            new Prop('login-banner-src', Prop::TYPE_STRING, $loginBannerUrl)
        );
        $component->addProp(
            new Prop('show-social-media', Prop::TYPE_BOOLEAN, $this->getThemeService()->showSocialMediaImages())
        );
        $component->addProp(new Prop('is-demo-mode', Prop::TYPE_BOOLEAN, Config::PRODUCT_MODE === Config::MODE_DEMO));
        $this->setComponent($component);
        $this->setTemplate('no_header.html.twig');
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request)
    {
        if ($this->getAuthUser()->isAuthenticated()) {
            $homePagePath = $this->getHomePageService()->getHomePagePath();
            return $this->redirect($homePagePath);
        }
        return parent::handle($request);
    }
}
