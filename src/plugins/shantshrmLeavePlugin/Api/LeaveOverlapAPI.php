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

use DateTime;
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
use ShantsHRM\Leave\Api\Model\OverlapLeaveModel;
use ShantsHRM\Leave\Api\Traits\LeaveRequestParamHelperTrait;
use ShantsHRM\Leave\Dto\LeaveOverlapParams;
use ShantsHRM\Leave\Service\LeaveApplicationService;
use ShantsHRM\Leave\Traits\Service\LeaveRequestServiceTrait;

class LeaveOverlapAPI extends Endpoint implements CollectionEndpoint
{
    use LeaveRequestParamHelperTrait;
    use AuthUserTrait;
    use LeaveRequestServiceTrait;

    public const META_PARAMETER_IS_WORK_SHIFT_LENGTH_EXCEEDED = 'isWorkShiftLengthExceeded';

    private ?LeaveApplicationService $leaveApplicationService = null;

    /**
     * @return LeaveApplicationService
     */
    protected function getLeaveApplicationService(): LeaveApplicationService
    {
        if (!$this->leaveApplicationService instanceof LeaveApplicationService) {
            $this->leaveApplicationService = new LeaveApplicationService();
        }
        return $this->leaveApplicationService;
    }

    /**
     * To reuse LeaveRequestParamHelperTrait::class but with LeaveOverlapParams::class
     * @inheritDoc
     */
    protected function getLeaveTypeIdParam(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    protected function getFromDateParam(): ?DateTime
    {
        return $this->getRequestParams()->getDateTimeOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            LeaveCommonParams::PARAMETER_FROM_DATE
        );
    }

    /**
     * @inheritDoc
     */
    protected function getToDateParam(): ?DateTime
    {
        return $this->getRequestParams()->getDateTimeOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            LeaveCommonParams::PARAMETER_TO_DATE
        );
    }

    /**
     * @inheritDoc
     */
    protected function getDurationParam(string $key, ?array $default = null): ?array
    {
        return $this->getRequestParams()->getArrayOrNull(RequestParams::PARAM_TYPE_QUERY, $key, $default);
    }

    /**
     * @inheritDoc
     */
    protected function getPartialOptionParam(): ?string
    {
        return $this->getRequestParams()->getStringOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            LeaveCommonParams::PARAMETER_PARTIAL_OPTION
        );
    }

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $this->getLeaveRequestService()->getLeaveRequestDao()->markApprovedLeaveAsTaken();
        $empNumber = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_QUERY,
            CommonParams::PARAMETER_EMP_NUMBER,
            $this->getAuthUser()->getEmpNumber()
        );
        $leaveRequestParams = $this->getLeaveRequestParams($empNumber, LeaveOverlapParams::class);

        $overlapLeaves = [];
        $hasOverlapLeaves = $this->getLeaveApplicationService()->hasOverlapLeaves($leaveRequestParams);
        if ($hasOverlapLeaves) {
            $overlapLeaves = $this->getLeaveApplicationService()->getOverlapLeaves($leaveRequestParams);
        }

        $isWorkShiftLengthExceeded = false;
        if (!$hasOverlapLeaves) {
            $isWorkShiftLengthExceeded = $this->getLeaveApplicationService()
                ->isWorkShiftLengthExceeded($leaveRequestParams);
            if ($isWorkShiftLengthExceeded) {
                $overlapLeaves = $this->getLeaveApplicationService()
                    ->getWorkShiftLengthExceedOverlapLeaves($leaveRequestParams);
            }
        }
        return new EndpointCollectionResult(
            OverlapLeaveModel::class,
            $overlapLeaves,
            new ParameterBag(
                [
                    CommonParams::PARAMETER_EMP_NUMBER => $empNumber,
                    self::META_PARAMETER_IS_WORK_SHIFT_LENGTH_EXCEEDED => $isWorkShiftLengthExceeded,
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        $paramRules = $this->getCommonParamRuleCollection();
        $paramRules->removeParamValidation(LeaveCommonParams::PARAMETER_LEAVE_TYPE_ID);
        $paramRules->removeParamValidation(LeaveCommonParams::PARAMETER_COMMENT);
        $paramRules->addParamValidation(
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(CommonParams::PARAMETER_EMP_NUMBER, new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS))
            )
        );
        return $paramRules;
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
