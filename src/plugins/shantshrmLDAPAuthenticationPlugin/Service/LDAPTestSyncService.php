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

namespace ShantsHRM\LDAP\Service;

use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\LDAP\Dto\LDAPSetting;

class LDAPTestSyncService extends LDAPSyncService
{
    /**
     * @param LDAPSetting $ldapSetting
     */
    public function __construct(LDAPSetting $ldapSetting)
    {
        $this->ldapSetting = $ldapSetting;
    }

    /**
     * @inheritDoc
     */
    protected function getLDAPService(): LDAPService
    {
        if (!$this->ldapService instanceof LDAPTestService) {
            $this->ldapService = new LDAPTestService($this->getLDAPSetting());
            $bindCredentials = new UserCredential();
            if (!$this->getLDAPSetting()->isBindAnonymously()) {
                $bindCredentials->setUsername($this->getLDAPSetting()->getBindUserDN());
                $bindCredentials->setPassword($this->getLDAPSetting()->getBindUserPassword());
            }
            $this->ldapService->bind($bindCredentials);
        }
        return $this->ldapService;
    }

    /**
     * @inheritDoc
     */
    protected function getLDAPSetting(): LDAPSetting
    {
        return $this->ldapSetting;
    }
}
