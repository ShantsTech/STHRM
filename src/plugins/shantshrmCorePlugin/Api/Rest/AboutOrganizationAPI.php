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

namespace ShantsHRM\Core\Api\Rest;

use ShantsHRM\Admin\Dto\AboutOrganization;
use ShantsHRM\Admin\Service\OrganizationService;
use ShantsHRM\Config\Config;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\Rest\Model\AboutOrganizationModel;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Entity\Organization;
use ShantsHRM\Pim\Dto\EmployeeSearchFilterParams;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;

class AboutOrganizationAPI extends Endpoint implements ResourceEndpoint
{
    use EmployeeServiceTrait;

    /**
     * @var null|OrganizationService
     */
    protected ?OrganizationService $organizationService = null;

    /**
     * @return OrganizationService
     */
    public function getOrganizationService(): OrganizationService
    {
        if (is_null($this->organizationService)) {
            $this->organizationService = new OrganizationService();
        }
        return $this->organizationService;
    }

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $aboutOrganization = new AboutOrganization();
        $employeeParamHolder = new EmployeeSearchFilterParams();
        $employeeParamHolder->setIncludeEmployees("3");
        $organization = $this->getOrganizationService()->getOrganizationGeneralInformation();
        $organizationName = $organization instanceof Organization ? $organization->getName() : 'ShantsHRM';
        $numberOfActiveEmployees = $this->getEmployeeService()->getNumberOfEmployees();
        $numberOfPastEmployees = $this->getEmployeeService()->getEmployeeCount($employeeParamHolder);
        $aboutOrganization->setCompanyName($organizationName);
        $aboutOrganization->setVersion(Config::PRODUCT_VERSION);
        $aboutOrganization->setNumberOfActiveEmployee($numberOfActiveEmployees);
        $aboutOrganization->setNumberOfPastEmployee($numberOfPastEmployees);
        return new EndpointResourceResult(AboutOrganizationModel::class, $aboutOrganization);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_ID
            ),
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
