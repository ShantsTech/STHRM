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

namespace ShantsHRM\Recruitment\Controller\File;

use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Recruitment\Traits\Service\RecruitmentAttachmentServiceTrait;

class CandidateAttachment extends AbstractFileController
{
    use RecruitmentAttachmentServiceTrait;

    public function handle(Request $request): Response
    {
        $candidateId = $request->attributes->get('candidateId');
        $response = $this->getResponse();

        if ($candidateId) {
            $attachment = $this->getRecruitmentAttachmentService()
                ->getRecruitmentAttachmentDao()
                ->getCandidateAttachmentByCandidateId($candidateId);
            if ($attachment instanceof \ShantsHRM\Entity\CandidateAttachment) {
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
