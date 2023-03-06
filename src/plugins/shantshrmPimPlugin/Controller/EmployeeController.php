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

use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Helper\VueControllerHelper;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\WorkflowStateMachine;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;

class EmployeeController extends AbstractVueController
{
    use UserRoleManagerTrait;
    use EmployeeServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('employee-list');
        $component->addProp(
            new Prop(
                'unselectable-emp-numbers',
                Prop::TYPE_ARRAY,
                $this->getEmployeeService()->getUndeletableEmpNumbers()
            )
        );
        $this->setComponent($component);

        $allowedToDeleteActive = $this->getUserRoleManager()->isActionAllowed(
            WorkflowStateMachine::FLOW_EMPLOYEE,
            Employee::STATE_ACTIVE,
            WorkflowStateMachine::EMPLOYEE_ACTION_DELETE_ACTIVE
        );
        $allowedToDeleteTerminated = $this->getUserRoleManager()->isActionAllowed(
            WorkflowStateMachine::FLOW_EMPLOYEE,
            Employee::STATE_TERMINATED,
            WorkflowStateMachine::EMPLOYEE_ACTION_DELETE_TERMINATED
        );
        $permissionsArray['employee_list'] = [
            'canRead' => true,
            'canCreate' => $this->getUserRoleManager()->isActionAllowed(
                WorkflowStateMachine::FLOW_EMPLOYEE,
                Employee::STATE_NOT_EXIST,
                WorkflowStateMachine::EMPLOYEE_ACTION_ADD
            ),
            'canUpdate' => true,
            'canDelete' => $allowedToDeleteActive || $allowedToDeleteTerminated,
        ];
        $this->getContext()->set(
            VueControllerHelper::PERMISSIONS,
            $permissionsArray
        );
    }
}
