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
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Service\MenuService;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Framework\Services;
use ShantsHRM\Time\Api\Model\TimeConfigPeriodModel;
use ShantsHRM\Time\Dto\TimeConfigPeriod;
use ShantsHRM\Time\Service\TimesheetPeriodService;

class TimeConfigPeriodAPI extends Endpoint implements ResourceEndpoint
{
    use ConfigServiceTrait;

    public const PARAMETER_START_DAY = 'startDay';

    /**
     * @var TimesheetPeriodService|null
     */
    protected ?TimesheetPeriodService $timesheetPeriodService = null;

    /**
     * @return TimesheetPeriodService
     */
    protected function getTimesheetPeriodService(): TimesheetPeriodService
    {
        if (!$this->timesheetPeriodService instanceof TimesheetPeriodService) {
            $this->timesheetPeriodService = new TimesheetPeriodService();
        }
        return $this->timesheetPeriodService;
    }

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $status = $this->getTimesheetPeriodService()->isTimesheetPeriodDefined();
        $startDay = $status ? $this->getTimesheetPeriodService()->getTimesheetStartDate() : '1'; // to set monday as default
        $timeConfigPeriod = new TimeConfigPeriod();
        $timeConfigPeriod->setStartDay($startDay);
        return new EndpointResourceResult(TimeConfigPeriodModel::class, $timeConfigPeriod);
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
        $startDay = $this->getRequestParams()->getString(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_START_DAY);
        $this->getTimesheetPeriodService()->setTimesheetPeriod($startDay);
        //After configuring the start day, we need to enable the menu items for time module
        /** @var MenuService $menuService */
        $menuService = $this->getContainer()->get(Services::MENU_SERVICE);
        $menuService->enableModuleMenuItems('time');
        $menuService->enableModuleMenuItems('attendance');
        $timeConfigPeriod = new TimeConfigPeriod();
        $timeConfigPeriod->setStartDay($startDay);
        return new EndpointResourceResult(TimeConfigPeriodModel::class, $timeConfigPeriod);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        $status = $this->getTimesheetPeriodService()->isTimesheetPeriodDefined();
        if ($status) {
            throw $this->getBadRequestException('Already defined');
        }
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_ID
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_START_DAY,
                    new Rule(Rules::BETWEEN, [1, 7])
                ),
            ),
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
