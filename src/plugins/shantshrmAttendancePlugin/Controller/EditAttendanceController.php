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
use ShantsHRM\Core\Controller\Common\DisabledModuleController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\AttendanceRecord;
use ShantsHRM\Framework\Http\Request;

class EditAttendanceController extends AbstractVueController
{
    use AttendanceServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->attributes->has('id')) {
            $attendanceRecordId = $request->attributes->getInt('id');
            $attendanceRecord = $this->getAttendanceService()
                ->getAttendanceDao()
                ->getAttendanceRecordById($attendanceRecordId);
            //no attendance record for the given id
            if (!$attendanceRecord instanceof AttendanceRecord) {
                throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
            }
            //check auth user's permission to update attendance record
            if (!$this->getAttendanceService()->isAuthUserAllowedToPerformTheEditActions($attendanceRecord)) {
                throw new RequestForwardableException(DisabledModuleController::class . '::handle');
            }
            $component = new Component('edit-attendance');
            $component->addProp(new Prop('attendance-id', Prop::TYPE_NUMBER, $attendanceRecordId));
        } else {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        $this->setComponent($component);
    }
}
