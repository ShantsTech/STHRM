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
use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\Authentication\Exception\AuthenticationException;
use ShantsHRM\Authentication\Service\AuthenticationService;
use ShantsHRM\Authentication\Service\LoginService;
use ShantsHRM\Authentication\Traits\CsrfTokenManagerTrait;
use ShantsHRM\Core\Controller\AbstractController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Framework\Http\RedirectResponse;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;

class AdministratorVerifyController extends AbstractController
{
    use AuthUserTrait;
    use UserRoleManagerTrait;
    use CsrfTokenManagerTrait;

    public const PARAMETER_PASSWORD = 'password';

    protected ?AuthenticationService $authenticationService = null;
    protected ?LoginService $loginService = null;

    /**
     * @return AuthenticationService
     */
    public function getAuthenticationService(): AuthenticationService
    {
        if (!$this->authenticationService instanceof AuthenticationService) {
            $this->authenticationService = new AuthenticationService();
        }
        return $this->authenticationService;
    }

    /**
     * @return LoginService
     */
    public function getLoginService(): LoginService
    {
        if (!$this->loginService instanceof LoginService) {
            $this->loginService = new LoginService();
        }
        return $this->loginService;
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     * @throws RequestForwardableException
     */
    public function handle(Request $request)
    {
        if (!$this->getUserRoleManager()->getDataGroupPermissions('auth_admin_verify')->canRead()) {
            throw new RequestForwardableException(ForbiddenController::class . '::handle');
        }

        $username = $this->getUserRoleManager()->getUser()->getUserName();
        $password = $request->request->get(self::PARAMETER_PASSWORD, '');
        $credentials = new UserCredential($username, $password);

        try {
            $token = $request->request->get('_token');
            if (!$this->getCsrfTokenManager()->isValid('administrator-access', $token)) {
                throw AuthenticationException::invalidCsrfToken();
            }
            $success = $this->getAuthenticationService()->setCredentials($credentials);
            if (!$success) {
                throw AuthenticationException::invalidCredentials();
            }
            $this->getAuthUser()->setHasAdminAccess(true);
            $this->getLoginService()->addLogin($credentials);

            $forwardUrl = $this->getAuthUser()->getAttribute(AuthUser::ADMIN_ACCESS_FORWARD_URL);

            $this->getAuthUser()->removeAttribute(AuthUser::ADMIN_ACCESS_FORWARD_URL);
            $this->getAuthUser()->removeAttribute(AuthUser::ADMIN_ACCESS_BACK_URL);

            return $this->redirect($forwardUrl);
        } catch (AuthenticationException $e) {
            $this->getAuthUser()->addFlash(AuthUser::FLASH_VERIFY_ERROR, $e->normalize());
            return $this->forward(AdministratorAccessController::class . '::handle');
        }
    }
}
