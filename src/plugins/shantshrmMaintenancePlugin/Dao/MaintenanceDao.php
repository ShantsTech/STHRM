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
 * Boston, MA 02110-1301, USA
 */

namespace ShantsHRM\Maintenance\Dao;

use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Core\Exception\DaoException;

//use Doctrine_Collection;
//use Doctrine_Query;

/**
 * Class MaintenanceDao
 */
class MaintenanceDao extends BaseDao
{
    /**
     * @param $empNumber
     * @param $table
     * @return mixed
     * @throws DaoException
     */
    public function extractDataFromEmpNumber($matchByValues, $table): array
    {
        $empNumber = reset($matchByValues);
        $field = key($matchByValues);
        $entity = 'entity';

        $qb = $this->createQueryBuilder('ShantsHRM\\Entity\\' . $table, 'entity');
        if (isset($matchByValues['join'])) {
            $qb->innerJoin('entity.' . $matchByValues['join'], 'joinEntity');
            $entity = 'joinEntity';
        }
        $qb->andWhere($qb->expr()->eq($entity . '.' . $field, ':empNumber'))
            ->setParameter('empNumber', $empNumber);

        return $qb->getQuery()->execute();
    }
}
