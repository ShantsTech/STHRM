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
use ShantsHRM\Entity\EmpUsTaxExemption;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Services;

class EmpUsTaxExemptionController extends BaseViewEmployeeController
{
    use ServiceContainerTrait;

    /**
     * @return CountryService
     */
    public function getCountryService(): CountryService
    {
        return $this->getContainer()->get(Services::COUNTRY_SERVICE);
    }

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $empNumber = $request->attributes->get('empNumber');
        if ($empNumber) {
            $component = new Component('employee-tax-exemption');
            $component->addProp(new Prop('emp-number', Prop::TYPE_NUMBER, $empNumber));
            $provinces = $this->getCountryService()->getProvinceArray();
            $status = [
                ["id" => EmpUsTaxExemption::STATUS_SINGLE, "label" => EmpUsTaxExemption::SINGLE],
                ["id" => EmpUsTaxExemption::STATUS_MARRIED, "label" => EmpUsTaxExemption::MARRIED],
                [
                    "id" => EmpUsTaxExemption::STATUS_NON_RESIDENT_ALIEN,
                    "label" => EmpUsTaxExemption::NON_RESIDENT_ALIEN
                ],
                ["id" => EmpUsTaxExemption::STATUS_NOT_APPLICABLE, "label" => EmpUsTaxExemption::NOT_APPLICABLE]
            ];
            $component->addProp(new Prop('provinces', Prop::TYPE_ARRAY, $provinces));
            $component->addProp(new Prop('statuses', Prop::TYPE_ARRAY, $status));
            $this->setComponent($component);

            $this->setPermissionsForEmployee(
                [
                    'tax_exemptions',
                    'tax_attachment',
                    'tax_custom_fields',
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
        return ['tax_exemptions'];
    }
}
