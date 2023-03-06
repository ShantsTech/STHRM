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

namespace ShantsHRM\Admin\Service;

use ShantsHRM\Admin\Dao\CompanyStructureDao;
use ShantsHRM\Admin\Service\Model\SubunitModel;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Entity\Subunit;

class CompanyStructureService
{
    use NormalizerServiceTrait;

    /**
     * @var CompanyStructureDao|null
     */
    private ?CompanyStructureDao $companyStructureDao = null;

    /**
     * @return CompanyStructureDao
     */
    public function getCompanyStructureDao(): CompanyStructureDao
    {
        if (!($this->companyStructureDao instanceof CompanyStructureDao)) {
            $this->companyStructureDao = new CompanyStructureDao();
        }
        return $this->companyStructureDao;
    }

    /**
     * @param CompanyStructureDao $companyStructureDao
     */
    public function setCompanyStructureDao(CompanyStructureDao $companyStructureDao): void
    {
        $this->companyStructureDao = $companyStructureDao;
    }

    /**
     * Get sub unit for a given id
     *
     * @param int $id Subunit auto incremental id
     * @return Subunit instance if found or a dao exception
     */
    public function getSubunitById(int $id): ?Subunit
    {
        return $this->getCompanyStructureDao()->getSubunitById($id);
    }

    /**
     * @param Subunit $subunit
     * @return Subunit
     */
    public function saveSubunit(Subunit $subunit): Subunit
    {
        return $this->getCompanyStructureDao()->saveSubunit($subunit);
    }

    /**
     * Add child subunit to a parent subunit
     * @param Subunit $parentSubunit
     * @param Subunit $subunit
     */
    public function addSubunit(Subunit $parentSubunit, Subunit $subunit): void
    {
        $this->getCompanyStructureDao()->addSubunit($parentSubunit, $subunit);
    }

    /**
     * Delete subunit
     *
     * This will delete the passed subunit and it's children
     *
     * @param Subunit $subunit
     */
    public function deleteSubunit(Subunit $subunit): void
    {
        $this->getCompanyStructureDao()->deleteSubunit($subunit);
    }

    /**
     * Set the organization name to the root of the tree. Previously the root has the name
     * 'Organization' then if the company name is set this will update the root node of the tree
     *
     * @param string $name
     * @return int - affected rows
     */
    public function setOrganizationName(string $name): int
    {
        return $this->getCompanyStructureDao()->setOrganizationName($name);
    }

    /**
     * Get the whole subunit tree
     *
     * @param int|null $depth
     * @return array|Subunit[] Subunit object list
     */
    public function getSubunitTree(?int $depth = null): array
    {
        return $this->getCompanyStructureDao()->getSubunitTree($depth);
    }

    /**
     * @return array
     */
    public function getSubunitArray(): array
    {
        $subunits = $this->getSubunitTree();
        unset($subunits[0]); // remove root node
        return $this->getNormalizerService()->normalizeArray(SubunitModel::class, $subunits);
    }

    /**
     * @param int $subunitId
     * @return int[]
     */
    public function getSubunitChainById(int $subunitId): array
    {
        $nestedSubunits = [];
        $subunit = $this->getSubunitById($subunitId);
        if (is_null($subunit)) {
            return $nestedSubunits;
        }

        $depth = $this->getCompanyStructureDao()->getMaxLevel() - $subunit->getLevel();
        $children = $subunit->getNode()->getChildren($depth);
        $nestedSubunits[] = $subunit->getId();

        foreach ($children as $child) {
            $nestedSubunits[] = $child->getId();
        }

        return $nestedSubunits;
    }
}
