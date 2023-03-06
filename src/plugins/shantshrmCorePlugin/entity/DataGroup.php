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

namespace ShantsHRM\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ohrm_data_group")
 * @ORM\Entity
 */
class DataGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private string $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="can_read", type="boolean", nullable=true)
     */
    private ?bool $canRead = null;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="can_create", type="boolean", nullable=true)
     */
    private ?bool $canCreate = null;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="can_update", type="boolean", nullable=true)
     */
    private ?bool $canUpdate = null;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="can_delete", type="boolean", nullable=true)
     */
    private ?bool $canDelete = null;

    /**
     * @var DataGroupPermission[]|Collection
     *
     * @ORM\OneToMany(targetEntity="ShantsHRM\Entity\DataGroupPermission", mappedBy="dataGroup")
     */
    private $dataGroupPermissions;

    /**
     * @var ApiPermission[]|Collection
     *
     * @ORM\OneToMany(targetEntity="ShantsHRM\Entity\ApiPermission", mappedBy="dataGroup")
     */
    private $apiPermissions;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function canRead(): bool
    {
        return (bool)$this->canRead;
    }

    /**
     * @param bool $canRead
     */
    public function setCanRead(bool $canRead): void
    {
        $this->canRead = $canRead;
    }

    /**
     * @return bool
     */
    public function canCreate(): bool
    {
        return (bool)$this->canCreate;
    }

    /**
     * @param bool $canCreate
     */
    public function setCanCreate(bool $canCreate): void
    {
        $this->canCreate = $canCreate;
    }

    /**
     * @return bool
     */
    public function canUpdate(): bool
    {
        return (bool)$this->canUpdate;
    }

    /**
     * @param bool $canUpdate
     */
    public function setCanUpdate(bool $canUpdate): void
    {
        $this->canUpdate = $canUpdate;
    }

    /**
     * @return bool
     */
    public function canDelete(): bool
    {
        return (bool)$this->canDelete;
    }

    /**
     * @param bool $canDelete
     */
    public function setCanDelete(bool $canDelete): void
    {
        $this->canDelete = $canDelete;
    }
}
