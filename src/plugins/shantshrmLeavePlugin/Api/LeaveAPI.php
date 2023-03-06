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

namespace ShantsHRM\Leave\Api;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CrudEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\Leave;
use ShantsHRM\Entity\LeaveRequest;
use ShantsHRM\Leave\Api\Model\LeaveDetailedModel;
use ShantsHRM\Leave\Api\Model\LeaveModel;
use ShantsHRM\Leave\Api\Traits\LeavePermissionTrait;
use ShantsHRM\Leave\Api\Traits\LeaveRequestParamHelperTrait;
use ShantsHRM\Leave\Api\Traits\LeaveRequestPermissionTrait;
use ShantsHRM\Leave\Dto\LeaveRequest\DetailedLeave;
use ShantsHRM\Leave\Dto\LeaveSearchFilterParams;
use ShantsHRM\Leave\Traits\Service\LeaveRequestServiceTrait;
use ShantsHRM\Pim\Api\Model\EmployeeModel;

class LeaveAPI extends Endpoint implements CrudEndpoint
{
    use LeaveRequestParamHelperTrait;
    use LeaveRequestServiceTrait;
    use UserRoleManagerTrait;
    use NormalizerServiceTrait;
    use LeaveRequestPermissionTrait;
    use LeavePermissionTrait;
    use DateTimeHelperTrait;

    public const FILTER_LEAVE_REQUEST_ID = 'leaveRequestId';

    public const PARAMETER_LEAVE_ID = 'leaveId';
    public const PARAMETER_ACTION = 'action';

    public const META_PARAMETER_EMPLOYEE = 'employee';
    public const META_PARAMETER_LEAVE_START_DATE = 'startDate';
    public const META_PARAMETER_LEAVE_END_DATE = 'endDate';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $leaveSearchFilterParams = $this->getLeaveSearchFilterParams();

        /** @var LeaveRequest|null $leaveRequest */
        $leaveRequest = $this->getLeaveRequestService()
            ->getLeaveRequestDao()
            ->getLeaveRequestById($leaveSearchFilterParams->getLeaveRequestId());

        $this->throwRecordNotFoundExceptionIfNotExist($leaveRequest, LeaveRequest::class);
        $this->checkLeaveRequestAccessible($leaveRequest);

        $leaves = $this->getLeaveRequestService()
            ->getLeaveRequestDao()
            ->getLeaves($leaveSearchFilterParams);
        $total = $this->getLeaveRequestService()
            ->getLeaveRequestDao()
            ->getLeavesCount($leaveSearchFilterParams);
        $allLeavesOfLeaveRequest = $this->getLeaveRequestService()->getLeaveRequestDao()
            ->getLeavesByLeaveRequestIds([$leaveRequest->getId()]);
        $detailedLeaves = $this->getLeaveRequestService()->getDetailedLeaves($leaves, $allLeavesOfLeaveRequest);

        $employee = $leaveRequest->getEmployee();

        return new EndpointCollectionResult(
            LeaveDetailedModel::class,
            $detailedLeaves,
            new ParameterBag(
                [
                    CommonParams::PARAMETER_TOTAL => $total,
                    self::META_PARAMETER_EMPLOYEE => $this->getNormalizedEmployee($employee),
                    self::META_PARAMETER_LEAVE_START_DATE => $this->getDateTimeHelper()->formatDateTimeToYmd($allLeavesOfLeaveRequest[0]->getDate()),
                    self::META_PARAMETER_LEAVE_END_DATE => $this->getDateTimeHelper()->formatDateTimeToYmd(end($allLeavesOfLeaveRequest)->getDate()),
                ]
            )
        );
    }

    /**
     * @param Employee $employee
     * @return array
     */
    protected function getNormalizedEmployee(Employee $employee): array
    {
        return $this->getNormalizerService()->normalize(
            EmployeeModel::class,
            $employee
        );
    }

    /**
     * @return LeaveSearchFilterParams
     */
    protected function getLeaveSearchFilterParams(): LeaveSearchFilterParams
    {
        $leaveRequestId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::FILTER_LEAVE_REQUEST_ID
        );

        $leaveRequestSearchFilterParams = new LeaveSearchFilterParams();
        $leaveRequestSearchFilterParams->setLeaveRequestId($leaveRequestId);
        $this->setSortingAndPaginationParams($leaveRequestSearchFilterParams);

        return $leaveRequestSearchFilterParams;
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(self::FILTER_LEAVE_REQUEST_ID, new Rule(Rules::POSITIVE)),
            ...$this->getSortingAndPaginationParamsRules()
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
    public function update(): EndpointResult
    {
        $leaveId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_LEAVE_ID
        );
        $leave = $this->getLeaveRequestService()->getLeaveRequestDao()->getLeaveById($leaveId);
        $this->throwRecordNotFoundExceptionIfNotExist($leave, Leave::class);
        $this->checkLeaveAccessible($leave);

        $detailedLeave = new DetailedLeave($leave);

        $action = $this->getRequestParams()->getString(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_ACTION);
        if (!$detailedLeave->isActionAllowed($action)) {
            throw $this->getBadRequestException('Performed action not allowed');
        }

        $workflow = $detailedLeave->getWorkflowForAction($action);
        $this->getLeaveRequestService()->changeLeaveStatus($leave, $workflow);

        return new EndpointResourceResult(LeaveModel::class, $leave);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(self::PARAMETER_LEAVE_ID, new Rule(Rules::POSITIVE)),
            new ParamRule(self::PARAMETER_ACTION, new Rule(Rules::STRING_TYPE)),
        );
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
