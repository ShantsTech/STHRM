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

use ShantsHRM\Core\Dto\FilterParams;

class UserSearchFilterParams extends FilterParams
{
    public const ALLOWED_SORT_FIELDS = ['u.userName', 'u.status', 'r.name', 'e.firstName', 'e.lastName','r.displayName'];

    /**
     * @var bool|null
     */
    protected ?bool $status = null;
    /**
     * @var string|null
     */
    protected ?string $username = null;
    /**
     * @var int|null
     */
    protected ?int $userRoleId = null;
    /**
     * @var int|null
     */
    protected ?int $empNumber = null;
    /**
     * @var bool|null
     */
    protected ?bool $hasPassword = null;

    public function __construct()
    {
        $this->setSortField('u.userName');
    }

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @param bool|null $status
     */
    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return int|null
     */
    public function getUserRoleId(): ?int
    {
        return $this->userRoleId;
    }

    /**
     * @param int|null $userRoleId
     */
    public function setUserRoleId(?int $userRoleId): void
    {
        $this->userRoleId = $userRoleId;
    }

    /**
     * @return int|null
     */
    public function getEmpNumber(): ?int
    {
        return $this->empNumber;
    }

    /**
     * @param int|null $empNumber
     */
    public function setEmpNumber(?int $empNumber): void
    {
        $this->empNumber = $empNumber;
    }

    /**
     * @return bool|null
     */
    public function hasPassword(): ?bool
    {
        return $this->hasPassword;
    }

    /**
     * @param bool|null $hasPassword
     */
    public function setHasPassword(?bool $hasPassword): void
    {
        $this->hasPassword = $hasPassword;
    }
}
