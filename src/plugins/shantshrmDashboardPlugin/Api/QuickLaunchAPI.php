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

namespace ShantsHRM\Dashboard\Api;

use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Dashboard\Dao\QuickLaunchDao;

class QuickLaunchAPI extends Endpoint implements CollectionEndpoint
{
    use UserRoleManagerTrait;

    protected const QUICK_LAUNCH_ITEMS = [
        QuickLaunchDao::ASSIGN_LEAVE => false,
        QuickLaunchDao::LEAVE_LIST => false,
        QuickLaunchDao::APPLY_LEAVE => false,
        QuickLaunchDao::MY_LEAVE => false,
        QuickLaunchDao::EMPLOYEE_TIMESHEET => false,
        QuickLaunchDao::MY_TIMESHEET => false,
    ];

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        return new EndpointResourceResult(
            ArrayModel::class,
            $this->getActiveQuickLaunchItemArray()
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection();
    }

    private function getActiveQuickLaunchItemArray(): array
    {
        $activeItems = self::QUICK_LAUNCH_ITEMS;
        $accessibleItems = $this->getUserRoleManager()->getAccessibleQuickLaunchList();

        foreach ($accessibleItems as $item) {
            $activeItems[$item] = true;
        }

        return $activeItems;
    }

    /**
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
