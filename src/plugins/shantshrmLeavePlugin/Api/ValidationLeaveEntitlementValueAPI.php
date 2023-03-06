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
use ShantsHRM\Entity\LeaveEntitlement;
use ShantsHRM\Leave\Api\Traits\LeaveEntitlementPermissionTrait;
use ShantsHRM\Leave\Traits\Service\LeaveEntitlementServiceTrait;

class ValidationLeaveEntitlementValueAPI extends Endpoint implements ResourceEndpoint
{
    use LeaveEntitlementServiceTrait;
    use LeaveEntitlementPermissionTrait;

    public const PARAMETER_ENTITLEMENT = 'entitlement';

    public const PARAMETER_VALID = 'valid';
    public const PARAMETER_DAYS_USED = 'daysUsed';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $id = $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, CommonParams::PARAMETER_ID);
        $leaveEntitlement = $this->getLeaveEntitlementService()->getLeaveEntitlementDao()->getLeaveEntitlement($id);
        $this->throwRecordNotFoundExceptionIfNotExist($leaveEntitlement, LeaveEntitlement::class);
        $this->checkLeaveEntitlementAccessible($leaveEntitlement);

        $entitlement = $this->getRequestParams()->getFloat(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_ENTITLEMENT
        );

        $isValidEntitlement = true;
        if ($leaveEntitlement->getDaysUsed() > $entitlement) {
            $isValidEntitlement = false;
        }
        return new EndpointResourceResult(
            ArrayModel::class,
            [
                self::PARAMETER_VALID => $isValidEntitlement,
                self::PARAMETER_DAYS_USED => $leaveEntitlement->getDaysUsed(),
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(CommonParams::PARAMETER_ID, new Rule(Rules::POSITIVE)),
            new ParamRule(self::PARAMETER_ENTITLEMENT, new Rule(Rules::ZERO_OR_POSITIVE))
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
