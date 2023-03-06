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

namespace ShantsHRM\Time\Controller;

use ShantsHRM\Core\Authorization\Service\HomePageService;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Framework\Http\Request;

class TimesheetPeriodConfigController extends AbstractVueController
{
    use ConfigServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @var HomePageService|null
     */
    protected ?HomePageService $homePageService = null;

    /**
     * @return HomePageService
     */
    public function getHomePageService(): HomePageService
    {
        if (!$this->homePageService instanceof HomePageService) {
            $this->homePageService = new HomePageService();
        }
        return $this->homePageService;
    }

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        // to block defineTimesheetPeriod (URL)
        $status = $this->getConfigService()->isTimesheetPeriodDefined();
        if (!$status) {
            if ($this->getUserRoleManager()->getDataGroupPermissions('attendance_configuration')->canUpdate()) {
                // config page of define start week
                $component = new Component('time-sheet-period');
            } else {
                // normal user -> warning page
                $component = new Component('time-sheet-period-not-defined');
            }
            $this->setComponent($component);
        } else {
            $defaultPath = $this->getHomePageService()->getTimeModuleDefaultPath();
            $this->setResponse($this->redirect($defaultPath));
        }
    }
}
