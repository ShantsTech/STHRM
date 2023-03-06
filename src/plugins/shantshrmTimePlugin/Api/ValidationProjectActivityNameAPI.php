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
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Entity\Project;
use ShantsHRM\Entity\ProjectActivity;
use ShantsHRM\Time\Traits\Service\ProjectServiceTrait;

class ValidationProjectActivityNameAPI extends Endpoint implements ResourceEndpoint
{
    use ProjectServiceTrait;

    public const PARAMETER_PROJECT_ACTIVITY_NAME = 'activityName';
    public const PARAMETER_PROJECT_ACTIVITY_Id = 'activityId';
    public const PARAMETER_IS_CHANGEABLE_PROJECT_ACTIVITY_NAME = 'valid';

    public const PARAM_RULE_PROJECT_ACTIVITY_NAME_MAX_LENGTH = 50;


    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        $projectActivityName = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PROJECT_ACTIVITY_NAME
        );

        $projectId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            CommonParams::PARAMETER_ID
        );

        $projectActivityId = $this->getRequestParams()->getIntOrNull(
            RequestParams::PARAM_TYPE_QUERY,
            self::PARAMETER_PROJECT_ACTIVITY_Id
        );

        $project = $this->getProjectService()->getProjectDao()->getProjectById($projectId);
        $this->throwRecordNotFoundExceptionIfNotExist($project, Project::class);

        if (!is_null($projectActivityId)) {
            $projectActivity = $this->getProjectService()
                ->getProjectActivityDao()
                ->getProjectActivityByProjectIdAndProjectActivityId($projectId, $projectActivityId);
            $this->throwRecordNotFoundExceptionIfNotExist($projectActivity, ProjectActivity::class);
        }

        $isChangeableProjectActivityName = !$this->getProjectService()
            ->getProjectActivityDao()
            ->isProjectActivityNameTaken($projectId, $projectActivityName, $projectActivityId);

        return new EndpointResourceResult(
            ArrayModel::class,
            [
                self::PARAMETER_IS_CHANGEABLE_PROJECT_ACTIVITY_NAME => $isChangeableProjectActivityName,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    CommonParams::PARAMETER_ID,
                    new Rule(Rules::POSITIVE)
                ),
            ),
            $this->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    self::PARAMETER_PROJECT_ACTIVITY_NAME,
                    new Rule(Rules::STRING_TYPE),
                    new Rule(Rules::LENGTH, [null, self::PARAM_RULE_PROJECT_ACTIVITY_NAME_MAX_LENGTH]),
                ),
            ),
            $this->getValidationDecorator()->notRequiredParamRule(
                new ParamRule(
                    self::PARAMETER_PROJECT_ACTIVITY_Id,
                    new Rule(Rules::POSITIVE),
                )
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
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
}
