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

namespace ShantsHRM\Recruitment\Api;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Pim\Api\Model\EmployeeModel;
use ShantsHRM\Pim\Dto\EmployeeSearchFilterParams;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;
use ShantsHRM\Recruitment\Dto\VacancySearchFilterParams;
use ShantsHRM\Recruitment\Traits\Service\VacancyServiceTrait;

class HiringManagerAPI extends Endpoint implements CollectionEndpoint
{
    use VacancyServiceTrait;
    use EmployeeServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $accessibleVacancyIds = $this->getUserRoleManager()->getAccessibleEntityIds(Vacancy::class);
        $vacancySearchFilterParams = new VacancySearchFilterParams();
        $vacancySearchFilterParams->setVacancyIds($accessibleVacancyIds);
        $vacancies = $this->getVacancyService()
            ->getVacancyDao()
            ->getVacancyListGroupByHiringManager($vacancySearchFilterParams);
        $hiringManagerEmpNumbers = array_map(function ($vacancy) {
            return $vacancy->getHiringManager()->getEmpNumber();
        }, $vacancies);
        $employeeSearchFilterParams = new EmployeeSearchFilterParams();
        $this->setSortingAndPaginationParams($employeeSearchFilterParams);
        $employeeSearchFilterParams->setEmployeeNumbers($hiringManagerEmpNumbers);
        $employeeSearchFilterParams->setIncludeEmployees(
            EmployeeSearchFilterParams::INCLUDE_EMPLOYEES_CURRENT_AND_PAST
        );
        $hiringManagers = $this->getEmployeeService()->getEmployeeList($employeeSearchFilterParams);
        $count = $this->getEmployeeService()->getEmployeeCount($employeeSearchFilterParams);
        return new EndpointCollectionResult(
            EmployeeModel::class,
            $hiringManagers,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $count])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            ...$this->getSortingAndPaginationParamsRules(EmployeeSearchFilterParams::ALLOWED_SORT_FIELDS)
        );
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
