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

namespace ShantsHRM\Buzz\Dto;

use ShantsHRM\Core\Dto\FilterParams;
use ShantsHRM\ORM\ListSorter;

class BuzzFeedFilterParams extends FilterParams
{
    public const ALLOWED_SORT_FIELDS = ['share.createdAtUtc', 'share.numOfLikes', 'share.numOfComments'];

    private int $authUserEmpNumber;

    private ?int $shareId = null;

    public function __construct()
    {
        $this->setSortField('share.createdAtUtc');
        $this->setSortOrder(ListSorter::DESCENDING);
    }

    /**
     * @return int
     */
    public function getAuthUserEmpNumber(): int
    {
        return $this->authUserEmpNumber;
    }

    /**
     * @param int $authUserEmpNumber
     */
    public function setAuthUserEmpNumber(int $authUserEmpNumber): void
    {
        $this->authUserEmpNumber = $authUserEmpNumber;
    }

    /**
     * @return int|null
     */
    public function getShareId(): ?int
    {
        return $this->shareId;
    }

    /**
     * @param int|null $shareId
     */
    public function setShareId(?int $shareId): void
    {
        $this->shareId = $shareId;
    }
}
