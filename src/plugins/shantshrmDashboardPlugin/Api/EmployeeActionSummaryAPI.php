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
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Dashboard\Dto\ActionSummary\PendingAction;
use ShantsHRM\Dashboard\Dto\ActionSummary\PendingAppraisalReviewSummary;
use ShantsHRM\Dashboard\Dto\ActionSummary\PendingLeaveRequestSummary;
use ShantsHRM\Dashboard\Dto\ActionSummary\PendingSelfReviewSummary;
use ShantsHRM\Dashboard\Dto\ActionSummary\PendingTimesheetSummary;
use ShantsHRM\Dashboard\Dto\ActionSummary\ScheduledInterviewSummary;
use ShantsHRM\Dashboard\Traits\Service\EmployeeActionSummaryServiceTrait;
use ShantsHRM\Dashboard\Traits\Service\ModuleServiceTrait;
use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\Employee;

class EmployeeActionSummaryAPI extends Endpoint implements ResourceEndpoint
{
    use UserRoleManagerTrait;
    use AuthUserTrait;
    use EmployeeActionSummaryServiceTrait;
    use ModuleServiceTrait;

    public const LEAVE_MODULE = 'leave';
    public const TIME_MODULE = 'time';
    public const PERFORMANCE_MODULE = 'performance';
    public const RECRUITMENT_MODULE = 'recruitment';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $empNumber = $this->getAuthUser()->getEmpNumber();

        $accessibleEmpNumbers = $this->getUserRoleManager()->getAccessibleEntityIds(Employee::class);
        $authUserIndex = array_search($empNumber, $accessibleEmpNumbers);
        if ($authUserIndex !== false) {
            unset($accessibleEmpNumbers[$authUserIndex]);
        }

        $accessibleCandidateIds = $this->getUserRoleManager()->getAccessibleEntityIds(Candidate::class);

        $enabledModuleNames = $this->getModuleService()->getModuleDao()->getEnabledModuleNameList();

        $availableActionGroups = [];
        if (in_array(self::LEAVE_MODULE, $enabledModuleNames)) {
            $availableActionGroups[] = new PendingLeaveRequestSummary(array_values($accessibleEmpNumbers));
        }
        if ((in_array(self::TIME_MODULE, $enabledModuleNames))) {
            $availableActionGroups[] = new PendingTimesheetSummary(array_values($accessibleEmpNumbers));
        }
        if (in_array(self::PERFORMANCE_MODULE, $enabledModuleNames)) {
            $availableActionGroups[] = new PendingAppraisalReviewSummary($empNumber);
            $availableActionGroups[] = new PendingSelfReviewSummary($empNumber);
        }
        if (in_array(self::RECRUITMENT_MODULE, $enabledModuleNames)) {
            $availableActionGroups[] = new ScheduledInterviewSummary($accessibleCandidateIds);
        }

        $actionsSummary = [];
        foreach ($availableActionGroups as $actionGroup) {
            $pendingAction = new PendingAction($actionGroup);
            $actionSummary = $pendingAction->generateActionSummary();
            if (!is_null($actionSummary)) {
                $actionsSummary[] = $actionSummary;
            }
        }

        return new EndpointResourceResult(ArrayModel::class, $actionsSummary);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        $paramRules = new ParamRuleCollection();
        $paramRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
        return $paramRules;
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
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
