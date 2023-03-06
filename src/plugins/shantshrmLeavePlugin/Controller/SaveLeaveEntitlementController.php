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

namespace ShantsHRM\Leave\Controller;

use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Entity\LeaveEntitlement;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Admin\Service\LocationService;
use ShantsHRM\Admin\Service\CompanyStructureService;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Leave\Traits\Service\LeaveEntitlementServiceTrait;
use ShantsHRM\Leave\Traits\Service\LeavePeriodServiceTrait;

class SaveLeaveEntitlementController extends AbstractVueController
{
    use LeavePeriodServiceTrait;
    use LeaveEntitlementServiceTrait;
    use UserRoleManagerTrait;

    protected ?CompanyStructureService $companyStructureService = null;
    protected ?LocationService $locationService = null;

    /**
     * @return LocationService
     */
    protected function getLocationService(): LocationService
    {
        if (!$this->locationService instanceof LocationService) {
            $this->locationService = new LocationService();
        }
        return $this->locationService;
    }

    /**
     * @return CompanyStructureService
     */
    protected function getCompanyStructureService(): CompanyStructureService
    {
        if (!$this->companyStructureService instanceof CompanyStructureService) {
            $this->companyStructureService = new CompanyStructureService();
        }
        return $this->companyStructureService;
    }

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->attributes->has('id')) {
            $id = $request->attributes->getInt('id');
            $component = new Component('leave-edit-entitlement');
            $component->addProp(new Prop('entitlement-id', Prop::TYPE_NUMBER, $id));
            $leaveEntitlementRecord = $this->getLeaveEntitlementService()
                ->getLeaveEntitlementDao()
                ->getLeaveEntitlement($id);
            if (!$leaveEntitlementRecord instanceof LeaveEntitlement ||
                !$this->getUserRoleManagerHelper()->isEmployeeAccessible(
                    $leaveEntitlementRecord->getEmployee()->getEmpNumber()
                )) {
                throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
            }
        } else {
            $component = new Component('leave-add-entitlement');
        }

        $subunits = $this->getCompanyStructureService()->getSubunitArray();
        $component->addProp(new Prop('subunits', Prop::TYPE_ARRAY, $subunits));

        $locations = $this->getLocationService()->getAccessibleLocationsArray();
        $component->addProp(new Prop('locations', Prop::TYPE_ARRAY, $locations));

        $leavePeriod = $this->getLeavePeriodService()->getNormalizedCurrentLeavePeriod();
        $leavePeriod = [
            "id" => $leavePeriod['startDate'] . "_" . $leavePeriod['endDate'],
            "label" => $leavePeriod['startDate'] . " - " . $leavePeriod['endDate'],
            "startDate" => $leavePeriod['startDate'],
            "endDate" => $leavePeriod['endDate'],
        ];

        $component->addProp(new Prop('leave-period', Prop::TYPE_OBJECT, $leavePeriod));

        $this->setComponent($component);
    }
}
