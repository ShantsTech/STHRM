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

namespace ShantsHRM\Admin\Controller\File;

use ShantsHRM\Admin\Service\JobTitleService;
use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Entity\JobSpecificationAttachment;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;

class JobSpecification extends AbstractFileController
{
    /**
     * @var JobTitleService|null
     */
    protected ?JobTitleService $jobTitleService = null;

    /**
     * @return JobTitleService
     */
    public function getJobTitleService(): JobTitleService
    {
        if (!$this->jobTitleService instanceof JobTitleService) {
            $this->jobTitleService = new JobTitleService();
        }
        return $this->jobTitleService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $attachId = $request->attributes->get('attachId');
        $response = $this->getResponse();

        if ($attachId) {
            $attachment = $this->getJobTitleService()->getJobSpecAttachmentById($attachId);
            if ($attachment instanceof JobSpecificationAttachment) {
                $this->setCommonHeadersToResponse(
                    $attachment->getFileName(),
                    $attachment->getFileType(),
                    $attachment->getFileSize(),
                    $response
                );
                $response->setContent($attachment->getDecorator()->getFileContent());
                return $response;
            }
        }

        return $this->handleBadRequest();
    }
}
