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
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Dashboard\Api\Model\EmployeesOnLeaveListModel;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Leave\Traits\Service\LeaveConfigServiceTrait;
use ShantsHRM\Dashboard\Dto\EmployeeOnLeaveSearchFilterParams;
use ShantsHRM\Dashboard\Traits\Service\EmployeeOnLeaveServiceTrait;

class EmployeeOnLeaveAPI extends Endpoint implements CollectionEndpoint
{
    use DateTimeHelperTrait;
    use LeaveConfigServiceTrait;
    use EmployeeOnLeaveServiceTrait;
    use ConfigServiceTrait;
    use UserRoleManagerTrait;
    use AuthUserTrait;

    public const PARAMETER_DATE = 'date';
    public const META_PARAMETER_LEAVE_PERIOD_DEFINED =  'leavePeriodDefined';

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $employeeOnLeaveSearchFilterParams = new EmployeeOnLeaveSearchFilterParams();

        $this->setSortingAndPaginationParams($employeeOnLeaveSearchFilterParams);
        $date = $this->getRequestParams()->getDateTime(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_DATE,
            null,
            $this->getDateTimeHelper()->getNow()
        );

        $employeeOnLeaveSearchFilterParams->setDate($date);

        $showOnlyAccessibleEmployeesOnLeaveToday = $this->getConfigService()
            ->getDashboardEmployeesOnLeaveTodayShowOnlyAccessibleConfig();

        if ($showOnlyAccessibleEmployeesOnLeaveToday) {
            $accessibleEmpNumbers = $this->getUserRoleManager()->getAccessibleEntityIds(Employee::class);
            $employeeOnLeaveSearchFilterParams->setAccessibleEmpNumber([$this->getAuthUser()->getEmpNumber(),...$accessibleEmpNumbers]);
        }

        $leavePeriodDefined = $this->getLeaveConfigService()->isLeavePeriodDefined();

        $empLeaveList = $this->getEmployeeOnLeaveService()->getEmployeeOnLeaveDao()
            ->getEmployeeOnLeaveList($employeeOnLeaveSearchFilterParams);
        $employeeCount = $this->getEmployeeOnLeaveService()->getEmployeeOnLeaveDao()
            ->getEmployeeOnLeaveCount($employeeOnLeaveSearchFilterParams);

        return new EndpointCollectionResult(
            EmployeesOnLeaveListModel::class,
            [$empLeaveList],
            new ParameterBag([
                CommonParams::PARAMETER_TOTAL => $employeeCount,
                self::META_PARAMETER_LEAVE_PERIOD_DEFINED => $leavePeriodDefined,
            ])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_DATE,
                new Rule(Rules::API_DATE)
            ),
            ...$this->getSortingAndPaginationParamsRules(EmployeeOnLeaveSearchFilterParams::ALLOWED_SORT_FIELDS),
        );
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
