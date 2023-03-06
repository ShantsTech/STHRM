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
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\JobTitle;
use ShantsHRM\Entity\Reviewer;
use ShantsHRM\Entity\PerformanceReview;
use ShantsHRM\Entity\ReviewerGroup;

class PerformanceReviewDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    protected PerformanceReview $performanceReview;

    /**
     * @param PerformanceReview $performanceReview
     */
    public function __construct(PerformanceReview $performanceReview)
    {
        $this->performanceReview = $performanceReview;
    }

    /**
     * @return PerformanceReview
     */
    protected function getPerformanceReview(): PerformanceReview
    {
        return $this->performanceReview;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getPerformanceReview()->setEmployee($employee);
    }

    /**
     * @param int $id
     */
    public function setJobTitleById(int $id): void
    {
        $jobTitle = $this->getReference(JobTitle::class, $id);
        $this->getPerformanceReview()->setJobTitle($jobTitle);
    }

    /**
     * @return Reviewer
     */
    public function getSupervisorReviewer(): Reviewer
    {
        $reviewers = [...$this->performanceReview->getReviewers()];
        $supervisorArray = array_filter($reviewers, function ($reviewer) {
            /** @var Reviewer $reviewer */
            return $reviewer->getGroup()->getName() === ReviewerGroup::REVIEWER_GROUP_SUPERVISOR;
        });
        return array_values($supervisorArray)[0];
    }

    /**
     * @return Reviewer
     */
    public function getEmployeeReviewer(): Reviewer
    {
        $reviewers = [...$this->performanceReview->getReviewers()];
        $employeeArray = array_filter($reviewers, function ($reviewer) {
            /** @var Reviewer $reviewer */
            return $reviewer->getGroup()->getName() === ReviewerGroup::REVIEWER_GROUP_EMPLOYEE;
        });
        return array_values($employeeArray)[0];
    }

    /**
     * @return string|null
     */
    public function getDueDate(): ?string
    {
        return $this->getDateTimeHelper()->formatDate($this->getPerformanceReview()->getDueDate());
    }

    /**
     * @return string|null
     */
    public function getReviewPeriodStart(): ?string
    {
        return $this->getDateTimeHelper()->formatDate($this->getPerformanceReview()->getReviewPeriodStart());
    }

    /**
     * @return string|null
     */
    public function getReviewPeriodEnd(): ?string
    {
        return $this->getDateTimeHelper()->formatDate($this->getPerformanceReview()->getReviewPeriodEnd());
    }

    /**
     * @return string|null
     */
    public function getCompletedDate(): ?string
    {
        return $this->getDateTimeHelper()->formatDate($this->getPerformanceReview()->getCompletedDate());
    }

    /**
     * @return string
     */
    public function getStatusName(): string
    {
        $statusId = $this->getPerformanceReview()->getStatusId();
        switch ($statusId) {
            case PerformanceReview::STATUS_INACTIVE:
                return 'Inactive';
            case PerformanceReview::STATUS_ACTIVATED:
                return 'Activated';
            case PerformanceReview::STATUS_IN_PROGRESS:
                return 'In Progress';
            case PerformanceReview::STATUS_COMPLETED:
                return 'Completed';
            default:
                return '';
        }
    }
}
