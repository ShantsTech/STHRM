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

namespace ShantsHRM\Admin\Api\Model;

use ShantsHRM\Admin\Traits\Service\WorkShiftServiceTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Entity\WorkShift;
use ShantsHRM\Pim\Api\Model\EmployeeModel;

/**
 * @OA\Schema(
 *     schema="Admin-WorkShiftDetailedModel",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="hoursPerDay", type="number"),
 *     @OA\Property(property="startTime", type="string"),
 *     @OA\Property(property="endTime", type="string"),
 *     @OA\Property(
 *         property="employees",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(ref="#/components/schemas/Pim-EmployeeModel"),
 *         )
 *     )
 * )
 */
class WorkShiftDetailedModel implements Normalizable
{
    use NormalizerServiceTrait;
    use DateTimeHelperTrait;
    use WorkShiftServiceTrait;

    private WorkShift $workShift;

    /**
     * @param WorkShift $workShift
     */
    public function __construct(WorkShift $workShift)
    {
        $this->workShift = $workShift;
    }

    /**
     * @return WorkShift
     */
    private function getWorkShift(): WorkShift
    {
        return $this->workShift;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $detailedWorkShift = $this->getWorkShift();
        $employees = $this->getNormalizerService()->normalizeArray(
            EmployeeModel::class,
            $this->getWorkShiftService()
                ->getWorkShiftDao()
                ->getEmployeeListByWorkShiftId($detailedWorkShift->getId())
        );
        return [
            'id' => $detailedWorkShift->getId(),
            'name' => $detailedWorkShift->getName(),
            'hoursPerDay' => $detailedWorkShift->getHoursPerDay(),
            'startTime' => $this->getDateTimeHelper()->formatDateTimeToTimeString(
                $detailedWorkShift->getStartTime()
            ),
            'endTime' => $this->getDateTimeHelper()->formatDateTimeToTimeString(
                $detailedWorkShift->getEndTime()
            ),
            'employees' => $employees
        ];
    }
}
