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

namespace ShantsHRM\Time\Api;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Entity\Project;
use ShantsHRM\Time\Api\Model\CopyActivityModel;
use ShantsHRM\Time\Dto\ProjectActivitySearchFilterParams;
use ShantsHRM\Time\Exception\ProjectServiceException;
use ShantsHRM\Time\Traits\Service\ProjectServiceTrait;

class CopyProjectActivityAPI extends Endpoint implements CollectionEndpoint
{
    use ProjectServiceTrait;

    public const PARAMETER_FROM_PROJECT_ID = 'fromProjectId';
    public const PARAMETER_TO_PROJECT_ID = 'toProjectId';
    public const PARAMETER_ACTIVITY_IDS = 'activityIds';

    /**
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        list($toProjectId, $fromProjectId) = $this->getUrlAttributes();
        $projectActivitySearchFilterParams = new ProjectActivitySearchFilterParams();
        $this->setSortingAndPaginationParams($projectActivitySearchFilterParams);
        $projectActivitiesForFromProject = $this->getProjectService()
            ->getProjectActivityDao()
            ->getProjectActivityListByProjectId($fromProjectId, $projectActivitySearchFilterParams);
        $duplicateActivities = $this->getProjectService()
            ->getProjectActivityDao()
            ->getDuplicatedActivities($fromProjectId, $toProjectId);

        $projectActivityCount = $this->getProjectService()
            ->getProjectActivityDao()
            ->getProjectActivityCount($fromProjectId, $projectActivitySearchFilterParams);

        return new EndpointCollectionResult(
            CopyActivityModel::class,
            [$projectActivitiesForFromProject, $duplicateActivities],
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $projectActivityCount])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            ...$this->getCommonURLValidationRules(),
            ...$this->getSortingAndPaginationParamsRules(ProjectActivitySearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        try {
            list($toProjectId, $fromProjectId) = $this->getUrlAttributes();
            $ids = $this->getRequestParams()->getArray(RequestParams::PARAM_TYPE_BODY, self::PARAMETER_ACTIVITY_IDS);
            $this->getProjectService()->validateProjectActivityName($toProjectId, $fromProjectId, $ids);
            $this->getProjectService()->getProjectActivityDao()->copyActivities($toProjectId, $ids);

            return new EndpointResourceResult(ArrayModel::class, $ids);
        } catch (ProjectServiceException $projectServiceException) {
            throw $this->getBadRequestException($projectServiceException->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_ACTIVITY_IDS,
                    new Rule(Rules::ARRAY_TYPE),
                    new Rule(
                        Rules::EACH,
                        [new Rules\Composite\AllOf(new Rule(Rules::POSITIVE))]
                    )
                )
            ),
            ...$this->getCommonURLValidationRules()
        );
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
     * @return ParamRule[]
     */
    private function getCommonURLValidationRules(): array
    {
        return [
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_TO_PROJECT_ID,
                    new Rule(Rules::POSITIVE),
                    new Rule(Rules::ENTITY_ID_EXISTS, [Project::class])
                )
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_FROM_PROJECT_ID,
                    new Rule(Rules::POSITIVE),
                    new Rule(Rules::ENTITY_ID_EXISTS, [Project::class])
                )
            )
        ];
    }

    /**
     * @return array
     */
    private function getUrlAttributes(): array
    {
        $fromProjectId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_FROM_PROJECT_ID
        );

        $toProjectId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_TO_PROJECT_ID
        );
        return [$toProjectId, $fromProjectId];
    }
}
