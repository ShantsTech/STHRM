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

use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Dashboard\Api\Model\EmployeeDistributionByLocationModel;
use ShantsHRM\Dashboard\Traits\Service\ChartServiceTrait;

class EmployeeDistributionByLocationAPI extends Endpoint implements CollectionEndpoint
{
    use ChartServiceTrait;

    public const PARAMETER_OTHER_EMPLOYEE_COUNT = 'otherEmployeeCount';
    public const PARAMETER_UNASSIGNED_EMPLOYEE_COUNT = 'unassignedEmployeeCount';
    public const PARAMETER_TOTAL_LOCATION_COUNT = 'totalLocationCount';

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $employeeDistribution = $this->getChartService()->getEmployeeDistributionByLocation();

        return new EndpointCollectionResult(
            EmployeeDistributionByLocationModel::class,
            $employeeDistribution->getLocationCountPairs(),
            new ParameterBag([
                self::PARAMETER_OTHER_EMPLOYEE_COUNT => $employeeDistribution->getOtherEmployeeCount(
                ),
                self::PARAMETER_UNASSIGNED_EMPLOYEE_COUNT => $employeeDistribution->getUnassignedEmployeeCount(
                ),
                self::PARAMETER_TOTAL_LOCATION_COUNT => $employeeDistribution->getTotalLocationCount(),
            ]),
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
