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

namespace ShantsHRM\Core\Authorization\Service;

use Exception;
use ShantsHRM\Admin\Traits\Service\UserServiceTrait;
use ShantsHRM\Core\Authorization\Manager\AbstractUserRoleManager;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Core\Exception\ServiceException;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\ClassHelperTrait;
use ShantsHRM\Core\Traits\LoggerTrait;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Entity\User;

class UserRoleManagerService
{
    use ClassHelperTrait;
    use ConfigServiceTrait;
    use AuthUserTrait;
    use LoggerTrait;
    use UserServiceTrait;

    public const KEY_USER_ROLE_MANAGER_CLASS = 'authorize_user_role_manager_class';

    /**
     * @return string|null
     * @throws DaoException
     */
    public function getUserRoleManagerClassName(): ?string
    {
        return $this->getConfigService()->getConfigDao()->getValue(self::KEY_USER_ROLE_MANAGER_CLASS);
    }

    /**
     * @return AbstractUserRoleManager|null
     * @throws DaoException
     * @throws ServiceException
     */
    public function getUserRoleManager(): ?AbstractUserRoleManager
    {
        $class = $this->getUserRoleManagerClassName();
        $manager = null;

        $fallbackNamespace = 'ShantsHRM\\Core\\Authorization\\Manager\\';
        if ($this->getClassHelper()->classExists($class, $fallbackNamespace)) {
            try {
                $class = $this->getClassHelper()->getClass($class, $fallbackNamespace);
                $manager = new $class();
            } catch (Exception $e) {
                throw new ServiceException('Exception when initializing user role manager:' . $e->getMessage());
            }
        } else {
            throw new ServiceException(sprintf('User Role Manager class %s not found.', $class));
        }

        if (!$manager instanceof AbstractUserRoleManager) {
            throw new ServiceException(
                sprintf('User Role Manager class %s is not a subclass of %s', $class, AbstractUserRoleManager::class)
            );
        }

        // Set System User object in manager
        $userId = $this->getAuthUser()->getUserId();
        if (is_null($userId)) {
            throw new ServiceException('No logged in user found.');
        }
        $systemUser = $this->getUserService()->getSystemUser($userId);

        if ($systemUser instanceof User) {
            $manager->setUser($systemUser);
        } else {
            $this->getLogger()->info('No logged in system user when creating UserRoleManager');
        }

        return $manager;
    }
}
