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

namespace ShantsHRM\Pim\Controller\File;

use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Entity\EmployeeAttachment;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Pim\Service\EmployeeAttachmentService;

class EmployeeAttachmentController extends AbstractFileController
{
    /**
     * @var EmployeeAttachmentService|null
     */
    protected ?EmployeeAttachmentService $employeeAttachmentService = null;

    /**
     * @return EmployeeAttachmentService
     */
    public function getEmployeeAttachmentService(): EmployeeAttachmentService
    {
        if (!$this->employeeAttachmentService instanceof EmployeeAttachmentService) {
            $this->employeeAttachmentService = new EmployeeAttachmentService();
        }
        return $this->employeeAttachmentService;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws DaoException
     */
    public function handle(Request $request): Response
    {
        $empNumber = $request->attributes->get('empNumber');
        $attachId = $request->attributes->get('attachId');

        $response = $this->getResponse();

        if ($empNumber && $attachId) {
            $attachment = $this->getEmployeeAttachmentService()->getAccessibleEmployeeAttachment($empNumber, $attachId);
            if ($attachment instanceof EmployeeAttachment) {
                $this->setCommonHeadersToResponse(
                    $attachment->getFilename(),
                    $attachment->getFileType(),
                    $attachment->getSize(),
                    $response
                );
                $response->setContent($attachment->getDecorator()->getAttachment());
                return $response;
            }
        }

        return $this->handleBadRequest();
    }
}
