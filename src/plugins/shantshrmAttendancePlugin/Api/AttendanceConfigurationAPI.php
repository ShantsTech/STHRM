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

namespace ShantsHRM\Attendance\Api;

use Exception;
use ShantsHRM\Attendance\Api\Model\AttendanceConfigurationModel;
use ShantsHRM\Attendance\Dto\AttendanceConfiguration;
use ShantsHRM\Attendance\Traits\Service\AttendanceServiceTrait;
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
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\ORM\Exception\TransactionException;

class AttendanceConfigurationAPI extends Endpoint implements ResourceEndpoint
{
    use AttendanceServiceTrait;
    use UserRoleManagerTrait;
    use EntityManagerHelperTrait;

    public const PARAMETER_CAN_USER_CHANGE_THE_CURRENT_TIME = 'canUserChangeCurrentTime';
    public const PARAMETER_CAN_USER_MODIFY_ATTENDANCE = 'canUserModifyAttendance';
    public const PARAMETER_CAN_SUPERVISOR_MODIFY_ATTENDANCE = 'canSupervisorModifyAttendance';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $canUserChangeCurrentTime = $this->getAttendanceService()->canUserChangeCurrentTimeConfiguration();
        $canUserModifyAttendance = $this->getAttendanceService()->canUserModifyAttendanceConfiguration();
        $canSupervisorModifyAttendance = $this->getAttendanceService()->canSupervisorModifyAttendanceConfiguration();

        $attendanceConfiguration = new AttendanceConfiguration();
        $attendanceConfiguration->setCanUserChangeCurrentTime($canUserChangeCurrentTime);
        $attendanceConfiguration->setCanUserModifyAttendance($canUserModifyAttendance);
        $attendanceConfiguration->setCanSupervisorModifyAttendance($canSupervisorModifyAttendance);

        return new EndpointResourceResult(AttendanceConfigurationModel::class, $attendanceConfiguration);
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
        $this->beginTransaction();
        try {
            $canUserChangeCurrentTime = $this->getRequestParams()->getBoolean(
                RequestParams::PARAM_TYPE_BODY,
                self::PARAMETER_CAN_USER_CHANGE_THE_CURRENT_TIME,
            );
            $canUserModifyAttendance = $this->getRequestParams()->getBoolean(
                RequestParams::PARAM_TYPE_BODY,
                self::PARAMETER_CAN_USER_MODIFY_ATTENDANCE,
            );
            $canSupervisorModifyAttendance = $this->getRequestParams()->getBoolean(
                RequestParams::PARAM_TYPE_BODY,
                self::PARAMETER_CAN_SUPERVISOR_MODIFY_ATTENDANCE,
            );

            //Configuration - Employee can change current time when punching in/out
            if ($canUserChangeCurrentTime) {
                $this->getAttendanceService()->enableUserCanChangeCurrentTimeConfiguration();
            } else {
                $this->getAttendanceService()->disableUserCanChangeCurrentTimeConfiguration();
            }

            //Configuration - Employee can edit/delete own attendance records
            if ($canUserModifyAttendance) {
                $this->getAttendanceService()->enableUserCanModifyAttendanceConfiguration();
            } else {
                $this->getAttendanceService()->disableUserCanModifyAttendanceConfiguration();
            }

            //Supervisor can add/edit/delete attendance records of subordinates
            if ($canSupervisorModifyAttendance) {
                $this->getAttendanceService()->enableSupervisorCanModifyAttendanceConfiguration();
            } else {
                $this->getAttendanceService()->disableSupervisorCanModifyAttendanceConfiguration();
            }
            $attendanceConfiguration = new AttendanceConfiguration();
            $attendanceConfiguration->setCanUserChangeCurrentTime($canUserChangeCurrentTime);
            $attendanceConfiguration->setCanUserModifyAttendance($canUserModifyAttendance);
            $attendanceConfiguration->setCanSupervisorModifyAttendance($canSupervisorModifyAttendance);

            $this->commitTransaction();

            return new EndpointResourceResult(AttendanceConfigurationModel::class, $attendanceConfiguration);
        } catch (Exception $e) {
            $this->rollBackTransaction();
            throw new TransactionException($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        $paramRules = new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_CAN_USER_CHANGE_THE_CURRENT_TIME,
                new Rule(Rules::BOOL_TYPE)
            ),
            new ParamRule(
                self::PARAMETER_CAN_USER_MODIFY_ATTENDANCE,
                new Rule(Rules::BOOL_TYPE)
            ),
            new ParamRule(
                self::PARAMETER_CAN_SUPERVISOR_MODIFY_ATTENDANCE,
                new Rule(Rules::BOOL_TYPE)
            )
        );
        $paramRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
        return $paramRules;
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
