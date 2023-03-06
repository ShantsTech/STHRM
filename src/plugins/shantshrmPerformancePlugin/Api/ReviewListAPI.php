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
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Performance\Api\Model\PerformanceReviewModel;
use ShantsHRM\Performance\Dto\PerformanceReviewSearchFilterParams;
use ShantsHRM\Performance\Traits\Service\PerformanceReviewServiceTrait;

class ReviewListAPI extends PerformanceReviewAPI
{
    use PerformanceReviewServiceTrait;
    use AuthUserTrait;

    public const FILTER_SUBUNIT_ID = 'subunitId';

    /**
     * @OA\Get(
     *     path="/api/v2/performance/employees/reviews",
     *     tags={"Performance/Reviews"},
     *     @OA\Parameter(
     *         name="empNumber",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="jobTitleId",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="statusId",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="subunitId",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="fromDate",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="toDate",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="includeEmployees",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sortField",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum=PerformanceReviewSearchFilterParams::REVIEW_LIST_ALLOWED_SORT_FIELDS)
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
     *                 @OA\Items(ref="#/components/schemas/Performance-PerformanceReviewModel")
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
        $performanceReviewSearchFilterParams = new PerformanceReviewSearchFilterParams();
        $this->setSortingAndPaginationParams($performanceReviewSearchFilterParams);

        $performanceReviewSearchFilterParams->setExcludeInactiveReviews(true);

        $performanceReviewSearchFilterParams->setReviewerEmpNumber(
            $this->getAuthUser()->getEmpNumber()
        );

        $performanceReviewSearchFilterParams->setSubunitId(
            $this->getRequestParams()->getIntOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_SUBUNIT_ID
            )
        );
        $this->getFilterParams($performanceReviewSearchFilterParams);

        $reviewList = $this->getPerformanceReviewService()
            ->getPerformanceReviewDao()
            ->getPerformanceReviewList($performanceReviewSearchFilterParams);
        $reviewListCount = $this->getPerformanceReviewService()
            ->getPerformanceReviewDao()
            ->getPerformanceReviewCount($performanceReviewSearchFilterParams);

        return new EndpointCollectionResult(
            PerformanceReviewModel::class,
            $reviewList,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $reviewListCount])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(self::FILTER_SUBUNIT_ID, new Rule(Rules::POSITIVE))
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::FILTER_STATUS_ID,
                    new Rule(Rules::POSITIVE),
                    new Rule(Rules::IN, [PerformanceReviewSearchFilterParams::REVIEW_LIST_STATUSES])
                )
            ),
            ...$this->getFilterParamRules(),
            ...$this->getSortingAndPaginationParamsRules(
                PerformanceReviewSearchFilterParams::REVIEW_LIST_ALLOWED_SORT_FIELDS
            )
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

    public function update(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
