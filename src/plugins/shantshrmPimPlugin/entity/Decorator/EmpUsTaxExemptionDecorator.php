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

namespace ShantsHRM\Entity\Decorator;

use ShantsHRM\Admin\Service\CountryService;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\EmpUsTaxExemption;
use ShantsHRM\Framework\Services;

class EmpUsTaxExemptionDecorator
{
    use EntityManagerHelperTrait;
    use ServiceContainerTrait;

    /**
     * @var EmpUsTaxExemption
     */
    protected EmpUsTaxExemption $empUsTaxExemption;

    /**
     * EmpUsTaxExemptionDecorator constructor.
     * @param EmpUsTaxExemption $empUsTaxExemption
     */
    public function __construct(EmpUsTaxExemption $empUsTaxExemption)
    {
        $this->empUsTaxExemption = $empUsTaxExemption;
    }

    /**
     * @return EmpUsTaxExemption
     */
    protected function getEmpUsTaxExemption(): EmpUsTaxExemption
    {
        return $this->empUsTaxExemption;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getEmpUsTaxExemption()->setEmployee($employee);
    }


    /**
     * @return string|null
     */
    public function getTaxState(): ?string
    {
        $stateCode = $this->getEmpUsTaxExemption()->getState();
        /** @var CountryService $countryService */
        $countryService = $this->getContainer()->get(Services::COUNTRY_SERVICE);
        if (is_null($stateCode)) {
            return null;
        }
        $state = $countryService->getProvinceByProvinceCode($stateCode);
        return $state->getProvinceName();
    }

    /**
     * @return string|null
     */
    public function getUnemploymentState(): ?string
    {
        $stateCode = $this->getEmpUsTaxExemption()->getUnemploymentState();
        /** @var CountryService $countryService */
        $countryService = $this->getContainer()->get(Services::COUNTRY_SERVICE);
        if (is_null($stateCode)) {
            return null;
        }
        $state = $countryService->getProvinceByProvinceCode($stateCode);
        return $state->getProvinceName();
    }

    /**
     * @return string|null
     */
    public function getWorkState(): ?string
    {
        $stateCode = $this->getEmpUsTaxExemption()->getWorkState();
        /** @var CountryService $countryService */
        $countryService = $this->getContainer()->get(Services::COUNTRY_SERVICE);
        if (is_null($stateCode)) {
            return null;
        }
        $state = $countryService->getProvinceByProvinceCode($stateCode);
        return $state->getProvinceName();
    }
}
