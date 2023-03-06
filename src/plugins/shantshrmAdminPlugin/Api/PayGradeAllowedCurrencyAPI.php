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

namespace ShantsHRM\Admin\Api;

use ShantsHRM\Admin\Api\Model\CurrencyTypeModel;
use ShantsHRM\Admin\Dto\PayGradeCurrencySearchFilterParams;
use ShantsHRM\Admin\Service\PayGradeService;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Services;

class PayGradeAllowedCurrencyAPI extends Endpoint implements CollectionEndpoint
{
    use ServiceContainerTrait;

    /**
     * @return PayGradeService
     * @throws \Exception
     */
    public function getPayGradeService(): PayGradeService
    {
        return $this->getContainer()->get(Services::PAY_GRADE_SERVICE);
    }

    /**
     * @OA\Get(
     *     path="/api/v2/admin/pay-grades/{payGradeId}/currencies/allowed",
     *     tags={"Admin/Pay Grade"},
     *     @OA\PathParameter(
     *         name="payGradeId",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="sortField",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum=PayGradeCurrencySearchFilterParams::ALLOWED_SORT_FIELDS)
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
     *                 @OA\Items(ref="#/components/schemas/Admin-CurrencyTypeModel")
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
        $payGradeId = $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, PayGradeCurrencySearchFilterParams::PARAMETER_PAY_GRADE_ID);
        $payGradeCurrencySearchFilterParams = new PayGradeCurrencySearchFilterParams();
        $payGradeCurrencySearchFilterParams->setPayGradeId($payGradeId);
        $payGradeCurrencySearchFilterParams->setSortField('ct.id');
        $payGradeCurrencySearchFilterParams->setLimit(0);
        $allowedCurrencies = $this->getPayGradeService()->getAllowedPayCurrencies($payGradeCurrencySearchFilterParams);
        $count = $this->getPayGradeService()->getAllowedPayCurrenciesCount($payGradeCurrencySearchFilterParams);
        return new EndpointCollectionResult(
            CurrencyTypeModel::class,
            $allowedCurrencies,
            new ParameterBag([
                PayGradeCurrencySearchFilterParams::PARAMETER_PAY_GRADE_ID => $payGradeId,
                CommonParams::PARAMETER_TOTAL=> $count,
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
                PayGradeCurrencySearchFilterParams::PARAMETER_PAY_GRADE_ID,
                new Rule(Rules::POSITIVE)
            ),
            ...$this->getSortingAndPaginationParamsRules(PayGradeCurrencySearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    public function create(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
