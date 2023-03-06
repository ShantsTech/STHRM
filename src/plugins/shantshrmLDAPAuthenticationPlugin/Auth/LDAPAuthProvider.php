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

namespace ShantsHRM\LDAP\Auth;

use ShantsHRM\Authentication\Auth\AbstractAuthProvider;
use ShantsHRM\Authentication\Dto\AuthParamsInterface;
use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\Authentication\Dto\UserCredentialInterface;
use ShantsHRM\Authentication\Exception\AuthenticationException;
use ShantsHRM\Authentication\Service\AuthenticationService;
use ShantsHRM\Core\Traits\LoggerTrait;
use ShantsHRM\Entity\UserAuthProvider;
use ShantsHRM\LDAP\Service\LDAPService;
use ShantsHRM\LDAP\Service\LDAPSyncService;
use Throwable;

class LDAPAuthProvider extends AbstractAuthProvider
{
    use LoggerTrait;

    private LDAPService $ldapService;
    private LDAPSyncService $ldapSyncService;
    private AuthenticationService $authenticationService;

    /**
     * @return LDAPService
     */
    private function getLDAPService(): LDAPService
    {
        return $this->ldapService ??= new LDAPService();
    }

    /**
     * @return LDAPSyncService
     */
    private function getLDAPSyncService(): LDAPSyncService
    {
        return $this->ldapSyncService ??= new LDAPSyncService();
    }

    /**
     * @return AuthenticationService
     */
    private function getAuthenticationService(): AuthenticationService
    {
        return $this->authenticationService ??= new AuthenticationService();
    }

    /**
     * @inheritDoc
     */
    public function authenticate(AuthParamsInterface $authParams): bool
    {
        if (!$authParams->getCredential() instanceof UserCredentialInterface) {
            return false;
        }
        $credential = $authParams->getCredential();
        $user = $this->getLDAPSyncService()
            ->getLDAPDao()
            ->getNonLocalUserByUserName($credential->getUsername(), false);
        if ($user === null) {
            return false;
        }
        $ldapAuthProvider = $this->getLDAPSyncService()->filterLDAPAuthProvider($user->getAuthProviders());
        if (!$ldapAuthProvider instanceof UserAuthProvider) {
            return false;
        }

        $ldapCredential = new UserCredential($ldapAuthProvider->getLDAPUserDN(), $credential->getPassword());
        try {
            $this->getLDAPService()->bind($ldapCredential);
            return $this->getAuthenticationService()->setCredentialsForUser($user);
        } catch (AuthenticationException $e) {
            throw $e;
        } catch (Throwable $e) {
            // Ignore logging stack trace to avoid dump bind passwords in the log file
            $this->getLogger()->error($e->getMessage());
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function getPriority(): int
    {
        return 5000;
    }
}
