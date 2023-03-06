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

namespace ShantsHRM\Core\Authorization\UserRole;

use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\CandidateHistory;
use ShantsHRM\Entity\Interview;
use ShantsHRM\Entity\InterviewAttachment;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Entity\VacancyAttachment;
use ShantsHRM\Recruitment\Dto\CandidateActionHistory;
use ShantsHRM\Recruitment\Traits\Service\CandidateServiceTrait;
use ShantsHRM\Recruitment\Traits\Service\RecruitmentAttachmentServiceTrait;
use ShantsHRM\Recruitment\Traits\Service\VacancyServiceTrait;

class HiringManagerUserRole extends AbstractUserRole
{
    use VacancyServiceTrait;
    use CandidateServiceTrait;
    use RecruitmentAttachmentServiceTrait;

    /**
     * @inheritDoc
     */
    protected function getAccessibleIdsForEntity(string $entityType, array $requiredPermissions = []): array
    {
        switch ($entityType) {
            case Vacancy::class:
                return $this->getAccessibleVacancyIds($requiredPermissions);
            case VacancyAttachment::class:
                return $this->getAccessibleVacancyAttachmentIds($requiredPermissions);
            case Candidate::class:
                return $this->getAccessibleCandidateIds($requiredPermissions);
            case Interview::class:
                return $this->getAccessibleInterviewIds($requiredPermissions);
            case InterviewAttachment::class:
                return $this->getAccessibleInterviewAttachmentIds($requiredPermissions);
            case CandidateHistory::class:
                return $this->getAccessibleCandidateHistoryIds($requiredPermissions);
            case CandidateActionHistory::class:
                return $this->getAccessibleCandidateActionHistoryIds($requiredPermissions);
            default:
                return [];
        }
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleVacancyIds(array $requiredPermissions = []): array
    {
        return $this->getVacancyService()
            ->getVacancyDao()
            ->getVacancyIdListForHiringManager($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleVacancyAttachmentIds(array $requiredPermissions = []): array
    {
        return $this->getRecruitmentAttachmentService()
            ->getRecruitmentAttachmentDao()
            ->getVacancyAttachmentListForHiringManger($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleCandidateIds(array $requiredPermissions = []): array
    {
        return $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateIdListForHiringManger($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleInterviewIds(array $requiredPermissions = []): array
    {
        return $this->getCandidateService()
            ->getCandidateDao()
            ->getInterviewIdListForHiringManager($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleInterviewAttachmentIds(array $requiredPermissions = []): array
    {
        return $this->getRecruitmentAttachmentService()
            ->getRecruitmentAttachmentDao()
            ->getInterviewAttachmentListForHiringManger($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleCandidateHistoryIds(array $requiredPermissions = []): array
    {
        return $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateHistoryIdListForHiringManager($this->getEmployeeNumber());
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleCandidateActionHistoryIds(array $requiredPermissions = []): array
    {
        $candidateActionHistory = new CandidateActionHistory();
        return $candidateActionHistory->getAccessibleCandidateActionHistoryIds();
    }
}
