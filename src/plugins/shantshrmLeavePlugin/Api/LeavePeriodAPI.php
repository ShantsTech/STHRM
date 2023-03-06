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
use ShantsHRM\Core\Service\MenuService;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Entity\LeavePeriodHistory;
use ShantsHRM\Framework\Services;
use ShantsHRM\Leave\Api\Model\LeavePeriodHistoryModel;
use ShantsHRM\Leave\Api\Model\LeavePeriodModel;
use ShantsHRM\Leave\Traits\Service\LeaveConfigServiceTrait;
use ShantsHRM\Leave\Traits\Service\LeavePeriodServiceTrait;

class LeavePeriodAPI extends Endpoint implements CrudEndpoint
{
    use LeavePeriodServiceTrait;
    use LeaveConfigServiceTrait;
    use NormalizerServiceTrait;
    use DateTimeHelperTrait;

    public const PARAMETER_START_MONTH = 'startMonth';
    public const PARAMETER_START_DAY = 'startDay';

    public const META_PARAMETER_LEAVE_PERIOD_DEFINED = 'leavePeriodDefined';
    public const META_PARAMETER_CURRENT_LEAVE_PERIOD = 'currentLeavePeriod';

    /**
     * @OA\Get(
     *     path="/api/v2/leave/leave-period",
     *     tags={"Leave/Configure"},
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Leave-LeavePeriodHistoryModel"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="currentLeavePeriod",
     *                     ref="#/components/schemas/Leave-LeavePeriodModel"
     *                 ),
     *                 @OA\Property(property="leavePeriodDefined", type="boolean"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     *
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $leavePeriodHistory = $this->getLeavePeriodService()->getCurrentLeavePeriodStartDateAndMonth();
        $leavePeriodDefined = $this->getLeaveConfigService()->isLeavePeriodDefined();
        if (!$leavePeriodDefined) {
            $leavePeriodHistory = new LeavePeriodHistory();
            $leavePeriodHistory->setStartMonth(1);
            $leavePeriodHistory->setStartDay(1);
            $leavePeriodHistory->setCreatedAt($this->getDateTimeHelper()->getNow());
        }
        return new EndpointResourceResult(
            LeavePeriodHistoryModel::class,
            $leavePeriodHistory,
            new ParameterBag(
                [
                    self::META_PARAMETER_LEAVE_PERIOD_DEFINED => $leavePeriodDefined,
                    self::META_PARAMETER_CURRENT_LEAVE_PERIOD => $this->getCurrentLeavePeriod($leavePeriodDefined),
                ]
            )
        );
    }

    /**
     * @param bool $leavePeriodDefined
     * @return array|null
     */
    private function getCurrentLeavePeriod(bool $leavePeriodDefined): ?array
    {
        return $leavePeriodDefined ?
            $this->getNormalizerService()->normalize(
                LeavePeriodModel::class,
                $this->getLeavePeriodService()->getCurrentLeavePeriod(true)
            ) : null;
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
     * @OA\Get(
     *     path="/api/v2/leave/leave-periods",
     *     tags={"Leave/Configure"},
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Leave-LeavePeriodModel"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="currentLeavePeriod",
     *                     ref="#/components/schemas/Leave-LeavePeriodModel"
     *                 ),
     *                 @OA\Property(property="leavePeriodDefined", type="boolean"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     *
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $leavePeriodDefined = $this->getLeaveConfigService()->isLeavePeriodDefined();
        return new EndpointCollectionResult(
            LeavePeriodModel::class,
            $this->getLeavePeriodService()->getGeneratedLeavePeriodList(),
            new ParameterBag(
                [
                    self::META_PARAMETER_LEAVE_PERIOD_DEFINED => $leavePeriodDefined,
                    self::META_PARAMETER_CURRENT_LEAVE_PERIOD => $this->getCurrentLeavePeriod($leavePeriodDefined),
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection();
    }

    /**
     * @OA\Put(
     *     path="/api/v2/leave/leave-period",
     *     tags={"Leave/Configure"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="startDay", type="integer"),
     *             @OA\Property(property="startMonth", type="integer")
     *         )
     *     ),
     *     @OA\Response(response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Leave-LeavePeriodHistoryModel"
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(
     *                     property="currentLeavePeriod",
     *                     ref="#/components/schemas/Leave-LeavePeriodModel"
     *                 ),
     *                 @OA\Property(property="leavePeriodDefined", type="boolean"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     *
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        $leavePeriodDefined = $this->getLeaveConfigService()->isLeavePeriodDefined();
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setStartMonth(
            $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_START_MONTH)
        );
        $leavePeriodHistory->setStartDay(
            $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_START_DAY)
        );
        $leavePeriodHistory->setCreatedAt($this->getDateTimeHelper()->getNow());
        $this->getLeavePeriodService()
            ->getLeavePeriodDao()
            ->saveLeavePeriodHistory($leavePeriodHistory);

        if (!$leavePeriodDefined) {
            /** @var MenuService $menuService */
            $menuService = $this->getContainer()->get(Services::MENU_SERVICE);
            $menuService->enableModuleMenuItems('leave');
        }
        return new EndpointResourceResult(
            LeavePeriodHistoryModel::class,
            $leavePeriodHistory,
            new ParameterBag(
                [
                    self::META_PARAMETER_LEAVE_PERIOD_DEFINED => $leavePeriodDefined,
                    self::META_PARAMETER_CURRENT_LEAVE_PERIOD => $this->getCurrentLeavePeriod($leavePeriodDefined),
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        $paramRules = new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_START_MONTH,
                new Rule(Rules::IN, [$this->getLeavePeriodService()->getMonthNumberList()])
            ),
            new ParamRule(
                self::PARAMETER_START_DAY,
                new Rule(Rules::POSITIVE),
                new Rule(Rules::CALLBACK, [
                    function (int $startDay) {
                        $startMonth = $this->getRequestParams()->getInt(
                            RequestParams::PARAM_TYPE_BODY,
                            self::PARAMETER_START_MONTH
                        );
                        $allowedDaysForMonth = $this->getLeavePeriodService()->getListOfDates($startMonth, false);
                        return in_array($startDay, $allowedDaysForMonth);
                    }
                ])
            ),
        );
        $paramRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
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
