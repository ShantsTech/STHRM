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
use ShantsHRM\Admin\Service\LocationService;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Entity\Country;
use ShantsHRM\Entity\Location;
use ShantsHRM\Framework\Services;

class LocationDecorator
{
    use EntityManagerHelperTrait;
    use ServiceContainerTrait;


    protected ?LocationService $locationService = null;

    /**
     * @var Location
     */
    protected Location $location;

    /**
     * Set Location Service
     *
     * @param LocationService $locationService
     */
    public function setLocationService(LocationService $locationService): void
    {
        $this->locationService = $locationService;
    }

    /**
     * Returns Location Service
     *
     * @returns LocationService
     */
    public function getLocationService(): LocationService
    {
        if (is_null($this->locationService)) {
            $this->locationService = new LocationService();
        }
        return $this->locationService;
    }

    /**
     * LocationDecorator constructor.
     *
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return Location
     */
    protected function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * Sets the given country code as the related country of the Location entity
     *
     * @param string|null $countryCode
     */
    public function setCountryByCountryCode(?string $countryCode): void
    {
        /** @var CountryService $countryService */
        $countryService = $this->getContainer()->get(Services::COUNTRY_SERVICE);
        /** @var Country|null $country */
        $country = is_null($countryCode) ? null : $countryService->getCountryByCountryCode($countryCode);
        $this->getLocation()->setCountry($country);
    }

    /**
     * Returns the number of employees in the given location.
     *
     * @return int
     */
    public function getNoOfEmployees(): int
    {
        return $this->getLocationService()->getNumberOfEmployeesForLocation($this->getLocation()->getId());
    }
}
