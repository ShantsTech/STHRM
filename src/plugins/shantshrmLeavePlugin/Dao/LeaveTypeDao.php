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

namespace ShantsHRM\Leave\Dao;

use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Entity\LeaveRequest;
use ShantsHRM\Entity\LeaveType;
use ShantsHRM\Leave\Dto\LeaveTypeSearchFilterParams;
use ShantsHRM\ORM\Paginator;

class LeaveTypeDao extends BaseDao
{
    /**
     * @return LeaveType[]
     */
    public function getLeaveTypeList(): array
    {
        $q = $this->createQueryBuilder(LeaveType::class, 'leaveType');
        $q->andWhere('leaveType.deleted = :deleted')
            ->setParameter('deleted', false);
        $q->orderBy('leaveType.name');

        return $q->getQuery()->execute();
    }

    /**
     * @param int $id
     * @return LeaveType|null
     */
    public function getLeaveTypeById(int $id): ?LeaveType
    {
        return $this->getRepository(LeaveType::class)->find($id);
    }

    /**
     * @param LeaveType $leaveType
     * @return LeaveType
     */
    public function saveLeaveType(LeaveType $leaveType): LeaveType
    {
        $this->persist($leaveType);
        return $leaveType;
    }

    /**
     * @param int[] $idsToDelete
     * @return int
     */
    public function deleteLeaveType(array $idsToDelete): int
    {
        $q = $this->createQueryBuilder(LeaveType::class, 'leaveType');
        $q->update();
        $q->where($q->expr()->in('leaveType.id', ':ids'))
            ->setParameter('ids', $idsToDelete);
        $q->set('leaveType.deleted', ':deleted')
            ->setParameter('deleted', true);

        return $q->getQuery()->execute();
    }

    /**
     * @return LeaveType[]
     */
    public function getDeletedLeaveTypeList(): array
    {
        $q = $this->createQueryBuilder(LeaveType::class, 'leaveType');
        $q->andWhere('leaveType.deleted = :deleted')
            ->setParameter('deleted', true);
        $q->orderBy('leaveType.name');

        return $q->getQuery()->execute();
    }

    /**
     * @param $leaveTypeName
     * @return LeaveType|null
     */
    public function getLeaveTypeByName($leaveTypeName): ?LeaveType
    {
        $q = $this->createQueryBuilder(LeaveType::class, 'leaveType');
        $q->andWhere('leaveType.name = :name')
            ->setParameter('name', $leaveTypeName);
        $q->andWhere('leaveType.deleted = :deleted')
            ->setParameter('deleted', false);

        return $this->fetchOne($q);
    }

    /**
     * @param int $leaveTypeId
     * @return LeaveType|null
     */
    public function undeleteLeaveType(int $leaveTypeId): ?LeaveType
    {
        $leaveType = $this->getLeaveTypeById($leaveTypeId);
        if ($leaveType instanceof LeaveType) {
            $leaveType->setDeleted(false);
            $this->persist($leaveType);
        }
        return $leaveType;
    }

    /**
     * Search Leave Type
     *
     * @param LeaveTypeSearchFilterParams $leaveTypeSearchParams
     * @return array
     */
    public function searchLeaveType(LeaveTypeSearchFilterParams $leaveTypeSearchParams): array
    {
        $paginator = $this->getSearchLeaveTypePaginator($leaveTypeSearchParams);
        return $paginator->getQuery()->execute();
    }

    /**
     * @param LeaveTypeSearchFilterParams $leaveTypeSearchParams
     * @return Paginator
     */
    private function getSearchLeaveTypePaginator(LeaveTypeSearchFilterParams $leaveTypeSearchParams): Paginator
    {
        $q = $this->createQueryBuilder(LeaveType::class, 'leaveType');
        $this->setSortingAndPaginationParams($q, $leaveTypeSearchParams);

        if (!empty($leaveTypeSearchParams->getName())) {
            $q->andWhere('leaveType.name = :name');
            $q->setParameter('name', $leaveTypeSearchParams->getName());
        }
        $q->andWhere('leaveType.deleted = :deleted')
            ->setParameter('deleted', false);
        return $this->getPaginator($q);
    }

    /**
     * Get Count of Search Query
     *
     * @param LeaveTypeSearchFilterParams $leaveTypeSearchParams
     * @return int
     */
    public function getSearchLeaveTypesCount(LeaveTypeSearchFilterParams $leaveTypeSearchParams): int
    {
        $paginator = $this->getSearchLeaveTypePaginator($leaveTypeSearchParams);
        return $paginator->count();
    }

    /**
     * @param int $empNumber
     * @param int $leaveTypeId
     * @return bool
     */
    public function hasEmployeeAllocatedLeavesForLeaveType(int $empNumber, int $leaveTypeId): bool
    {
        $q = $this->createQueryBuilder(LeaveRequest::class, 'lr')
            ->andWhere('lr.employee = :empNumber')
            ->setParameter('empNumber', $empNumber)
            ->andWhere('lr.leaveType = :leaveTypeId')
            ->setParameter('leaveTypeId', $leaveTypeId);
        return $this->getPaginator($q)->count() > 0;
    }
}
