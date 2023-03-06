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

use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\WorkflowStateMachine;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Recruitment\Traits\Service\CandidateServiceTrait;

class CandidateActionController extends AbstractVueController
{
    use CandidateServiceTrait;
    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $candidateId = $request->query->getInt('candidateId');
        $actionId = $request->query->getInt('selectedAction');
        $candidate = $this->getCandidateService()->getCandidateDao()->getCandidateById($candidateId);
        if (!$candidate instanceof Candidate) {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }

        switch ($actionId) {
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHORTLIST:
                $component = new Component('shortlist-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_REJECT:
                $component = new Component('reject-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHEDULE_INTERVIEW:
                $component = new Component('interview-schedule-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_PASSED:
                $component = new Component('interview-passed-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_FAILED:
                $component = new Component('interview-failed-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_OFFER_JOB:
                $component = new Component('offer-job-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_DECLINE_OFFER:
                $component = new Component('offer-decline-action');
                break;
            case WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_HIRE:
                $component = new Component('hire-action');
                break;
            default:
                throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }

        if (
            $actionId === WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_PASSED
            || $actionId === WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_FAILED
        ) {
            $interviewIds = $this->getCandidateService()->getCandidateDao()->getInterviewIdsByCandidateId($candidateId);
            $component->addProp(new Prop('interview-id', Prop::TYPE_NUMBER, $interviewIds[0]));
        }

        $component->addProp(new Prop('candidate-id', Prop::TYPE_NUMBER, $candidateId));
        $this->setComponent($component);
    }
}
