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

namespace ShantsHRM\Dashboard\Dto\ActionSummary;

use ShantsHRM\Dashboard\Dto\ActionableReviewSearchFilterParams;
use ShantsHRM\Dashboard\Traits\Service\EmployeeActionSummaryServiceTrait;
use ShantsHRM\Entity\Reviewer;
use ShantsHRM\Entity\WorkflowStateMachine;

class PendingSelfReviewSummary implements ActionSummary
{
    use EmployeeActionSummaryServiceTrait;

    /**
     * @var ActionableReviewSearchFilterParams
     */
    private ActionableReviewSearchFilterParams $actionableReviewSearchFilterParams;

    /**
     * @param int $empNumber
     */
    public function __construct(int $empNumber)
    {
        $actionableReviewSearchFilterParams = new ActionableReviewSearchFilterParams();
        $actionableReviewSearchFilterParams->setEmpNumber($empNumber);
        $actionableReviewSearchFilterParams->setActionableStatuses(
            [
                WorkflowStateMachine::REVIEW_ACTIVATE,
                WorkflowStateMachine::REVIEW_IN_PROGRESS_SAVE
            ]
        );
        $actionableReviewSearchFilterParams->setSelfReviewStatuses(
            [
                Reviewer::STATUS_ACTIVATED,
                Reviewer::STATUS_IN_PROGRESS
            ]
        );
        $this->actionableReviewSearchFilterParams = $actionableReviewSearchFilterParams;
    }

    /**
     * @inheritDoc
     */
    public function getGroupId(): int
    {
        return 4;
    }

    /**
     * @inheritDoc
     */
    public function getGroup(): string
    {
        return 'Pending Self Reviews';
    }

    /**
     * @inheritDoc
     */
    public function getPendingActionCount(): int
    {
        return $this->getEmployeeActionSummaryService()
            ->getEmployeeActionSummaryDao()
            ->getPendingSelfReviewCount($this->actionableReviewSearchFilterParams);
    }
}
