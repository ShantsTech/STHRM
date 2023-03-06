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

namespace ShantsHRM\Recruitment\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Recruitment\Dto\RecruitmentAttachment;

class InterviewAttachmentModel implements Normalizable
{
    use ModelTrait;

    public function __construct(RecruitmentAttachment $interviewAttachment)
    {
        $this->setEntity($interviewAttachment);
        $this->setFilters(
            [
                'id',
                'fkIdentity', //this represents the interviewId
                'fileName',
                'fileType',
                'fileSize',
                'comment',
            ]
        );
        $this->setAttributeNames(
            [
                'id',
                'interviewId',
                ['attachment', 'fileName'],
                ['attachment', 'fileType'],
                ['attachment', 'fileSize'],
                'comment',
            ]
        );
    }
}
