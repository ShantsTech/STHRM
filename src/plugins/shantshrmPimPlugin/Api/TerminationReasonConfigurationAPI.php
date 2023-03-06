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

namespace ShantsHRM\Pim\Api;

use Exception;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CrudEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Exception\RecordNotFoundException;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Entity\TerminationReason;
use ShantsHRM\Pim\Api\Model\TerminationReasonConfigurationModel;
use ShantsHRM\Pim\Dto\TerminationReasonConfigurationSearchFilterParams;
use ShantsHRM\Pim\Service\TerminationReasonConfigurationService;

class TerminationReasonConfigurationAPI extends EndPoint implements CrudEndpoint
{
    public const PARAMETER_NAME = 'name';
    public const PARAM_RULE_NAME_MAX_LENGTH = 100;

    /**
     * @var TerminationReasonConfigurationService|null
     */
    protected ?TerminationReasonConfigurationService $terminationReasonConfigurationService = null;

    public function getTerminationReasonConfigurationService(): TerminationReasonConfigurationService
    {
        if (!$this->terminationReasonConfigurationService instanceof TerminationReasonConfigurationService) {
            $this->terminationReasonConfigurationService = new TerminationReasonConfigurationService();
        }
        return $this->terminationReasonConfigurationService;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getAll(): EndpointResult
    {
        $terminationReasonConfigurationParamHolder = new TerminationReasonConfigurationSearchFilterParams();
        $this->setSortingAndPaginationParams($terminationReasonConfigurationParamHolder);
        $terminationReasons = $this->getTerminationReasonConfigurationService()->getTerminationReasonList($terminationReasonConfigurationParamHolder);
        $count = $this->getTerminationReasonConfigurationService()->getTerminationReasonCount($terminationReasonConfigurationParamHolder);
        return new EndpointCollectionResult(
            TerminationReasonConfigurationModel::class,
            $terminationReasons,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $count])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            ...$this->getSortingAndPaginationParamsRules(TerminationReasonConfigurationSearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function create(): EndpointResult
    {
        $terminationReason = $this->saveTerminationReason();
        return new EndpointResourceResult(TerminationReasonConfigurationModel::class, $terminationReason);
    }

    /**
     * @return TerminationReason
     * @throws DaoException
     * @throws RecordNotFoundException
     */
    public function saveTerminationReason(): TerminationReason
    {
        $id = $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, CommonParams::PARAMETER_ID);
        $name = $this->getRequestParams()->getString(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_NAME);
        if ($id) {
            $terminationReason = $this->getTerminationReasonConfigurationService()->getTerminationReasonById($id);
            $this->throwRecordNotFoundExceptionIfNotExist($terminationReason, TerminationReason::class);
        } else {
            $terminationReason = new TerminationReason();
        }
        $terminationReason->setName($name);
        return $this->getTerminationReasonConfigurationService()->saveTerminationReason($terminationReason);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_NAME,
                new Rule(Rules::STRING_TYPE),
                new Rule(Rules::LENGTH, [null, self::PARAM_RULE_NAME_MAX_LENGTH]),
            ),
        );
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function delete(): EndpointResult
    {
        $ids = $this->getRequestParams()->getArray(RequestParams::PARAM_TYPE_BODY, CommonParams::PARAMETER_IDS);
        $this->getTerminationReasonConfigurationService()->deleteTerminationReasons($ids);
        return new EndpointResourceResult(ArrayModel::class, $ids);
    }

    /**
     * @inheritDoc
     * @throws DaoException
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        $reasonIdsInUse = $this->getTerminationReasonConfigurationService()->getReasonIdsInUse();
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_IDS,
                new Rule(
                    Rules::EACH,
                    [
                        new Rules\Composite\AllOf(
                            new Rule(Rules::POSITIVE),
                            new Rule(Rules::NOT_IN, [$reasonIdsInUse])
                        )
                    ]
                )
            ),
        );
    }

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResourceResult
    {
        $id = $this->getRequestParams()->getInt(RequestParams::PARAM_TYPE_ATTRIBUTE, CommonParams::PARAMETER_ID);
        $terminationReason = $this->getTerminationReasonConfigurationService()->getTerminationReasonById($id);
        $this->throwRecordNotFoundExceptionIfNotExist($terminationReason, TerminationReason::class);
        return new EndpointResourceResult(TerminationReasonConfigurationModel::class, $terminationReason);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_ID,
                new Rule(Rules::POSITIVE)
            ),
        );
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function update(): EndpointResult
    {
        $terminationReason = $this->saveTerminationReason();
        return new EndpointResourceResult(TerminationReasonConfigurationModel::class, $terminationReason);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_ID,
                new Rule(Rules::POSITIVE)
            ),
            new ParamRule(
                self::PARAMETER_NAME,
                new Rule(Rules::STRING_TYPE),
                new Rule(Rules::LENGTH, [null, self::PARAM_RULE_NAME_MAX_LENGTH]),
            ),
        );
    }
}
