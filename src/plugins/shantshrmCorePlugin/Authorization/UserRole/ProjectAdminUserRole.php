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

namespace ShantsHRM\Core\Authorization\UserRole;

use ShantsHRM\Core\Authorization\Manager\BasicUserRoleManager;
use ShantsHRM\Entity\Customer;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\Project;
use ShantsHRM\Time\Traits\Service\CustomerServiceTrait;
use ShantsHRM\Time\Traits\Service\ProjectServiceTrait;

class ProjectAdminUserRole extends AbstractUserRole
{
    use ProjectServiceTrait;
    use CustomerServiceTrait;

    public const INCLUDE_EMPLOYEE = 'include_employee';

    /**
     * @inheritDoc
     */
    protected function getAccessibleIdsForEntity(string $entityType, array $requiredPermissions = []): array
    {
        switch ($entityType) {
            case Employee::class:
                return $this->getAccessibleEmployeeIds($requiredPermissions);
            case Project::class:
                return $this->getAccessibleProjectIds($requiredPermissions);
            case Customer::class:
                return $this->getAccessibleCustomerIds($requiredPermissions);
            default:
                return [];
        }
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleEmployeeIds(array $requiredPermissions = []): array
    {
        if (isset($requiredPermissions[BasicUserRoleManager::PERMISSION_TYPE_USER_ROLE_SPECIFIC])) {
            $permissions = $requiredPermissions[BasicUserRoleManager::PERMISSION_TYPE_USER_ROLE_SPECIFIC];
            if (is_array($permissions) &&
                isset($permissions[self::INCLUDE_EMPLOYEE]) &&
                $permissions[self::INCLUDE_EMPLOYEE] === true) {
                return $this->getProjectService()
                    ->getProjectDao()
                    ->getAccessibleEmpNumbersForProjectAdmin($this->getEmployeeNumber());
            }
        }
        return [];
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleProjectIds(array $requiredPermissions = []): array
    {
        return $this->getProjectService()
            ->getProjectDao()
            ->getProjectIdListForProjectAdmin($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleCustomerIds(array $requiredPermissions): array
    {
        return $this->getCustomerService()
            ->getCustomerDao()
            ->getCustomerIdListForProjectAdmin($this->getEmployeeNumber());
    }
}
