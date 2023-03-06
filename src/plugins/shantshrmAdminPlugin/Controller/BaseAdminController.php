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

namespace ShantsHRM\Admin\Controller;

use ShantsHRM\Core\Authorization\Controller\CapableViewController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Helper\VueControllerHelper;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Framework\Http\Request;

abstract class BaseAdminController extends AbstractVueController implements CapableViewController
{
    use UserRoleManagerTrait;

    /**
     * @return string[]
     */
    protected function getDataGroupsForCapabilityCheck(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     * @throws \ShantsHRM\Core\Exception\DaoException
     */
    public function isCapable(Request $request): bool
    {
        $permission = $this->getUserRoleManagerHelper()->getEntityIndependentDataGroupPermissions(
            $this->getDataGroupsForCapabilityCheck()
        );
        return $permission->canRead();
    }

    /**
     * Sets the data group permissions into the Vue context
     *
     * @param array $dataGroups
     */
    protected function setPermissionsForController(array $dataGroups)
    {
        $permissions = $this->getUserRoleManagerHelper()
                            ->geEntityIndependentDataGroupPermissionCollection($dataGroups);
        $this->getContext()->set(
            VueControllerHelper::PERMISSIONS,
            $permissions->toArray()
        );
    }
}
