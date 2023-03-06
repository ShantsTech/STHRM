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

namespace ShantsHRM\Recruitment\Api;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CrudEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Entity\Candidate;
use ShantsHRM\Entity\CandidateHistory;
use ShantsHRM\Entity\CandidateVacancy;
use ShantsHRM\Entity\WorkflowStateMachine;
use ShantsHRM\Recruitment\Api\Model\CandidateHistoryDetailedModel;
use ShantsHRM\Recruitment\Api\Model\CandidateHistoryListModel;
use ShantsHRM\Recruitment\Dto\CandidateActionHistory;
use ShantsHRM\Recruitment\Dto\CandidateHistorySearchFilterParams;
use ShantsHRM\Recruitment\Service\CandidateService;
use ShantsHRM\Recruitment\Traits\Service\CandidateServiceTrait;

class CandidateHistoryAPI extends Endpoint implements CrudEndpoint
{
    use CandidateServiceTrait;
    use UserRoleManagerTrait;
    use AuthUserTrait;

    public const PARAMETER_CANDIDATE_ID = 'candidateId';
    public const PARAMETER_HISTORY_ID = 'historyId';
    public const PARAMETER_NOTE = 'note';

    public const PARAMETER_RULE_NOTE_MAX_LENGTH = 2000;

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $candidateHistorySearchFilterParams = new CandidateHistorySearchFilterParams();
        $this->setSortingAndPaginationParams($candidateHistorySearchFilterParams);
        $candidateHistorySearchFilterParams->setCandidateId($this->getCandidateId());
        $candidateVacancy = $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateVacancyByCandidateId($this->getCandidateId());
        $rolesToExclude = [];
        if ($candidateVacancy instanceof CandidateVacancy) {
            $hiringMangerEmpNumber = $candidateVacancy->getVacancy()->getHiringManager()->getEmpNumber();
            if ($hiringMangerEmpNumber !== $this->getAuthUser()->getEmpNumber()) {
                $rolesToExclude = ['HiringManager'];
            }
        }
        $accessibleActionHistoryIds = $this->getUserRoleManager()
            ->getAccessibleEntityIds(CandidateActionHistory::class, null, null, $rolesToExclude);
        $candidateHistorySearchFilterParams->setActionIds($accessibleActionHistoryIds);

        $candidateHistoryRecords = $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateHistoryRecords($candidateHistorySearchFilterParams);

        $count = $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateHistoryRecordsCount($candidateHistorySearchFilterParams);

        return new EndpointCollectionResult(
            CandidateHistoryListModel::class,
            $candidateHistoryRecords,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $count])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_CANDIDATE_ID,
                new Rule(Rules::IN_ACCESSIBLE_ENTITY_ID, [Candidate::class])
            ),
            ...$this->getSortingAndPaginationParamsRules(CandidateHistorySearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $candidateHistoryRecord = $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateHistoryRecordByCandidateIdAndHistoryId($this->getCandidateId(), $this->getHistoryId());

        $this->throwRecordNotFoundExceptionIfNotExist($candidateHistoryRecord, CandidateHistory::class);
        $vacancy = $candidateHistoryRecord->getVacancy();
        $disabled = false;
        if ($candidateHistoryRecord->getAction() === WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHORTLIST &&
            (
                !is_null($vacancy) &&
                $vacancy->getHiringManager()->getEmpNumber() !== $this->getAuthUser()->getEmpNumber()
            )
        ) {
            $rolesToExclude = ['HiringManager'];
            $allowedWorkflowItems = $this->getUserRoleManager()->getAllowedActions(
                WorkflowStateMachine::FLOW_RECRUITMENT,
                CandidateService::STATUS_MAP[WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_ATTACH_VACANCY],
                $rolesToExclude
            );
            $disabled = !in_array(
                WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHORTLIST,
                array_keys($allowedWorkflowItems)
            );
        }
        return new EndpointResourceResult(
            CandidateHistoryDetailedModel::class,
            $candidateHistoryRecord,
            new ParameterBag(['disabled' => $disabled])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_CANDIDATE_ID,
                new Rule(Rules::IN_ACCESSIBLE_ENTITY_ID, [Candidate::class])
            ),
            new ParamRule(
                self::PARAMETER_HISTORY_ID,
                new Rule(Rules::POSITIVE)
            ),
        );
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        $candidateVacancy = $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateVacancyByCandidateId($this->getCandidateId());

        if (is_null($candidateVacancy)) {
            throw $this->getForbiddenException();
        }

        $candidateHistoryRecord = $this->getCandidateService()
            ->getCandidateDao()
            ->getCandidateHistoryRecordByCandidateIdAndHistoryId($this->getCandidateId(), $this->getHistoryId());

        $this->throwRecordNotFoundExceptionIfNotExist($candidateHistoryRecord, CandidateHistory::class);

        $candidateHistoryRecord->setNote(
            $this->getRequestParams()->getStringOrNull(
                RequestParams::PARAM_TYPE_BODY,
                self::PARAMETER_NOTE
            )
        );
        $this->getCandidateService()->getCandidateDao()->saveCandidateHistory($candidateHistoryRecord);
        return new EndpointResourceResult(CandidateHistoryDetailedModel::class, $candidateHistoryRecord);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_CANDIDATE_ID,
                new Rule(Rules::IN_ACCESSIBLE_ENTITY_ID, [Candidate::class])
            ),
            new ParamRule(
                self::PARAMETER_HISTORY_ID,
                new Rule(Rules::POSITIVE)
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::PARAMETER_NOTE,
                    new Rule(Rules::STRING_TYPE),
                    new Rule(Rules::LENGTH, [null, self::PARAMETER_RULE_NOTE_MAX_LENGTH]),
                ),
                true
            )
        );
    }

    /**
     * @return int
     */
    private function getCandidateId(): int
    {
        return $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_CANDIDATE_ID
        );
    }

    /**
     * @return int
     */
    private function getHistoryId(): int
    {
        return $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_HISTORY_ID
        );
    }
}
