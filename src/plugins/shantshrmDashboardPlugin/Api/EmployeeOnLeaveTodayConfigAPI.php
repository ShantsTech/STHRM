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

namespace ShantsHRM\Dashboard\Api;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;

class EmployeeOnLeaveTodayConfigAPI extends Endpoint implements ResourceEndpoint
{
    use ConfigServiceTrait;

    public const PARAMETER_SHOW_ONLY_ACCESSIBLE_EMPLOYEES_ON_LEAVE_TODAY = 'showOnlyAccessibleEmployeesOnLeaveToday';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $showOnlyAccessibleEmployeesOnLeaveToday = $this->getConfigService()
            ->getDashboardEmployeesOnLeaveTodayShowOnlyAccessibleConfig();
        return new EndpointResourceResult(
            ArrayModel::class,
            [self::PARAMETER_SHOW_ONLY_ACCESSIBLE_EMPLOYEES_ON_LEAVE_TODAY => $showOnlyAccessibleEmployeesOnLeaveToday]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        $paramsRules = new ParamRuleCollection();
        $paramsRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
        return $paramsRules;
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        $showOnlyAccessibleEmployeesOnLeaveToday = $this->getRequestParams()->getBoolean(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_SHOW_ONLY_ACCESSIBLE_EMPLOYEES_ON_LEAVE_TODAY
        );

        $this->getConfigService()->setDashboardEmployeesOnLeaveTodayShowOnlyAccessibleConfig(
            $showOnlyAccessibleEmployeesOnLeaveToday
        );

        return new EndpointResourceResult(
            ArrayModel::class,
            [self::PARAMETER_SHOW_ONLY_ACCESSIBLE_EMPLOYEES_ON_LEAVE_TODAY => $showOnlyAccessibleEmployeesOnLeaveToday]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        $paramsRules = new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_SHOW_ONLY_ACCESSIBLE_EMPLOYEES_ON_LEAVE_TODAY,
                new Rule(Rules::BOOL_TYPE)
            )
        );
        $paramsRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
        return $paramsRules;
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
