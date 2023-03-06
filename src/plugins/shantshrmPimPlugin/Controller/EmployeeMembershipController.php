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

use ShantsHRM\Admin\Service\MembershipService;
use ShantsHRM\Admin\Service\PayGradeService;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\EmployeeMembership;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Services;

class EmployeeMembershipController extends BaseViewEmployeeController
{
    use ServiceContainerTrait;

    /**
     * @var MembershipService|null
     */
    protected ?MembershipService $membershipService = null;

    /**
     * @return MembershipService
     */
    protected function getMembershipService(): MembershipService
    {
        if (!$this->membershipService instanceof MembershipService) {
            $this->membershipService = new MembershipService();
        }
        return $this->membershipService;
    }

    /**
     * @return PayGradeService
     */
    public function getPayGradeService(): PayGradeService
    {
        return $this->getContainer()->get(Services::PAY_GRADE_SERVICE);
    }

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $empNumber = $request->attributes->get('empNumber');
        if ($empNumber) {
            $component = new Component('employee-membership');
            $component->addProp(new Prop('emp-number', Prop::TYPE_NUMBER, $empNumber));
            $currencies = $this->getPayGradeService()->getCurrencyArray();
            $memberships = $this->getMembershipService()->getMembershipArray();
            $paidBy = [
                ["id" => EmployeeMembership::COMPANY, "label" => EmployeeMembership::COMPANY],
                ["id" => EmployeeMembership::INDIVIDUAL, "label" => EmployeeMembership::INDIVIDUAL]
            ];
            $component->addProp(new Prop('currencies', Prop::TYPE_ARRAY, $currencies));
            $component->addProp(new Prop('paid-by', Prop::TYPE_ARRAY, $paidBy));
            $component->addProp(new Prop('memberships', Prop::TYPE_ARRAY, $memberships));
            $this->setComponent($component);

            $this->setPermissionsForEmployee(
                [
                    'membership',
                    'membership_attachment',
                    'membership_custom_fields',
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
        return ['membership'];
    }
}
