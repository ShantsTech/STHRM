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

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Report\ReportData;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Core\Traits\Service\NumberHelperTrait;
use ShantsHRM\Time\Api\Model\ProjectModel;
use ShantsHRM\Time\Dto\ProjectReportSearchFilterParams;
use ShantsHRM\Time\Traits\Service\ProjectServiceTrait;

class ProjectReportData implements ReportData
{
    use ProjectServiceTrait;
    use NumberHelperTrait;
    use DateTimeHelperTrait;
    use NormalizerServiceTrait;

    private ProjectReportSearchFilterParams $filterParams;

    public function __construct(ProjectReportSearchFilterParams $filterParams)
    {
        $this->filterParams = $filterParams;
    }

    /**
     * @inheritDoc
     */
    public function normalize(): array
    {
        $projectActivities = $this->getProjectService()
            ->getProjectDao()
            ->getProjectReportCriteriaList($this->filterParams);
        $fromDateYmd = $this->getDateTimeHelper()->formatDateTimeToYmd($this->filterParams->getFromDate());
        $toDateYmd = $this->getDateTimeHelper()->formatDateTimeToYmd($this->filterParams->getToDate());
        $projectId = $this->filterParams->getProjectId();
        $includeTimesheet = $this->filterParams->getIncludeApproveTimesheet() === null ?
            ProjectReportSearchFilterParams::INCLUDE_TIMESHEET_ALL : $this->filterParams->getIncludeApproveTimesheet();
        $projectActivityDetailsReportURL = '/time/displayProjectActivityDetailsReport';
        $result = [];
        foreach ($projectActivities as $projectActivity) {
            $activityId = $projectActivity['activityId'];
            $result[] = [
                ProjectReport::PARAMETER_ACTIVITY_ID => $activityId,
                ProjectReport::PARAMETER_ACTIVITY_NAME => $projectActivity['name'],
                ProjectReport::PARAMETER_ACTIVITY_DELETED => $projectActivity['deleted'],
                ProjectReport::PARAMETER_TIME => $this->getNumberHelper()
                    ->numberFormat((float)$projectActivity['totalDuration'] / 3600, 2),
                '_url' => [
                    ProjectReport::PARAMETER_ACTIVITY_NAME => $projectActivityDetailsReportURL .
                        "?fromDate=$fromDateYmd" .
                        "&toDate=$toDateYmd" .
                        "&projectId=$projectId" .
                        "&activityId=$activityId" .
                        "&includeTimesheet=$includeTimesheet"
                ],
            ];
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getMeta(): ?ParameterBag
    {
        $project = $this->getProjectService()->getProjectDao()->getProjectById($this->filterParams->getProjectId());
        $total = $this->getProjectService()
            ->getProjectDao()
            ->getTotalDurationForProjectReport($this->filterParams);

        return new ParameterBag(
            [
                CommonParams::PARAMETER_TOTAL => $this->getProjectService()
                    ->getProjectDao()
                    ->getProjectReportCriteriaListCount($this->filterParams),
                'sum' => [
                    'hours' => floor($total / 3600),
                    'minutes' => ($total / 60) % 60,
                    'label' => $this->getNumberHelper()->numberFormat($total / 3600, 2),
                ],
                'project' => $this->getNormalizerService()
                    ->normalize(ProjectModel::class, $project),
            ]
        );
    }
}
