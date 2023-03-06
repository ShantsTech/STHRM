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

namespace ShantsHRM\Maintenance\Controller\File;

use ShantsHRM\Authentication\Controller\ForbiddenController;
use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Service\TextHelperTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Maintenance\DownloadFormats\JsonDownloadFormat;
use ShantsHRM\Maintenance\Service\MaintenanceService;

class AccessEmployeeFileController extends AbstractFileController
{
    use TextHelperTrait;
    use UserRoleManagerTrait;

    /**
     * @var MaintenanceService|null
     */
    protected ?MaintenanceService $maintenanceService = null;
    protected ?JsonDownloadFormat $downloadFormat = null;

    /**
     * @return MaintenanceService
     */

    public function getDownloadFormat(): JsonDownloadFormat
    {
        if (!$this->downloadFormat instanceof JsonDownloadFormat) {
            $this->downloadFormat = new JsonDownloadFormat();
        }
        return $this->downloadFormat;
    }

    public function getMaintenanceService(): MaintenanceService
    {
        if (!$this->maintenanceService instanceof MaintenanceService) {
            $this->maintenanceService = new MaintenanceService();
        }
        return $this->maintenanceService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        if (!$this->getUserRoleManager()->getDataGroupPermissions('maintenance_employee_json')->canRead()) {
            throw new RequestForwardableException(ForbiddenController::class . '::handle');
        }

        $empNumber = $request->attributes->get('empNumber');
        $response = $this->getResponse();

        if ($empNumber) {
            $content = $this->getDownloadFormat()->getFormattedString(
                $this->getMaintenanceService()->accessEmployeeData($empNumber)
            );
            $this->setCommonHeadersToResponse(
                $this->getDownloadFormat()->getDownloadFileName($empNumber),
                'application/json',
                $this->getTextHelper()->strLength($content),
                $response
            );
            $response->setContent($content);
            return $response;
        }

        return $this->handleBadRequest();
    }
}
