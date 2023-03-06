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

use ShantsHRM\Admin\Service\LocationService;
use ShantsHRM\Buzz\Traits\Service\BuzzServiceTrait;
use ShantsHRM\Dashboard\Traits\Service\QuickLaunchServiceTrait;
use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\CandidateHistory;
use ShantsHRM\Entity\Customer;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\Interview;
use ShantsHRM\Entity\InterviewAttachment;
use ShantsHRM\Entity\Location;
use ShantsHRM\Entity\PerformanceReview;
use ShantsHRM\Entity\PerformanceTracker;
use ShantsHRM\Entity\PerformanceTrackerLog;
use ShantsHRM\Entity\Project;
use ShantsHRM\Entity\User;
use ShantsHRM\Entity\UserRole;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Entity\VacancyAttachment;
use ShantsHRM\Performance\Traits\Service\PerformanceReviewServiceTrait;
use ShantsHRM\Performance\Traits\Service\PerformanceTrackerLogServiceTrait;
use ShantsHRM\Performance\Traits\Service\PerformanceTrackerServiceTrait;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;
use ShantsHRM\Recruitment\Dto\CandidateActionHistory;
use ShantsHRM\Recruitment\Traits\Service\CandidateServiceTrait;
use ShantsHRM\Recruitment\Traits\Service\RecruitmentAttachmentServiceTrait;
use ShantsHRM\Recruitment\Traits\Service\VacancyServiceTrait;
use ShantsHRM\Time\Traits\Service\CustomerServiceTrait;
use ShantsHRM\Time\Traits\Service\ProjectServiceTrait;

class AdminUserRole extends AbstractUserRole
{
    use EmployeeServiceTrait;
    use ProjectServiceTrait;
    use CustomerServiceTrait;
    use PerformanceTrackerServiceTrait;
    use PerformanceReviewServiceTrait;
    use PerformanceTrackerLogServiceTrait;
    use CandidateServiceTrait;
    use RecruitmentAttachmentServiceTrait;
    use VacancyServiceTrait;
    use QuickLaunchServiceTrait;
    use BuzzServiceTrait;

    protected ?LocationService $locationService = null;

    /**
     * @return LocationService
     */
    protected function getLocationService(): LocationService
    {
        if (!$this->locationService instanceof LocationService) {
            $this->locationService = new LocationService();
        }
        return $this->locationService;
    }

    /**
     * @inheritDoc
     */
    protected function getAccessibleIdsForEntity(string $entityType, array $requiredPermissions = []): array
    {
        switch ($entityType) {
            case Employee::class:
                return $this->getAccessibleEmployeeIds($requiredPermissions);
            case User::class:
                return $this->getAccessibleSystemUserIds($requiredPermissions);
            case UserRole::class:
                return $this->getAccessibleUserRoleIds($requiredPermissions);
            case Location::class:
                return $this->getAccessibleLocationIds($requiredPermissions);
            case Project::class:
                return $this->getAccessibleProjectIds($requiredPermissions);
            case Customer::class:
                return $this->getAccessibleCustomerIds($requiredPermissions);
            case Vacancy::class:
                return $this->getAccessibleVacancyIds($requiredPermissions);
            case VacancyAttachment::class:
                return $this->getAccessibleVacancyAttachmentIds();
            case PerformanceTracker::class:
                return $this->getAccessibleTrackerIds($requiredPermissions);
            case PerformanceReview::class:
                return $this->getAccessibleReviewIds($requiredPermissions);
            case PerformanceTrackerLog::class:
                return $this->getAccessibleTrackerLogIds($requiredPermissions);
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
    protected function getAccessibleEmployeeIds(array $requiredPermissions = []): array
    {
        return $this->getEmployeeService()->getEmployeeDao()->getEmpNumberList(false);
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleLocationIds(array $requiredPermissions = []): array
    {
        return $this->getLocationService()->getLocationDao()->getLocationsIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleSystemUserIds(array $requiredPermissions = []): array
    {
        return $this->getUserService()
            ->geUserDao()
            ->getSystemUserIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleUserRoleIds(array $requiredPermissions = []): array
    {
        $userRoles = $this->getUserService()
            ->geUserDao()
            ->getAssignableUserRoles();

        $ids = [];

        foreach ($userRoles as $role) {
            $ids[] = $role->getId();
        }

        return $ids;
    }

    /**
     * @param array $entities
     * @return Employee[]
     */
    public function getEmployeesWithRole(array $entities = []): array
    {
        return $this->getUserService()->getEmployeesByUserRole($this->roleName);
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleProjectIds(array $requiredPermissions = []): array
    {
        /**
         * @return int[]
         */
        return $this->getProjectService()
            ->getProjectDao()
            ->getProjectIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleCustomerIds(array $requiredPermissions): array
    {
        return $this->getCustomerService()
            ->getCustomerDao()
            ->getCustomerIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleVacancyIds(array $requiredPermissions = []): array
    {
        return $this->getVacancyService()->getVacancyDao()->getVacancyIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleTrackerIds(array $requiredPermissions = []): array
    {
        return $this->getPerformanceTrackerService()
            ->getPerformanceTrackerDao()
            ->getPerformanceTrackerIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleReviewIds(array $requiredPermissions = []): array
    {
        return $this->getPerformanceReviewService()
            ->getPerformanceReviewDao()
            ->getReviewIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleTrackerLogIds(array $requiredPermissions = []): array
    {
        return $this->getPerformanceTrackerLogService()
            ->getPerformanceTrackerLogDao()
            ->getPerformanceTrackerLogsIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleCandidateIds(array $requiredPermissions = []): array
    {
        return $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleInterviewIds(array $requiredPermissions = []): array
    {
        return $this->getCandidateService()
            ->getCandidateDao()
            ->getInterviewIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    protected function getAccessibleInterviewAttachmentIds(array $requiredPermissions = []): array
    {
        return $this->getRecruitmentAttachmentService()
            ->getRecruitmentAttachmentDao()
            ->getInterviewAttachmentIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleVacancyAttachmentIds(array $requiredPermissions = []): array
    {
        return $this->getRecruitmentAttachmentService()
            ->getRecruitmentAttachmentDao()
            ->getVacancyAttachmentIdList();
    }

    /**
     * @param array $requiredPermissions
     * @return int[]
     */
    private function getAccessibleCandidateHistoryIds(array $requiredPermissions = []): array
    {
        return $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateHistoryIdList();
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

    /**
     * @inheritDoc
     */
    public function getAccessibleQuickLaunchList(array $requiredPermissions): array
    {
        return $this->getQuickLaunchService()
            ->getQuickLaunchDao()
            ->getQuickLaunchList();
    }
}
