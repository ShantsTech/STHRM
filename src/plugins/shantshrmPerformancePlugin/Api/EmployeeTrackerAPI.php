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

namespace ShantsHRM\Performance\Api;

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
use ShantsHRM\Core\Api\V2\Validator\Rules\InAccessibleEntityIdOption;
use ShantsHRM\Core\Authorization\Manager\BasicUserRoleManager;
use ShantsHRM\Core\Authorization\UserRole\ReviewerUserRole;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\PerformanceTracker;
use ShantsHRM\Performance\Api\Model\EmployeeTrackerModel;
use ShantsHRM\Performance\Api\Model\PerformanceTrackerModel;
use ShantsHRM\Performance\Dto\EmployeeTrackerSearchFilterParams;
use ShantsHRM\Performance\Traits\Service\PerformanceTrackerServiceTrait;

class EmployeeTrackerAPI extends Endpoint implements CrudEndpoint
{
    use UserRoleManagerTrait;
    use PerformanceTrackerServiceTrait;

    public const FILTER_INCLUDE_EMPLOYEES = 'includeEmployees';

    /**
     * @OA\Get(
     *     path="/api/v2/performance/employees/trackers",
     *     tags={"Performance/Employee Trackers"},
     *     @OA\Parameter(
     *         name="empNumber",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortField",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum=EmployeeTrackerSearchFilterParams::ALLOWED_SORT_FIELDS)
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
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Performance-EmployeeTrackerModel")
     *             ),
     *             @OA\Property(property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $employeeTrackerSearchFilterParams = $this->getEmployeeTrackerSearchFilterParams();
        $this->setSortingAndPaginationParams($employeeTrackerSearchFilterParams);

        $employeeTrackerSearchFilterParams->setEmpNumber(
            $this->getRequestParams()->getIntOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                CommonParams::PARAMETER_EMP_NUMBER
            )
        );
        $employeeTrackerSearchFilterParams->setTrackerIds(
            $this->getUserRoleManager()->getAccessibleEntityIds(
                PerformanceTracker::class,
                null,
                null,
                ['ESS']
            )
        );

        $employeeTrackerList = $this->getPerformanceTrackerService()
            ->getPerformanceTrackerDao()
            ->getEmployeeTrackerList($employeeTrackerSearchFilterParams);
        $employeeTrackerCount = $this->getPerformanceTrackerService()
            ->getPerformanceTrackerDao()
            ->getEmployeeTrackerCount($employeeTrackerSearchFilterParams);

        return new EndpointCollectionResult(
            EmployeeTrackerModel::class,
            $employeeTrackerList,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $employeeTrackerCount])
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
                    new Rule(
                        Rules::IN_ACCESSIBLE_ENTITY_ID,
                        [
                            Employee::class,
                            (new InAccessibleEntityIdOption())
                                ->setRolesToExclude(['Supervisor'])
                                ->setThrow(false)
                                ->setThrowIfOnlyEntityExist(false)
                                ->setRequiredPermissions(
                                    [BasicUserRoleManager::PERMISSION_TYPE_USER_ROLE_SPECIFIC => [ReviewerUserRole::REVIEWER_INCLUDE_EMPLOYEE => true]]
                                )
                        ]
                    )
                )
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::FILTER_INCLUDE_EMPLOYEES,
                    new Rule(Rules::IN, [EmployeeTrackerSearchFilterParams::INCLUDE_EMPLOYEES])
                )
            ),
            ...$this->getSortingAndPaginationParamsRules(
                EmployeeTrackerSearchFilterParams::ALLOWED_SORT_FIELDS
            )
        );
    }

    /**
     * @return EmployeeTrackerSearchFilterParams
     */
    protected function getEmployeeTrackerSearchFilterParams(): EmployeeTrackerSearchFilterParams
    {
        $employeeTrackerSearchFilterParams = new EmployeeTrackerSearchFilterParams();
        $employeeTrackerSearchFilterParams->setIncludeEmployees(
            $this->getRequestParams()->getString(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_INCLUDE_EMPLOYEES,
                EmployeeTrackerSearchFilterParams::INCLUDE_EMPLOYEES_ONLY_CURRENT
            )
        );
        return $employeeTrackerSearchFilterParams;
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

    /**
     *@OA\Get(
     *     path="/api/v2/performance/employees/trackers/{id}}",
     *     tags={"Performance/Employee Trackers"},
     * @OA\PathParameter(
     *     name="id",
     *     @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="data",
     *             ref="#/components/schemas/Performance-PerformanceTrackerModel"
     *         ),
     *         @OA\Property(property="meta", type="object")
     *     )
     * ),
     * @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     *
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $id = $this->getRequestParams()
            ->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, CommonParams::PARAMETER_ID);
        $performanceTracker = $this->getPerformanceTrackerService()
            ->getPerformanceTrackerDao()
            ->getPerformanceTracker($id);
        $this->throwRecordNotFoundExceptionIfNotExist($performanceTracker, PerformanceTracker::class);
        return new EndpointResourceResult(PerformanceTrackerModel::class, $performanceTracker);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(new ParamRule(
            CommonParams::PARAMETER_ID,
            new Rule(Rules::POSITIVE),
            new Rule(Rules::IN_ACCESSIBLE_ENTITY_ID, [PerformanceTracker::class])
        ));
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
}
