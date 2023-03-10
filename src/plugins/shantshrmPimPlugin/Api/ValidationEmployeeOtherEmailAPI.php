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

namespace ShantsHRM\Pim\Api;

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
use ShantsHRM\Entity\Employee;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;

class ValidationEmployeeOtherEmailAPI extends Endpoint implements ResourceEndpoint
{
    use EmployeeServiceTrait;

    public const PARAMETER_OTHER_EMAIL = 'otherEmail';
    public const PARAMETER_IS_UNIQUE_OTHER_EMAIL = 'valid';

    public const PARAM_RULE_OTHER_EMAIL_MAX_LENGTH = 50;

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $empNumber = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            CommonParams::PARAMETER_EMP_NUMBER
        );
        $workEmail = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_OTHER_EMAIL
        );
        $employee = $this->getEmployeeService()->getEmployeeDao()->getEmployeeByEmpNumber($empNumber);
        $this->throwRecordNotFoundExceptionIfNotExist($employee, Employee::class);
        $isChangeableWorkEmail = $this->getEmployeeService()
            ->isUniqueEmail(
                $workEmail,
                $employee->getOtherEmail()
            );
        return new EndpointResourceResult(
            ArrayModel::class,
            [
                self::PARAMETER_IS_UNIQUE_OTHER_EMAIL => $isChangeableWorkEmail,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_OTHER_EMAIL,
                new Rule(Rules::STRING_TYPE),
                new Rule(Rules::LENGTH, [null, self::PARAM_RULE_OTHER_EMAIL_MAX_LENGTH]),
            ),
            new ParamRule(CommonParams::PARAMETER_EMP_NUMBER),
        );
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
