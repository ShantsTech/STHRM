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
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Entity\AttendanceRecord;
use ShantsHRM\Core\Vue\Prop;

class PunchOutController extends AbstractVueController
{
    use AttendanceServiceTrait;
    use AuthUserTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        // check if previous record is a punch in.
        $attendanceRecord = $this->getAttendanceService()
            ->getAttendanceDao()
            ->getLastPunchRecordByEmployeeNumberAndActionableList(
                $this->getAuthUser()->getEmpNumber(),
                [AttendanceRecord::STATE_PUNCHED_IN]
            );

        //previous record is not present redirect to punch in
        if (!$attendanceRecord instanceof AttendanceRecord) {
            $this->setResponse($this->redirect('/attendance/punchIn'));
            return;
        }

        $component = new Component('attendance-punch-out');
        $component->addProp(new Prop('attendance-record-id', Prop::TYPE_NUMBER, $attendanceRecord->getId()));

        //if configuration enabled, editable is true
        if ($this->getAttendanceService()->canUserChangeCurrentTime()) {
            $component->addProp(new Prop('is-editable', Prop::TYPE_BOOLEAN, true));
        }
        $this->setComponent($component);
    }
}
