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

namespace ShantsHRM\Recruitment\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Entity\CandidateHistory;

class CandidateHistoryDetailedModel implements Normalizable
{
    use ModelTrait;

    public function __construct(CandidateHistory $candidateHistory)
    {
        $this->setEntity($candidateHistory);
        $this->setFilters([
            'id',
            'action',
            ['getDecorator', 'getCandidateHistoryAction'],
            ['getCandidate', 'getId'],
            ['getCandidate', 'getFirstName'],
            ['getCandidate', 'getMiddleName'],
            ['getCandidate', 'getLastName'],
            ['getVacancy', 'getId'],
            ['getVacancy', 'getName'],
            ['getVacancy', 'getHiringManager', 'getEmpNumber'],
            ['getVacancy', 'getHiringManager', 'getFirstName'],
            ['getVacancy', 'getHiringManager', 'getMiddleName'],
            ['getVacancy', 'getHiringManager', 'getLastName'],
            ['getVacancy', 'getHiringManager', 'getEmployeeTerminationRecord', 'getId'],
            ['getPerformedBy', 'getEmpNumber'],
            ['getPerformedBy', 'getFirstName'],
            ['getPerformedBy', 'getMiddleName'],
            ['getPerformedBy', 'getLastName'],
            ['getPerformedBy', 'getEmployeeTerminationRecord', 'getId'],
            ['getInterview', 'getId'],
            ['getDecorator', 'getPerformedDate'],
            'note',
        ]);

        $this->setAttributeNames([
            'id',
            ['action', 'id'],
            ['action', 'label'],
            ['candidate', 'id'],
            ['candidate', 'firstName'],
            ['candidate', 'middleName'],
            ['candidate', 'lastName'],
            ['vacancy', 'id'],
            ['vacancy', 'name'],
            ['vacancy', 'hiringManger', 'empNumber'],
            ['vacancy', 'hiringManger', 'firstName'],
            ['vacancy', 'hiringManger', 'middleName'],
            ['vacancy', 'hiringManger', 'lastName'],
            ['vacancy', 'hiringManger', 'terminationId'],
            ['performedBy', 'empNumber'],
            ['performedBy', 'firstName'],
            ['performedBy', 'middleName'],
            ['performedBy', 'lastName'],
            ['performedBy', 'terminationId'],
            ['interview', 'id'],
            'performedDate',
            'note'
        ]);
    }
}
