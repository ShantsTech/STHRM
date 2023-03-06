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

use DateTime;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Dashboard\Traits\Service\EmployeeTimeAtWorkServiceTrait;

class EmployeeTimeAtWorkAPI extends Endpoint implements ResourceEndpoint
{
    use AuthUserTrait;
    use DateTimeHelperTrait;
    use EmployeeTimeAtWorkServiceTrait;

    public const PARAMETER_CURRENT_DATE = 'currentDate';
    public const PARAMETER_CURRENT_TIME = 'currentTime';
    public const PARAMETER_TIME_ZONE_OFFSET = 'timezoneOffset';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $empNumber = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_QUERY,
            CommonParams::PARAMETER_EMP_NUMBER,
            $this->getAuthUser()->getEmpNumber()
        );
        $currentDate = $this->getRequestParams()->getStringOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_CURRENT_DATE,
        );
        $currentTime = $this->getRequestParams()->getStringOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_CURRENT_TIME
        );
        $timezoneOffset = $this->getRequestParams()->getFloatOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_TIME_ZONE_OFFSET,
        );

        if ((!is_null($currentDate) && !is_null($currentTime) && !is_null($timezoneOffset))) {
            $spotDateTime = new DateTime(
                $currentDate.' '.$currentTime,
                $this->getDateTimeHelper()->getTimezoneByTimezoneOffset($timezoneOffset)
            );
            $currentDateTime = new DateTime(
                $currentDate . ' 00:00:00',
                $this->getDateTimeHelper()->getTimezoneByTimezoneOffset($timezoneOffset)
            );
        } else {
            $serverCurrentDateTime = $this->getDateTimeHelper()->getNow();
            $spotDateTime = $serverCurrentDateTime;
            $currentDateTime = new DateTime(
                $this->getDateTimeHelper()->formatDateTimeToYmd($serverCurrentDateTime) . ' 00:00:00',
                $serverCurrentDateTime->getTimezone()
            );
        }

        list($timeAtWorkDate, $timeAtWorkMetaData) = $this->getEmployeeTimeAtWorkService()->getTimeAtWorkResults(
            $empNumber,
            $currentDateTime,
            $spotDateTime
        );

        return new EndpointResourceResult(
            ArrayModel::class,
            $timeAtWorkDate,
            new ParameterBag($timeAtWorkMetaData)
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        $paramRules = new ParamRuleCollection(
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    CommonParams::PARAMETER_EMP_NUMBER,
                    new Rule(Rules::IN_ACCESSIBLE_EMP_NUMBERS)
                ),
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::PARAMETER_CURRENT_DATE,
                    new Rule(Rules::API_DATE)
                ),
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::PARAMETER_CURRENT_TIME,
                    new Rule(Rules::TIME, ['H:i'])
                ),
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::PARAMETER_TIME_ZONE_OFFSET,
                    new Rule(Rules::TIMEZONE_OFFSET)
                ),
            ),
        );
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
