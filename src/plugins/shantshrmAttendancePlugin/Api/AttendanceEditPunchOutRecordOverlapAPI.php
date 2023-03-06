<?php
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 Shants Tech LLC., http://www.hrm.shants-tech.com
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

namespace ShantsHRM\Attendance\Api;

use DateTime;
use DateTimeZone;
use ShantsHRM\Attendance\Api\Traits\AttendanceRecordPermissionTrait;
use ShantsHRM\Attendance\Exception\AttendanceServiceException;
use ShantsHRM\Attendance\Traits\Service\AttendanceServiceTrait;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Service\DateTimeHelperService;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;

class AttendanceEditPunchOutRecordOverlapAPI extends Endpoint implements ResourceEndpoint
{
    use DateTimeHelperTrait;
    use AuthUserTrait;
    use AttendanceServiceTrait;
    use AttendanceRecordPermissionTrait;

    public const PARAMETER_RECORD_ID = 'recordId';
    public const PARAMETER_PUNCH_IN_DATE = 'punchInDate';
    public const PARAMETER_PUNCH_IN_TIME = 'punchInTime';
    public const PARAMETER_PUNCH_OUT_DATE = 'punchOutDate';
    public const PARAMETER_PUNCH_OUT_TIME = 'punchOutTime';
    public const PARAMETER_PUNCH_IN_TIME_ZONE_OFFSET = 'punchInTimezoneOffset';
    public const PARAMETER_PUNCH_OUT_TIME_ZONE_OFFSET = 'punchOutTimezoneOffset';
    public const PARAMETER_IS_PUNCH_OUT_OVERLAP = 'valid';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        try {
            $recordId = $this->getRequestParams()->getInt(
                RequestParams::PARAM_TYPE_QUERY,
                self::PARAMETER_RECORD_ID
            );

            $attendanceRecord = $this->getAttendanceService()
                ->getAttendanceDao()
                ->getAttendanceRecordById($recordId);

            $this->throwRecordNotFoundExceptionIfNotExist($attendanceRecord);
            $this->checkAttendanceRecordAccessible($attendanceRecord);
            list($punchInUtc, $punchOutUtc) = $this->getUTCTimeByOffsetAndDateTime();

            $isPunchInOverlap = $this->getAttendanceService()
                ->getAttendanceDao()
                ->checkForPunchInOutOverLappingRecordsWhenEditing(
                    $punchInUtc,
                    $punchOutUtc,
                    $attendanceRecord->getEmployee()->getEmpNumber(),
                    $recordId
                );

            return new EndpointResourceResult(
                ArrayModel::class,
                [
                    self::PARAMETER_IS_PUNCH_OUT_OVERLAP => $isPunchInOverlap,
                ]
            );
        } catch (AttendanceServiceException $attendanceServiceException) {
            throw $this->getBadRequestException($attendanceServiceException->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        $paramRules = new ParamRuleCollection(
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_RECORD_ID,
                    new Rule(Rules::POSITIVE)
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PUNCH_IN_DATE,
                    new Rule(Rules::API_DATE)
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PUNCH_OUT_DATE,
                    new Rule(Rules::API_DATE)
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PUNCH_IN_TIME,
                    new Rule(Rules::TIME, ['H:i'])
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PUNCH_OUT_TIME,
                    new Rule(Rules::TIME, ['H:i'])
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PUNCH_IN_TIME_ZONE_OFFSET,
                    new Rule(Rules::STRING_TYPE)
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PUNCH_OUT_TIME_ZONE_OFFSET,
                    new Rule(Rules::STRING_TYPE)
                )
            )
        );
        $paramRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
        return $paramRules;
    }

    /**
     * @return array
     */
    protected function getUTCTimeByOffsetAndDateTime(): array
    {
        $punchInDate = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PUNCH_IN_DATE,
        );

        $punchOutDate = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PUNCH_OUT_DATE,
        );

        $punchInTime = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PUNCH_IN_TIME,
        );

        $punchOutTime = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PUNCH_OUT_TIME,
        );

        $punchInTimezoneOffset = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PUNCH_IN_TIME_ZONE_OFFSET,
        );

        $punchOutTimezoneOffset = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PUNCH_OUT_TIME_ZONE_OFFSET,
        );

        $punchInDateTime = $punchInDate . ' ' . $punchInTime;
        $punchOutDateTime = $punchOutDate . ' ' . $punchOutTime;

        $punchInDateTime = new DateTime(
            $punchInDateTime,
            $this->getDateTimeHelper()->getTimezoneByTimezoneOffset($punchInTimezoneOffset)
        );

        $punchOutDateTime = new DateTime(
            $punchOutDateTime,
            $this->getDateTimeHelper()->getTimezoneByTimezoneOffset($punchOutTimezoneOffset)
        );

        return [
            $punchInDateTime->setTimezone(new DateTimeZone(DateTimeHelperService::TIMEZONE_UTC)),
            $punchOutDateTime->setTimezone(new DateTimeZone(DateTimeHelperService::TIMEZONE_UTC))
        ];
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
