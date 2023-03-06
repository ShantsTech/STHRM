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

namespace ShantsHRM\Performance\Controller;

use ShantsHRM\Core\Authorization\Controller\CapableViewController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\PerformanceTracker;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Performance\Traits\Service\PerformanceTrackerServiceTrait;

class EmployeeTrackerLogsController extends AbstractVueController implements CapableViewController
{
    use PerformanceTrackerServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $id = $request->attributes->getInt('id');
        $component = new Component('employee-tracker-logs');

        $tracker = $this->getPerformanceTrackerService()->getPerformanceTrackerDao()->getPerformanceTracker($id);
        if (!is_null($tracker)) {
            $component->addProp(new Prop('tracker-id', Prop::TYPE_NUMBER, $tracker->getId()));
            $component->addProp(new Prop('emp-number', Prop::TYPE_NUMBER, $tracker->getEmployee()->getEmpNumber()));
        }

        $this->setComponent($component);
    }

    /**
     * @inheritDoc
     */
    public function isCapable(Request $request): bool
    {
        $id = $request->attributes->getInt('id');
        $performanceTracker = $this->getPerformanceTrackerService()
            ->getPerformanceTrackerDao()
            ->getPerformanceTracker($id);
        if (is_null($performanceTracker) || !is_null($performanceTracker->getEmployee()->getPurgedAt())) {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        return $this->getUserRoleManager()->isEntityAccessible(PerformanceTracker::class, $id);
    }
}
