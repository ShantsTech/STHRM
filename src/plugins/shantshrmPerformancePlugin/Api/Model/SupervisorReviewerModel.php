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

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Entity\Reviewer;

/**
 * @OA\Schema(
 *     schema="Performance-SupervisorReviewerModel",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(
 *         property="employee",
 *         type="object",
 *         @OA\Property(property="empNumber", type="integer"),
 *         @OA\Property(property="lastName", type="string"),
 *         @OA\Property(property="firstName", type="string"),
 *         @OA\Property(property="middleName", type="string"),
 *         @OA\Property(property="employeeId", type="string"),
 *         @OA\Property(property="terminationId", type="integer"),
 *         @OA\Property(
 *             property="jobTitle",
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="deleted", type="boolean"),
 *         ),
 *     ),
 *     @OA\Property(property="status", type="integer"),
 * )
 */
class SupervisorReviewerModel implements Normalizable
{
    use ModelTrait;

    public function __construct(Reviewer $reviewer)
    {
        $this->setEntity($reviewer);
        $this->setFilters(
            [
                'id',
                ['getEmployee', 'getEmpNumber'],
                ['getEmployee', 'getLastName'],
                ['getEmployee', 'getFirstName'],
                ['getEmployee', 'getMiddleName'],
                ['getEmployee', 'getEmployeeId'],
                ['getEmployee', 'getEmployeeTerminationRecord', 'getId'],
                ['getEmployee', 'getJobTitle', 'getId'],
                ['getEmployee', 'getJobTitle', 'getJobTitleName'],
                ['getEmployee','getJobTitle', 'isDeleted'],
                'status'
            ]
        );
        $this->setAttributeNames(
            [
                'id',
                ['employee','empNumber'],
                ['employee','lastName'],
                ['employee','firstName'],
                ['employee','middleName'],
                ['employee','employeeId'],
                ['employee','terminationId'],
                ['employee','jobTitle','id'],
                ['employee','jobTitle','name'],
                ['employee','jobTitle', 'deleted'],
                'status'
            ]
        );
    }
}
