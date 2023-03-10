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
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Entity\LeaveRequest;
use ShantsHRM\Leave\Api\Model\LeaveRequestDetailedModel;
use ShantsHRM\Leave\Dto\LeaveRequest\DetailedLeaveRequest;
use ShantsHRM\Leave\Dto\LeaveRequestSearchFilterParams;
use ShantsHRM\Leave\Exception\LeaveAllocationServiceException;
use ShantsHRM\Leave\Service\LeaveApplicationService;
use ShantsHRM\Leave\Traits\Service\LeaveRequestServiceTrait;

class MyLeaveRequestAPI extends EmployeeLeaveRequestAPI
{
    use AuthUserTrait;
    use LeaveRequestServiceTrait;

    protected ?LeaveApplicationService $leaveApplicationService = null;

    /**
     * @return LeaveApplicationService
     */
    public function getLeaveApplicationService(): LeaveApplicationService
    {
        if (!$this->leaveApplicationService instanceof LeaveApplicationService) {
            $this->leaveApplicationService = new LeaveApplicationService();
        }
        return $this->leaveApplicationService;
    }

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
     * @OA\Get(
     *     path="/api/v2/leave/leave-requests",
     *     tags={"Leave/My Leave"},
     *     @OA\Parameter(
     *         name="leaveTypeId",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="fromDate",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="toDate",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="includeEmployees",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum={"onlyCurrent", "onlyPast", "currentAndPast"})
     *     ),
     *     @OA\Parameter(
     *         name="statuses",
     *         in="query",
     *         required=false,
     *         description="-1 => rejected,
 *                           0 => cancelled,
 *                           1 => pending ,
 *                           2 => approved,
 *                           3 => taken,
 *                           4 => weekend,
 *                           5 => holiday",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="sortField",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum=LeaveRequestSearchFilterParams::ALLOWED_SORT_FIELDS)
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Parameter(ref="#/components/parameters/offset"),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Leave-LeaveRequestDetailedModel"
     *             ),
     *             @OA\Property(property="meta",
     *                 type="object",
     *                 @OA\Property(property="empNumber", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     * )
     *
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $empNumber = $this->getAuthUser()->getEmpNumber();
        $leaveRequestSearchFilterParams = $this->getLeaveRequestSearchFilterParams($empNumber);
        $leaveRequests = $this->getLeaveRequestService()
            ->getLeaveRequestDao()
            ->getLeaveRequests($leaveRequestSearchFilterParams);
        $total = $this->getLeaveRequestService()
            ->getLeaveRequestDao()
            ->getLeaveRequestsCount($leaveRequestSearchFilterParams);
        $detailedLeaveRequests = $this->getLeaveRequestService()->getDetailedLeaveRequests($leaveRequests);

        return new EndpointCollectionResult(
            LeaveRequestDetailedModel::class,
            $detailedLeaveRequests,
            new ParameterBag(
                [
                    CommonParams::PARAMETER_EMP_NUMBER => $empNumber,
                    CommonParams::PARAMETER_TOTAL => $total
                ]
            )
        );
    }

    /**
     * @return string
     */
    protected function getDefaultIncludeEmployees(): string
    {
        return LeaveRequestSearchFilterParams::INCLUDE_EMPLOYEES_CURRENT_AND_PAST;
    }

    /**
     * @return string[]
     */
    protected function getDefaultStatuses(): array
    {
        return LeaveRequestSearchFilterParams::LEAVE_STATUSES;
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return $this->getCommonFilterParamRuleCollection();
    }

    /**
     * @OA\Post(
     *     path="/api/v2/leave/leave-requests",
     *     tags={"Leave/My Leave"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="comment", type="string"),
     *             @OA\Property(
     *                 property="duration",
     *                 type="object",
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     example="full_day, half_day_afternoon,half_day_morning,specify_time"
     *                 ),
     *                 @OA\Property(
     *                     property="fromTime",
     *                     type="number",
     *                     example="12:00",
     *                     description="used when duration type = specify_time "
     *                 ),
     *                 @OA\Property(
     *                     property="toTime",
     *                     type="number",
     *                     example="17:00",
     *                     description="used when duration type = specify_time "
     *                 ),
     *                 required={"type"}
     *             ),
     *             @OA\Property(
     *                 property="endDuration",
     *                 type="object",
     *                 description="Used when there are partial days at both the beginning and end",
     *                 @OA\Property(
     *                     property="type",
     *                     type="string",
     *                     enum={"full_day", "half_day_afternoon", "half_day_morning", "specify_time"},
     *                 ),
     *                 @OA\Property(
     *                     property="fromTime",
     *                     type="number", example="12:00",
     *                     description="used when endDuration type = specify_time "
     *                 ),
     *                 @OA\Property(
     *                     property="toTime",
     *                     type="number",
     *                     example="17:00",
     *                     description="used when endDuration type = specify_time "
     *                 ),
     *                 required={"type"}
     *             ),
     *             @OA\Property(property="partialOption", type="string", example="start"),
     *             @OA\Property(property="fromDate", type="number"),
     *             @OA\Property(property="toDate", type="number"),
     *             @OA\Property(property="leaveTypeId", type="integer"),
     *             required={"duration", "fromDate", "toDate", "leaveTypeId"},
     *             example="{
     *                  'leaveTypeId':3,
 *                      'fromDate':'2022-09-07',
     *                  'toDate':'2022-09-08',
     *                  'comment':null,
     *                  'duration':{'type':'specify_time','fromTime':'09:00','toTime':'17:00'},
     *                  'partialOption':'start_end',
     *                  'endDuration':{'type':'specify_time','fromTime':'09:00','toTime':'17:00'}
     *             }"
     *         ),
     *
     *     ),
     *     @OA\Response(response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Leave-LeaveTypeModel"
     *             ),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     * )
     *
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        $empNumber = $this->getAuthUser()->getEmpNumber();
        $leaveRequestParams = $this->getLeaveRequestParams($empNumber);
        try {
            $leaveRequest = $this->getLeaveApplicationService()->applyLeave($leaveRequestParams);
            $model = $this->getRequestParams()->getString(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_MODEL,
                self::MODEL_DEFAULT
            );
            if ($model === self::MODEL_DETAILED) {
                $data = new DetailedLeaveRequest($leaveRequest);
                $data->fetchLeaves();
            } else {
                $data = $leaveRequest;
            }
            return new EndpointResourceResult(
                self::MODEL_MAP[$model],
                $data,
                new ParameterBag([CommonParams::PARAMETER_EMP_NUMBER => $empNumber])
            );
        } catch (LeaveAllocationServiceException $e) {
            throw $this->getBadRequestException($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        $paramRules = $this->getCommonParamRuleCollection();
        $paramRules->addParamValidation(
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(self::FILTER_MODEL, new Rule(Rules::IN, [array_keys(self::MODEL_MAP)])),
            )
        );
        return $paramRules;
    }

    /**
     * @inheritDoc
     */
    public function checkLeaveRequestAccessible(LeaveRequest $leaveRequest): void
    {
        $empNumber = $leaveRequest->getEmployee()->getEmpNumber();
        if (!$this->getUserRoleManagerHelper()->isSelfByEmpNumber($empNumber)) {
            throw $this->getForbiddenException();
        }
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
