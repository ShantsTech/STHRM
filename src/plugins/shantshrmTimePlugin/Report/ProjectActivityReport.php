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

namespace ShantsHRM\Time\Report;

use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Api\V2\Validator\ValidatorException;
use ShantsHRM\Core\Dto\FilterParams;
use ShantsHRM\Core\Report\Api\EndpointProxy;
use ShantsHRM\Core\Report\Header\Column;
use ShantsHRM\Core\Report\Header\Header;
use ShantsHRM\Core\Report\Header\HeaderDefinition;
use ShantsHRM\Core\Report\ReportData;
use ShantsHRM\Entity\ProjectActivity;
use ShantsHRM\I18N\Traits\Service\I18NHelperTrait;
use ShantsHRM\Time\Dto\ProjectActivityDetailedReportSearchFilterParams;

class ProjectActivityReport extends ProjectReport
{
    use I18NHelperTrait;

    public const PARAMETER_EMPLOYEE_NAME = 'employeeName';

    public const FILTER_PARAMETER_PROJECT_ACTIVITY_ID = 'activityId';

    /**
     * @inheritDoc
     */
    public function prepareFilterParams(EndpointProxy $endpoint): FilterParams
    {
        $filterParams = new ProjectActivityDetailedReportSearchFilterParams();
        $filterParams->setProjectId(
            $endpoint->getRequestParams()->getInt(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_PROJECT_ID
            )
        );

        $filterParams->setProjectActivityId(
            $endpoint->getRequestParams()->getInt(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_PROJECT_ACTIVITY_ID
            )
        );

        $endpoint->setSortingAndPaginationParams($filterParams);

        $filterParams->setFromDate(
            $endpoint->getRequestParams()->getDateTimeOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_DATE_FROM
            )
        );
        $filterParams->setToDate(
            $endpoint->getRequestParams()->getDateTimeOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_DATE_TO
            )
        );
        $filterParams->setIncludeApproveTimesheet(
            $endpoint->getRequestParams()->getStringOrNull(
                RequestParams::PARAM_TYPE_QUERY,
                self::FILTER_PARAMETER_PARAMETER_INCLUDE_TIMESHEET
            )
        );

        return $filterParams;
    }

    /**
     * @param EndpointProxy $endpoint
     * @return ParamRuleCollection
     * @throws ValidatorException
     */
    public function getValidationRule(EndpointProxy $endpoint): ParamRuleCollection
    {
        $paramRuleCollection = parent::getValidationRule($endpoint);
        $paramRuleCollection->addParamValidation(
            $endpoint->getValidationDecorator()->requiredParamRule(
                new ParamRule(
                    ProjectReport::PARAMETER_ACTIVITY_ID,
                    new Rule(Rules::POSITIVE),
                    new Rule(Rules::ENTITY_ID_EXISTS, [ProjectActivity::class]),
                )
            ),
        );
        return $paramRuleCollection;
    }

    /**
     * @return Header
     */
    public function getHeaderDefinition(): HeaderDefinition
    {
        return new Header(
            [
                (new Column(self::PARAMETER_EMPLOYEE_NAME))
                    ->setName($this->getI18NHelper()->transBySource('Employee Name'))
                    ->setSize(ProjectReport::DEFAULT_COLUMN_SIZE),
                (new Column(ProjectReport::PARAMETER_TIME))
                    ->setName($this->getI18NHelper()->transBySource('Time (Hours)'))
                    ->setCellProperties(['class' => ['col-alt' => true]])
                    ->setSize(ProjectReport::DEFAULT_COLUMN_SIZE),
            ]
        );
    }

    /**
     * @param ProjectActivityDetailedReportSearchFilterParams $filterParams
     * @return ProjectActivityDetailedReportData
     */
    public function getData(FilterParams $filterParams): ReportData
    {
        return new ProjectActivityDetailedReportData($filterParams);
    }
}
