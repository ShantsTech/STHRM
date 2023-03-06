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

namespace ShantsHRM\Buzz\Api;

use DateInterval;
use ShantsHRM\Buzz\Api\Model\EmployeeAnniversaryModel;
use ShantsHRM\Buzz\Traits\Service\BuzzAnniversaryServiceTrait;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Buzz\Dto\EmployeeAnniversarySearchFilterParams;

class EmployeeAnniversaryAPI extends Endpoint implements CollectionEndpoint
{
    use BuzzAnniversaryServiceTrait;
    use DateTimeHelperTrait;

    public const DATE_DIFFERENCE_MIN = 0;
    public const DATE_DIFFERENCE_MAX = 30;

    /**
     * @OA\Get(
     *     path="/api/v2/buzz/anniversaries",
     *     tags={"Buzz/Employee Anniversary"},
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
     *                 @OA\Items(ref="#/components/schemas/Buzz-EmployeeAnniversaryModel")
     *             ),
     *             @OA\Property(property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     *
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $employeeAnniversarySearchFilterParams = new EmployeeAnniversarySearchFilterParams();
        $this->setSortingAndPaginationParams($employeeAnniversarySearchFilterParams);

        $thisYear = $this->getDateTimeHelper()->getNow()->format('Y');
        $nextDate =  $this->getDateTimeHelper()->getNow();
        $nextDate->add(new DateInterval('P30D'));

        $employeeAnniversarySearchFilterParams->setThisYear($thisYear);
        $employeeAnniversarySearchFilterParams->setNextDate($nextDate);
        $employeeAnniversarySearchFilterParams->setDateDiffMin(self::DATE_DIFFERENCE_MIN);
        $employeeAnniversarySearchFilterParams->setDateDiffMax(self::DATE_DIFFERENCE_MAX);

        $upcomingAnniversaries = $this->getBuzzAnniversaryService()->getBuzzAnniversaryDao()
            ->getUpcomingAnniversariesList($employeeAnniversarySearchFilterParams);

        $count = $this->getBuzzAnniversaryService()
            ->getBuzzAnniversaryDao()
            ->getUpcomingAnniversariesCount($employeeAnniversarySearchFilterParams);

        return new EndpointCollectionResult(
            EmployeeAnniversaryModel::class,
            $upcomingAnniversaries,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $count])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
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
