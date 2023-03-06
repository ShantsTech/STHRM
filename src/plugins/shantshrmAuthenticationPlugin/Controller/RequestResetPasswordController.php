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

use ShantsHRM\Authentication\Exception\AuthenticationException;
use ShantsHRM\Authentication\Service\ResetPasswordService;
use ShantsHRM\Authentication\Traits\CsrfTokenManagerTrait;
use ShantsHRM\Core\Controller\AbstractController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Entity\User;
use ShantsHRM\Framework\Http\RedirectResponse;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;

class RequestResetPasswordController extends AbstractController implements PublicControllerInterface
{
    use CsrfTokenManagerTrait;

    protected ?ResetPasswordService $resetPasswordService = null;

    /**
     * @return ResetPasswordService
     */
    public function getResetPasswordService(): ResetPasswordService
    {
        if (!$this->resetPasswordService instanceof ResetPasswordService) {
            $this->resetPasswordService = new ResetPasswordService();
        }
        return $this->resetPasswordService;
    }

    /**
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function handle(Request $request): RedirectResponse
    {
        $token = $request->request->get('_token');
        if (!$this->getCsrfTokenManager()->isValid('request-reset-password', $token)) {
            throw AuthenticationException::invalidCsrfToken();
        }
        $username = $request->request->get('username');
        if (($user = $this->getResetPasswordService()->searchForUserRecord($username)) instanceof User) {
            $this->getResetPasswordService()->logPasswordResetRequest($user);
        }
        return $this->redirect('auth/sendPasswordReset');
    }
}
