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
use ShantsHRM\Core\Traits\Controller\VueComponentPermissionTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Project;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Time\Traits\Service\ProjectServiceTrait;

class SaveProjectController extends AbstractVueController implements CapableViewController
{
    use VueComponentPermissionTrait;
    use UserRoleManagerTrait;
    use ProjectServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->attributes->has('id')) {
            $component = new Component('project-edit');
            $unselectableActivityIds = $this->getProjectService()
                ->getProjectDao()
                ->getActivityIdsOfProjectInTimesheetItems($request->attributes->getInt('id'));
            $component->addProp(new Prop('unselectable-ids', Prop::TYPE_ARRAY, $unselectableActivityIds));
            $component->addProp(new Prop('project-id', Prop::TYPE_NUMBER, $request->attributes->getInt('id')));
        } else {
            $component = new Component('project-save');
        }
        $this->setComponent($component);
        $this->setPermissions(['time_projects', 'time_project_activities']);
    }

    /**
     * @inheritDoc
     */
    public function isCapable(Request $request): bool
    {
        if ($request->attributes->has('id')) {
            $id = $request->attributes->get('id');
            if (!$this->getProjectService()->getProjectDao()->getProjectById($id) instanceof Project) {
                throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
            }
            return $this->getUserRoleManager()->isEntityAccessible(Project::class, $id);
        }
        return true;
    }
}
