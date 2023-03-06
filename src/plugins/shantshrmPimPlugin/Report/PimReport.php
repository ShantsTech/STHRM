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

namespace ShantsHRM\Pim\Report;

use ShantsHRM\Core\Api\Rest\ReportAPI;
use ShantsHRM\Core\Api\V2\Exception\ForbiddenException;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Dto\FilterParams;
use ShantsHRM\Core\Report\Api\EndpointAwareReport;
use ShantsHRM\Core\Report\Api\EndpointProxy;
use ShantsHRM\Core\Report\Filter\Filter;
use ShantsHRM\Core\Report\Header\Header;
use ShantsHRM\Core\Service\ReportGeneratorService;
use ShantsHRM\Pim\Api\PimReportAPI;
use ShantsHRM\Pim\Dto\PimReportSearchFilterParams;

class PimReport implements EndpointAwareReport
{
    /**
     * @var ReportGeneratorService|null
     */
    private ?ReportGeneratorService $reportGeneratorService = null;

    /**
     * @var int
     */
    private int $reportId;

    /**
     * @param int $reportId
     */
    public function __construct(int $reportId)
    {
        $this->reportId = $reportId;
    }

    /**
     * @return ReportGeneratorService
     */
    protected function getReportGeneratorService(): ReportGeneratorService
    {
        if (!$this->reportGeneratorService instanceof ReportGeneratorService) {
            $this->reportGeneratorService = new ReportGeneratorService();
        }
        return $this->reportGeneratorService;
    }

    /**
     * @return Header
     */
    public function getHeaderDefinition(): Header
    {
        return $this->getReportGeneratorService()->getHeaderDefinitionByReportId($this->reportId);
    }

    /**
     * @return Filter
     */
    public function getFilterDefinition(): Filter
    {
        return new Filter();
    }

    /**
     * @param PimReportSearchFilterParams $filterParams
     * @return PimReportData
     */
    public function getData(FilterParams $filterParams): PimReportData
    {
        return new PimReportData($filterParams);
    }

    /**
     * @inheritDoc
     */
    public function prepareFilterParams(EndpointProxy $endpoint): PimReportSearchFilterParams
    {
        $filterParams = new PimReportSearchFilterParams();
        $endpoint->setSortingAndPaginationParams($filterParams);
        $filterParams->setReportId(
            $endpoint->getRequestParams()->getInt(RequestParams::PARAM_TYPE_QUERY, PimReportAPI::PARAMETER_REPORT_ID)
        );
        return $filterParams;
    }

    /**
     * @inheritDoc
     */
    public function getValidationRule(EndpointProxy $endpoint): ParamRuleCollection
    {
        return new ParamRuleCollection(
            ...$endpoint->getSortingAndPaginationParamsRules([])
        );
    }

    /**
     * @inheritDoc
     */
    public function checkReportAccessibility(EndpointProxy $endpoint): void
    {
        $reportName = $endpoint->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_QUERY,
            ReportAPI::PARAMETER_NAME
        );
        if ($reportName !== 'pim_defined') {
            // Should handle permissions if PIM report requirement changes
            throw new ForbiddenException();
        }
    }
}
