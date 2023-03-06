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

namespace ShantsHRM\Time\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Entity\Project;

class ProjectDetailedModel implements Normalizable
{
    use ModelTrait {
        ModelTrait::toArray as entityToArray;
    }

    public function __construct(Project $project)
    {
        $this->setEntity($project);
        $this->setFilters([
            'id',
            'name',
            'description',
            ['getCustomer', 'getId'],
            ['getCustomer', 'getName'],
            ['getCustomer', 'isDeleted'],
            ['isDeleted'],
        ]);

        $this->setAttributeNames([
            'id',
            'name',
            'description',
            ['customer', 'id'],
            ['customer', 'name'],
            ['customer', 'deleted'],
            'deleted',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $normalizedProject = $this->entityToArray();
        $normalizedProject['projectAdmins'] = [];
        /** @var Project $project */
        $project = $this->getEntity();
        foreach ($project->getProjectAdmins() as $projectAdmin) {
            $normalizedProjectAdmin = [];
            $normalizedProjectAdmin['empNumber'] = $projectAdmin->getEmpNumber();
            $normalizedProjectAdmin['lastName'] = $projectAdmin->getLastName();
            $normalizedProjectAdmin['firstName'] = $projectAdmin->getFirstName();
            $normalizedProjectAdmin['middleName'] = $projectAdmin->getMiddleName();
            $normalizedProjectAdmin['terminationId'] = $projectAdmin->getEmployeeTerminationRecord() ?
                $projectAdmin->getEmployeeTerminationRecord()->getId() : null;
            $normalizedProject['projectAdmins'][] = $normalizedProjectAdmin;
        }
        return $normalizedProject;
    }
}
