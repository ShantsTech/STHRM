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

namespace ShantsHRM\Authentication\Service;

use ShantsHRM\Admin\Traits\Service\UserServiceTrait;
use ShantsHRM\Authentication\Dao\LoginLogDao;
use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\Entity\LoginLog;

class LoginService
{
    use UserServiceTrait;

    /**
     * @var LoginLogDao|null
     */
    private ?LoginLogDao $loginLogDao = null;

    /**
     * @return LoginLogDao|null
     */
    public function getLoginLogDao(): ?LoginLogDao
    {
        if (!($this->loginLogDao instanceof LoginLogDao)) {
            $this->loginLogDao = new LoginLogDao();
        }
        return $this->loginLogDao;
    }

    /**
     * @param UserCredential $credentials
     * @return LoginLog
     */
    public function addLogin(UserCredential $credentials): LoginLog
    {
        $user = $this->getUserService()
            ->geUserDao()
            ->getUserByUserName($credentials->getUsername());
        $loginLog = new LoginLog();
        $loginLog->setUserId($user->getId());
        $loginLog->setUserName($user->getUserName());
        $loginLog->setUserRoleName($user->getUserRole()->getName());
        $loginLog->setUserRolePredefined($user->getUserRole()->isPredefined());
        return $this->getLoginLogDao()->saveLoginLog($loginLog);
    }
}
