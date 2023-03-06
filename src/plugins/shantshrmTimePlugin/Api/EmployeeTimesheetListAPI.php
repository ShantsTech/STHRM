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

namespace ShantsHRM\Time\Api;

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
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\WorkflowStateMachine;
use ShantsHRM\Time\Api\Model\EmployeeTimesheetModel;
use ShantsHRM\Time\Dto\EmployeeTimesheetListSearchFilterParams;
use ShantsHRM\Time\Traits\Service\TimesheetServiceTrait;

class EmployeeTimesheetListAPI extends Endpoint implements CollectionEndpoint
{
    use TimesheetServiceTrait;
    use UserRoleManagerTrait;

    public const FILTER_EMP_NUMBER = 'empNumber';

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $employeeTimesheetListSearchParamHolder = new EmployeeTimesheetListSearchFilterParams();
        $this->setSortingAndPaginationParams($employeeTimesheetListSearchParamHolder);
        $empNumber = $this->getRequestParams()->getIntOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::FILTER_EMP_NUMBER
        );

        if (!is_null($empNumber)) {
            $employeeTimesheetListSearchParamHolder->setEmployeeNumbers([$empNumber]);
        } else {
            $accessibleEmpNumbers = $this->getUserRoleManager()->getAccessibleEntityIds(Employee::class);
            $employeeTimesheetListSearchParamHolder->setEmployeeNumbers($accessibleEmpNumbers);
        }

        $actions = [WorkflowStateMachine::TIMESHEET_ACTION_APPROVE, WorkflowStateMachine::TIMESHEET_ACTION_REJECT];
        $actionableStatesList = $this->getUserRoleManager()
            ->getActionableStates(WorkflowStateMachine::FLOW_TIME_TIMESHEET, $actions);
        $employeeTimesheetListSearchParamHolder->setActionableStatesList($actionableStatesList);

        $employeeTimesheetList = $this->getTimesheetService()
            ->getTimesheetDao()
            ->getEmployeeTimesheetList($employeeTimesheetListSearchParamHolder);

        $employeeTimesheetListCount = $this->getTimesheetService()
            ->getTimesheetDao()
            ->getEmployeeTimesheetListCount($employeeTimesheetListSearchParamHolder);

        return new EndpointCollectionResult(
            EmployeeTimesheetModel::class,
            $employeeTimesheetList,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $employeeTimesheetListCount])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    CommonParams::PARAMETER_EMP_NUMBER,
                    new Rule(Rules::POSITIVE),
                    new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
                )
            ),
            ...$this->getSortingAndPaginationParamsRules(EmployeeTimesheetListSearchFilterParams::ALLOWED_SORT_FIELDS)
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
