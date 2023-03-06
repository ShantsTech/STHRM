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

namespace ShantsHRM\Pim\Controller;

use ShantsHRM\Admin\Service\CountryService;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Services;

class EmployeeImmigrationController extends BaseViewEmployeeController
{
    use ServiceContainerTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $empNumber = $request->attributes->get('empNumber');
        if ($empNumber) {
            $component = new Component('employee-immigration');
            $component->addProp(new Prop('emp-number', Prop::TYPE_NUMBER, $empNumber));

            /** @var CountryService $countryService */
            $countryService = $this->getContainer()->get(Services::COUNTRY_SERVICE);
            $component->addProp(new Prop('countries', Prop::TYPE_ARRAY, $countryService->getCountryArray()));

            $this->setComponent($component);

            $this->setPermissionsForEmployee(
                [
                    'immigration',
                    'immigration_attachment',
                    'immigration_custom_fields',
                ],
                $empNumber
            );
        } else {
            $this->handleBadRequest();
        }
    }

    /**
     * @inheritDoc
     */
    protected function getDataGroupsForCapabilityCheck(): array
    {
        return ['immigration'];
    }
}
