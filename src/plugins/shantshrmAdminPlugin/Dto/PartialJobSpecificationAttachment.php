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

namespace ShantsHRM\Admin\Dto;

class PartialJobSpecificationAttachment
{
    private ?int $id;

    private ?string $fileName;

    private ?string $fileType;

    private ?int $fileSize;

    private ?int $jobTitleId;

    /**
     * @param int|null $id
     * @param string|null $fileName
     * @param string|null $fileType
     * @param int|null $fileSize
     * @param int|null $jobTitleId
     */
    public function __construct(?int $id, ?string $fileName, ?string $fileType, ?int $fileSize, ?int $jobTitleId)
    {
        $this->id = $id;
        $this->fileName = $fileName;
        $this->fileType = $fileType;
        $this->fileSize = $fileSize;
        $this->jobTitleId = $jobTitleId;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @return string|null
     */
    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    /**
     * @return int|null
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * @return int|null
     */
    public function getJobTitleId(): ?int
    {
        return $this->jobTitleId;
    }
}
