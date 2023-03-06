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

namespace ShantsHRM\Time\Controller;

use ShantsHRM\Core\Authorization\Controller\CapableViewController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Timesheet;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Time\Traits\Service\TimesheetServiceTrait;

class EditTimesheetController extends AbstractVueController implements CapableViewController
{
    use AuthUserTrait;
    use TimesheetServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        // TODO: show 404 if no id
        if ($request->attributes->has('id')) {
            $timesheetId = $request->attributes->getInt('id');
            $component = new Component('edit-timesheet');
            $component->addProp(new Prop('timesheet-id', Prop::TYPE_NUMBER, $timesheetId));

            $timesheet = $this->getTimesheetService()->getTimesheetDao()->getTimesheetById($timesheetId);
            $timesheetOwnerEmpNumber = $timesheet->getEmployee()->getEmpNumber();
            $currentUserEmpNumber = $this->getAuthUser()->getEmpNumber();
            if ($timesheetOwnerEmpNumber === $currentUserEmpNumber) {
                $component->addProp(new Prop('my-timesheet', Prop::TYPE_BOOLEAN, true));
            }
        }

        $this->setComponent($component);
    }

    /**
     * @inheritDoc
     */
    public function isCapable(Request $request): bool
    {
        if ($request->attributes->has('id')) {
            $timesheet = $this->getTimesheetService()
                ->getTimesheetDao()
                ->getTimesheetById($request->attributes->getInt('id'));
            if ($timesheet instanceof Timesheet) {
                if ($this->getUserRoleManagerHelper()->isSelfByEmpNumber($timesheet->getEmployee()->getEmpNumber())
                    && $timesheet->getState() === 'APPROVED') {
                    return false;
                }
                return $this->getUserRoleManagerHelper()
                    ->isEmployeeAccessible($timesheet->getEmployee()->getEmpNumber());
            }
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        return true;
    }
}
