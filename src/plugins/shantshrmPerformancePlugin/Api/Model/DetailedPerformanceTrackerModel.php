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

namespace ShantsHRM\Performance\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Entity\EmployeeTerminationRecord;
use ShantsHRM\Entity\PerformanceTracker;
use ShantsHRM\Performance\Traits\Service\PerformanceTrackerServiceTrait;

/**
 * @OA\Schema(
 *     schema="Performance-DetailedPerformanceTrackerModel",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="trackerName", type="string"),
 *     @OA\Property(property="addedDate", type="number"),
 *     @OA\Property(property="modifiedDate", type="number"),
 *     @OA\Property(property="status", type="integer"),
 *     @OA\Property(
 *         property="employee",
 *         type="object",
 *         @OA\Property(property="empNumber", type="integer"),
 *         @OA\Property(property="firstName", type="string"),
 *         @OA\Property(property="lastName", type="string"),
 *         @OA\Property(property="middleName", type="string"),
 *         @OA\Property(property="employeeId", type="string"),
 *         @OA\Property(property="terminationId", type="integer"),
 *     ),
 *     @OA\Property(
 *         property="reviewers",
 *         type="array",
 *         @OA\Items(
 *             @OA\Property(ref="#/components/schemas/Performance-PerformanceTrackReviewerModel"),
 *         )
 *     ),
 *     @OA\Property(property="ownerEditable", type="boolean"),
 * )
 */
class DetailedPerformanceTrackerModel implements Normalizable
{
    use NormalizerServiceTrait;
    use PerformanceTrackerServiceTrait;

    /**
     * @var PerformanceTracker
     */
    private PerformanceTracker $performanceTracker;

    /**
     * @param PerformanceTracker $performanceTracker
     */
    public function __construct(PerformanceTracker $performanceTracker)
    {
        $this->performanceTracker = $performanceTracker;
    }

    public function toArray(): array
    {
        $reviewers = $this->getNormalizerService()->normalizeArray(
            PerformanceTrackReviewerModel::class,
            $this->getPerformanceTrackerService()
                ->getPerformanceTrackerDao()
                ->getReviewerListByTrackerId($this->performanceTracker->getId())
        );

        $trackerOwnerEditable = $this->getPerformanceTrackerService()->getPerformanceTrackerDao()
            ->isTrackerOwnerEditable($this->performanceTracker->getId());
        $terminationRecord = $this->performanceTracker->getEmployee()->getEmployeeTerminationRecord();
        return [
            'id' => $this->performanceTracker->getId(),
            'trackerName' => $this->performanceTracker->getTrackerName(),
            'addedDate' => $this->performanceTracker->getDecorator()->getAddedDate(),
            'modifiedDate' => $this->performanceTracker->getDecorator()->getModifiedDate(),
            'status' =>$this->performanceTracker->getStatus(),
            'employee' => [
                'empNumber' => $this->performanceTracker->getEmployee()->getEmpNumber(),
                'lastName' => $this->performanceTracker->getEmployee()->getLastName(),
                'firstName' => $this->performanceTracker->getEmployee()->getFirstName(),
                'middleName' => $this->performanceTracker->getEmployee()->getMiddleName(),
                'employeeId' => $this->performanceTracker->getEmployee()->getEmployeeId(),
                'terminationId' => $terminationRecord instanceof EmployeeTerminationRecord ? $terminationRecord->getId() : null,
            ],
            'reviewers' => $reviewers,
            'ownerEditable' =>$trackerOwnerEditable,
        ];
    }
}
