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

namespace ShantsHRM\Recruitment\Controller;

use ShantsHRM\Core\Authorization\Controller\CapableViewController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\CandidateHistory;
use ShantsHRM\Entity\Interview;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Recruitment\Dto\CandidateActionHistory;
use ShantsHRM\Recruitment\Traits\Service\CandidateServiceTrait;

class WorkflowActionHistoryController extends AbstractVueController implements CapableViewController
{
    use UserRoleManagerTrait;
    use CandidateServiceTrait;
    use AuthUserTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('view-action-history');
        $candidateId = $request->attributes->getInt('candidateId');
        $historyId = $request->attributes->getInt('historyId');

        $candidateHistory = $this->getCandidateService()
            ->getCandidateDao()->
            getCandidateHistoryRecordByCandidateIdAndHistoryId($candidateId, $historyId);

        if ($candidateHistory instanceof CandidateHistory && $candidateHistory->getInterview() instanceof Interview) {
            $rolesToExclude = [];
            $hiringManagerEmpNumber = $candidateHistory->getVacancy()->getHiringManager()->getEmpNumber();
            if ($hiringManagerEmpNumber !== $this->getAuthUser()->getEmpNumber()) {
                $rolesToExclude = ['HiringManager', 'Interviewer'];
            }
            $editable = $this->getUserRoleManager()->isEntityAccessible(
                Candidate::class,
                $candidateId,
                null,
                $rolesToExclude
            );
            $component->addProp(new Prop('editable', Prop::TYPE_BOOLEAN, $editable));
        }

        $component->addProp(new Prop('candidate-id', Prop::TYPE_NUMBER, $candidateId));
        $component->addProp(new Prop('history-id', Prop::TYPE_NUMBER, $historyId));
        $this->setComponent($component);
    }

    public function isCapable(
        Request $request
    ): bool {
        if ($request->attributes->has('candidateId') && $request->attributes->has('historyId')) {
            $candidateId = $request->attributes->getInt('candidateId');
            $historyId = $request->attributes->getInt('historyId');

            $candidateHistory = $this->getCandidateService()
                ->getCandidateDao()
                ->getCandidateHistoryRecordByCandidateIdAndHistoryId($candidateId, $historyId);
            if (!$candidateHistory instanceof CandidateHistory) {
                throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
            }
            if (!$this->getUserRoleManager()->isEntityAccessible(Candidate::class, $candidateId)) {
                return false;
            }
            if (!$this->getUserRoleManager()->isEntityAccessible(CandidateHistory::class, $historyId)) {
                return false;
            }
            if ($candidateHistory->getVacancy() instanceof Vacancy) {
                $rolesToExclude = [];
                $hiringManagerEmpNumber = $candidateHistory->getVacancy()->getHiringManager()->getEmpNumber();
                if ($hiringManagerEmpNumber !== $this->getAuthUser()->getEmpNumber()) {
                    $rolesToExclude = ['HiringManager'];
                }
                $accessibleActionHistoryIds = $this->getUserRoleManager()->getAccessibleEntityIds(
                    CandidateActionHistory::class,
                    null,
                    null,
                    $rolesToExclude
                );
                if (!in_array($candidateHistory->getAction(), $accessibleActionHistoryIds)) {
                    return false;
                }
                $currentVacancyId = $this->getCandidateService()
                    ->getCandidateDao()
                    ->getCurrentVacancyIdByCandidateId($candidateId);
                if ($currentVacancyId != $candidateHistory->getVacancy()->getId()) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
