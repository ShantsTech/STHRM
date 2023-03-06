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

use ShantsHRM\Authentication\Auth\AuthProviderChain;
use ShantsHRM\Authentication\Auth\User as AuthUser;
use ShantsHRM\Authentication\Dto\AuthParams;
use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\Authentication\Exception\AuthenticationException;
use ShantsHRM\Authentication\Service\LoginService;
use ShantsHRM\Authentication\Traits\CsrfTokenManagerTrait;
use ShantsHRM\Core\Authorization\Service\HomePageService;
use ShantsHRM\Core\Controller\AbstractController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Http\RedirectResponse;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Session\Session;
use ShantsHRM\Framework\Routing\UrlGenerator;
use ShantsHRM\Framework\Services;
use Throwable;

class ValidateController extends AbstractController implements PublicControllerInterface
{
    use AuthUserTrait;
    use ServiceContainerTrait;
    use CsrfTokenManagerTrait;

    public const PARAMETER_USERNAME = 'username';
    public const PARAMETER_PASSWORD = 'password';

    /**
     * @var null|LoginService
     */
    protected ?LoginService $loginService = null;

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
     * @return LoginService
     */
    public function getLoginService(): LoginService
    {
        if (is_null($this->loginService)) {
            $this->loginService = new LoginService();
        }
        return $this->loginService;
    }

    public function handle(Request $request): RedirectResponse
    {
        $username = $request->request->get(self::PARAMETER_USERNAME, '');
        $password = $request->request->get(self::PARAMETER_PASSWORD, '');
        $credentials = new UserCredential($username, $password);

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->getContainer()->get(Services::URL_GENERATOR);
        $loginUrl = $urlGenerator->generate('auth_login', [], UrlGenerator::ABSOLUTE_URL);

        try {
            $token = $request->request->get('_token');
            if (!$this->getCsrfTokenManager()->isValid('login', $token)) {
                throw AuthenticationException::invalidCsrfToken();
            }

            /** @var AuthProviderChain $authProviderChain */
            $authProviderChain = $this->getContainer()->get(Services::AUTH_PROVIDER_CHAIN);
            $success = $authProviderChain->authenticate(new AuthParams($credentials));

            if (!$success) {
                throw AuthenticationException::invalidCredentials();
            }
            $this->getAuthUser()->setIsAuthenticated($success);
            $this->getLoginService()->addLogin($credentials);
        } catch (AuthenticationException $e) {
            $this->getAuthUser()->addFlash(AuthUser::FLASH_LOGIN_ERROR, $e->normalize());
            return new RedirectResponse($loginUrl);
        } catch (Throwable $e) {
            $this->getAuthUser()->addFlash(
                AuthUser::FLASH_LOGIN_ERROR,
                [
                    'error' => AuthenticationException::UNEXPECT_ERROR,
                    'message' => 'Unexpected error occurred',
                ]
            );
            return new RedirectResponse($loginUrl);
        }

        /** @var Session $session */
        $session = $this->getContainer()->get(Services::SESSION);
        //Recreate the session
        $session->migrate(true);

        if ($this->getAuthUser()->hasAttribute(AuthUser::SESSION_TIMEOUT_REDIRECT_URL)) {
            $redirectUrl = $this->getAuthUser()->getAttribute(AuthUser::SESSION_TIMEOUT_REDIRECT_URL);
            $this->getAuthUser()->removeAttribute(AuthUser::SESSION_TIMEOUT_REDIRECT_URL);
            $logoutUrl = $urlGenerator->generate('auth_logout', [], UrlGenerator::ABSOLUTE_URL);

            if ($redirectUrl !== $loginUrl || $redirectUrl !== $logoutUrl) {
                return new RedirectResponse($redirectUrl);
            }
        }

        $homePagePath = $this->getHomePageService()->getHomePagePath();
        return $this->redirect($homePagePath);
    }
}
