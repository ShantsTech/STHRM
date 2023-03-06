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

use ShantsHRM\Core\Traits\EventDispatcherTrait;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Dashboard\Service\ChartService;
use ShantsHRM\Dashboard\Service\EmployeeActionSummaryService;
use ShantsHRM\Dashboard\Service\EmployeeOnLeaveService;
use ShantsHRM\Dashboard\Service\EmployeeTimeAtWorkService;
use ShantsHRM\Dashboard\Service\QuickLaunchService;
use ShantsHRM\Dashboard\Subscriber\LeaveModuleStatusChangeSubscriber;
use ShantsHRM\Dashboard\Subscriber\TimeModuleStatusChangeSubscriber;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\PluginConfigurationInterface;
use ShantsHRM\Framework\Services;

class DashboardPluginConfiguration implements PluginConfigurationInterface
{
    use ServiceContainerTrait;
    use EventDispatcherTrait;

    /**
     * @inheritDoc
     */
    public function initialize(Request $request): void
    {
        $this->getContainer()->register(
            Services::EMPLOYEE_ON_LEAVE_SERVICE,
            EmployeeOnLeaveService::class
        );

        $this->getContainer()->register(
            Services::CHART_SERVICE,
            ChartService::class
        );
        $this->getContainer()->register(
            Services::QUICK_LAUNCH_SERVICE,
            QuickLaunchService::class
        );

        $this->getContainer()->register(
            Services::EMPLOYEE_TIME_AT_WORK_SERVICE,
            EmployeeTimeAtWorkService::class
        );

        $this->getContainer()->register(
            Services::EMPLOYEE_ACTION_SUMMARY_SERVICE,
            EmployeeActionSummaryService::class
        );

        $this->getEventDispatcher()->addSubscriber(new TimeModuleStatusChangeSubscriber());
        $this->getEventDispatcher()->addSubscriber(new LeaveModuleStatusChangeSubscriber());
    }
}
