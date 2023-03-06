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
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Entity\AttendanceRecord;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;

class EmployeePunchInOutController extends AbstractVueController
{
    use AttendanceServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->query->has('employeeId')) {
            $empNumber = $request->query->getInt('employeeId');
            // check if previous record is a punch in.
            $attendanceRecord = $this->getAttendanceService()
                ->getAttendanceDao()
                ->getLastPunchRecordByEmployeeNumberAndActionableList(
                    $empNumber,
                    [AttendanceRecord::STATE_PUNCHED_IN]
                );

            //previous record is not present redirect to punch in
            if (!$attendanceRecord instanceof AttendanceRecord) {
                $component = new Component('attendance-punch-in');
            } else {
                $component = new Component('attendance-punch-out');
            }

            $component->addProp(new Prop('is-editable', Prop::TYPE_BOOLEAN, true));
            $component->addProp(new Prop('employee-id', Prop::TYPE_NUMBER, $empNumber));
            $component->addProp(new Prop('is-timezone-editable', Prop::TYPE_BOOLEAN, true));

            if ($attendanceRecord) {
                $component->addProp(new Prop('attendance-record-id', Prop::TYPE_NUMBER, $attendanceRecord->getId()));
            }

            if ($request->query->has('date')) {
                $component->addProp(new Prop('date', Prop::TYPE_STRING, $request->query->get('date')));
            }
        } else {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        $this->setComponent($component);
    }
}
