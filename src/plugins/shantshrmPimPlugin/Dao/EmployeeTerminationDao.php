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

namespace ShantsHRM\Pim\Dao;

use Exception;
use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Entity\EmployeeTerminationRecord;
use ShantsHRM\Entity\TerminationReason;
use ShantsHRM\ORM\ListSorter;

class EmployeeTerminationDao extends BaseDao
{
    /**
     * @param EmployeeTerminationRecord $employeeTerminationRecord
     * @return EmployeeTerminationRecord
     * @throws DaoException
     */
    public function saveEmployeeTermination(
        EmployeeTerminationRecord $employeeTerminationRecord
    ): EmployeeTerminationRecord {
        try {
            $this->persist($employeeTerminationRecord);
            return $employeeTerminationRecord;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param int $id
     * @return EmployeeTerminationRecord|null
     * @throws DaoException
     */
    public function getEmployeeTermination(int $id): ?EmployeeTerminationRecord
    {
        try {
            return $this->getRepository(EmployeeTerminationRecord::class)->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return TerminationReason[]
     * @throws DaoException
     */
    public function getTerminationReasonList(): array
    {
        try {
            $q = $this->createQueryBuilder(TerminationReason::class, 'tr');
            $q->addOrderBy('tr.name', ListSorter::ASCENDING);
            return $q->getQuery()->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
