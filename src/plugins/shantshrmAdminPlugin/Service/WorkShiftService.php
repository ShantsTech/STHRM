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

use DateTime;
use ShantsHRM\Admin\Dao\WorkShiftDao;
use ShantsHRM\Admin\Dto\WorkShiftSearchFilterParams;
use ShantsHRM\Admin\Dto\WorkShiftStartAndEndTime;
use ShantsHRM\Core\Exception\DaoException;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Entity\WorkShift;

class WorkShiftService
{
    use ConfigServiceTrait;
    private ?WorkShiftDao $workShiftDao = null;

    /**
     * @return WorkShiftStartAndEndTime
     */
    public function getWorkShiftDefaultStartAndEndTime(): WorkShiftStartAndEndTime
    {
        $startTime = $this->getConfigService()->getDefaultWorkShiftStartTime();
        $endTime = $this->getConfigService()->getDefaultWorkShiftEndTime();

        return new WorkShiftStartAndEndTime(new DateTime($startTime), new DateTime($endTime));
    }

    /**
     * @param $id
     * @return WorkShift|null
     */
    public function getWorkShiftById($id): ?WorkShift
    {
        return $this->getWorkShiftDao()->getWorkShiftById($id);
    }

    /**
     * @return WorkShiftDao
     */
    public function getWorkShiftDao(): WorkShiftDao
    {
        if (!$this->workShiftDao instanceof WorkShiftDao) {
            $this->workShiftDao = new WorkShiftDao();
        }
        return $this->workShiftDao;
    }

    /**
     * @param WorkShiftDao $workShiftDao
     */
    public function setWorkShiftDao(WorkShiftDao $workShiftDao): void
    {
        $this->workShiftDao = $workShiftDao;
    }

    /**
     * @param WorkShiftSearchFilterParams $workShiftSearchFilterParams
     * @return array
     * @throws DaoException
     */
    public function getWorkShiftList(WorkShiftSearchFilterParams $workShiftSearchFilterParams): array
    {
        return $this->getWorkShiftDao()->getWorkShiftList($workShiftSearchFilterParams);
    }

    /**
     * @param WorkShiftSearchFilterParams $workShiftSearchFilterParams
     * @return int
     * @throws DaoException
     */
    public function getWorkShiftCount(WorkShiftSearchFilterParams $workShiftSearchFilterParams): int
    {
        return $this->getWorkShiftDao()->getWorkShiftCount($workShiftSearchFilterParams);
    }
}
