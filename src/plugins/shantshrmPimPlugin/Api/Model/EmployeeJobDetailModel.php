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

namespace ShantsHRM\Pim\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Entity\Employee;

class EmployeeJobDetailModel implements Normalizable
{
    use ModelTrait;

    public function __construct(Employee $employee)
    {
        $this->setEntity($employee);
        $this->setFilters(
            [
                'empNumber',
                ['getDecorator', 'getJoinedDate'],
                ['getJobTitle', 'getId'],
                ['getJobTitle', 'getJobTitleName'],
                ['getJobTitle', 'isDeleted'],
                ['getJobTitle', 'getJobSpecificationAttachment', 'getId'],
                ['getJobTitle', 'getJobSpecificationAttachment', 'getFileName'],
                ['getEmpStatus', 'getId'],
                ['getEmpStatus', 'getName'],
                ['getJobCategory', 'getId'],
                ['getJobCategory', 'getName'],
                ['getSubDivision', 'getId'],
                ['getSubDivision', 'getName'],
                ['getSubDivision', 'getUnitId'],
                ['getDecorator', 'getLocation', 'getId'],
                ['getDecorator', 'getLocation', 'getName'],
                ['getEmployeeTerminationRecord', 'getId'],
                ['getEmployeeTerminationRecord', 'getDecorator', 'getDate'],
            ]
        );
        $this->setAttributeNames(
            [
                'empNumber',
                'joinedDate',
                ['jobTitle', 'id'],
                ['jobTitle', 'title'],
                ['jobTitle', 'isDeleted'],
                ['jobSpecificationAttachment', 'id'],
                ['jobSpecificationAttachment', 'filename'],
                ['empStatus', 'id'],
                ['empStatus', 'name'],
                ['jobCategory', 'id'],
                ['jobCategory', 'name'],
                ['subunit', 'id'],
                ['subunit', 'name'],
                ['subunit', 'unitId'],
                ['location', 'id'],
                ['location', 'name'],
                ['employeeTerminationRecord', 'id'],
                ['employeeTerminationRecord', 'date'],
            ]
        );
    }
}
