<?php
/*
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

namespace ShantsHRM\Core\Authorization\Dao;

use Exception;
use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Entity\ScreenPermission;
use ShantsHRM\Entity\UserRole;

class ScreenPermissionDao extends BaseDao
{
    /**
     * @param string $module Module Name
     * @param string $actionUrl Action
     * @param string[]|UserRole[] $roles Array of UserRole objects or user role names
     * @return ScreenPermission[]
     * @throws DaoException
     */
    public function getScreenPermissions(string $module, string $actionUrl, array $roles): array
    {
        try {
            $roleNames = [];

            foreach ($roles as $role) {
                if ($role instanceof UserRole) {
                    $roleNames[] = $role->getName();
                } elseif (is_string($role)) {
                    $roleNames[] = $role;
                }
            }

            $q = $this->createQueryBuilder(ScreenPermission::class, 'sp');
            $q->leftJoin('sp.userRole', 'ur');
            $q->leftJoin('sp.screen', 's');
            $q->leftJoin('s.module', 'm');
            $q->andWhere('m.name = :moduleName')
                ->setParameter('moduleName', $module);
            $q->andWhere('s.actionUrl = :actionUrl')
                ->setParameter('actionUrl', $actionUrl);
            $q->andWhere($q->expr()->in('ur.name', ':userRoleNames'))
                ->setParameter('userRoleNames', $roleNames);

            return $q->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
