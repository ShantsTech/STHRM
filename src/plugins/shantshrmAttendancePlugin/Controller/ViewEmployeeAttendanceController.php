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

namespace ShantsHRM\Attendance\Controller;

use ShantsHRM\Attendance\Traits\Service\AttendanceServiceTrait;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\AttendanceRecord;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\WorkflowStateMachine;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;

class ViewEmployeeAttendanceController extends AbstractVueController
{
    use AuthUserTrait;
    use UserRoleManagerTrait;
    use EmployeeServiceTrait;
    use AttendanceServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->query->has('employeeId')) {
            $empNumber = $request->query->getInt('employeeId');
            if (!$this->getUserRoleManagerHelper()->isEmployeeAccessible($empNumber)) {
                throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
            }
            $component = new Component('view-employee-attendance-detailed');
            $component->addProp(
                new Prop(
                    'employee',
                    Prop::TYPE_OBJECT,
                    $this->getEmployeeService()->getEmployeeAsArray($empNumber)
                )
            );
            $loggedInUserEmpNumber = $this->getAuthUser()->getEmpNumber();
            $rolesToInclude = [];
            //check the configuration as ESS since Admin user is always allowed to delete self records
            if ($empNumber === $loggedInUserEmpNumber) {
                $rolesToInclude = ['ESS'];
            }
            //If edit/delete own attendance record, get the allowed actions list as an ESS user
            //since Admin is always allowed to edit/delete own record
            //If delete someone else's attendance record, get the allowed actions list as a Supervisor
            //Admin is always allowed to edit/delete others records
            $allowedWorkflowItems = $this->getUserRoleManager()->getAllowedActions(
                WorkflowStateMachine::FLOW_ATTENDANCE,
                AttendanceRecord::STATE_PUNCHED_IN,
                [],
                $rolesToInclude,
                [Employee::class => $empNumber]
            );
            if (in_array(WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, array_keys($allowedWorkflowItems))) {
                $component->addProp(new Prop('is-editable', Prop::TYPE_BOOLEAN, true));
            }
        } else {
            $component = new Component('view-employee-attendance-summary');
        }

        if ($request->query->has('date')) {
            $component->addProp(new Prop('date', Prop::TYPE_STRING, $request->query->get('date')));
        }
        $this->setComponent($component);
    }
}
