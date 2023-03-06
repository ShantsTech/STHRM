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

namespace ShantsHRM\Maintenance\Api;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
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
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Maintenance\Api\Model\PurgeCandidateListModel;
use ShantsHRM\Maintenance\Api\Model\PurgeCandidateModel;
use ShantsHRM\Maintenance\Service\PurgeService;
use ShantsHRM\ORM\Exception\TransactionException;
use ShantsHRM\Recruitment\Dto\CandidateSearchFilterParams;
use ShantsHRM\Recruitment\Traits\Service\CandidateServiceTrait;

class PurgeCandidateAPI extends Endpoint implements CollectionEndpoint
{
    use CandidateServiceTrait;

    private ?PurgeService $purgeService = null;

    public const PARAMETER_VACANCY_ID = 'vacancyId';

    /**
     * @return PurgeService
     */
    public function getPurgeService(): PurgeService
    {
        if (is_null($this->purgeService)) {
            $this->purgeService = new PurgeService();
        }
        return $this->purgeService;
    }

    /**
     * @inheritDoc
     * @throws TransactionException
     */
    public function delete(): EndpointResult
    {
        $vacancyId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_VACANCY_ID
        );
        $this->getPurgeService()->purgeCandidateData($vacancyId);
        return new EndpointResourceResult(PurgeCandidateModel::class, $vacancyId);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_VACANCY_ID,
                new Rule(Rules::POSITIVE),
                new Rule(Rules::ENTITY_ID_EXISTS, [Vacancy::class])
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $candidateSearchFilterParamHolder = new CandidateSearchFilterParams();

        $candidateSearchFilterParamHolder->setVacancyId(
            $this->getRequestParams()->getInt(
                RequestParams::PARAM_TYPE_QUERY,
                self::PARAMETER_VACANCY_ID
            )
        );
        $candidateSearchFilterParamHolder->setConsentToKeepData(false);

        $this->setSortingAndPaginationParams($candidateSearchFilterParamHolder);

        $candidates = $this->getCandidateService()->getCandidateDao()->getCandidatesList($candidateSearchFilterParamHolder);
        $count = $this->getCandidateService()->getCandidateDao()->getCandidatesCount($candidateSearchFilterParamHolder);

        return new EndpointCollectionResult(
            PurgeCandidateListModel::class,
            $candidates,
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
                self::PARAMETER_VACANCY_ID,
                new Rule(Rules::POSITIVE),
                new Rule(Rules::ENTITY_ID_EXISTS, [Vacancy::class])
            ),
            ...$this->getSortingAndPaginationParamsRules([])
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
}
