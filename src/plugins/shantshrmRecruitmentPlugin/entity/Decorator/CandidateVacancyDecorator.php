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

namespace ShantsHRM\Entity\Decorator;

use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\CandidateVacancy;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Recruitment\Service\CandidateService;

class CandidateVacancyDecorator
{
    use EntityManagerHelperTrait;

    protected CandidateVacancy $candidateVacancy;

    /**
     * @param CandidateVacancy $candidateVacancy
     */
    public function __construct(CandidateVacancy $candidateVacancy)
    {
        $this->candidateVacancy = $candidateVacancy;
    }

    /**
     * @param int $id
     */
    public function setVacancyById(int $id): void
    {
        $vacancy = $this->getReference(Vacancy::class, $id);
        $this->candidateVacancy->setVacancy($vacancy);
    }

    /**
     * @param int $id
     */
    public function setCandidateById(int $id): void
    {
        $candidate = $this->getReference(Candidate::class, $id);
        $this->candidateVacancy->setCandidate($candidate);
    }

    /**
     * @return array
     */
    public function getCandidateVacancyStatus(): array
    {
        $candidateVacancyStatus = $this->candidateVacancy->getStatus();
        return [
            'id' => array_flip(CandidateService::STATUS_MAP)[$candidateVacancyStatus],
            'label' => ucwords(strtolower($candidateVacancyStatus))
        ];
    }
}
