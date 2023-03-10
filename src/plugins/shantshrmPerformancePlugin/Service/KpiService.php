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

namespace ShantsHRM\Performance\Service;

use Exception;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Entity\Kpi;
use ShantsHRM\ORM\Exception\TransactionException;
use ShantsHRM\Performance\Dao\KpiDao;
use ShantsHRM\Performance\Exception\KpiServiceException;

class KpiService
{
    use EntityManagerHelperTrait;

    private ?KpiDao $kpiDao = null;

    /**
     * @return KpiDao
     */
    public function getKpiDao(): KpiDao
    {
        if (!($this->kpiDao instanceof KpiDao)) {
            $this->kpiDao = new KpiDao();
        }
        return $this->kpiDao;
    }

    /**
     * @param Kpi $kpi
     * @return Kpi
     * @throws KpiServiceException|TransactionException
     */
    public function saveKpi(Kpi $kpi): Kpi
    {
        if ($kpi->getMinRating() >= $kpi->getMaxRating()) {
            throw KpiServiceException::minGreaterThanMax();
        }
        $this->beginTransaction();
        try {
            $kpi = $this->getKpiDao()->saveKpi($kpi);
            if ($kpi->isDefaultKpi()) {
                $this->getKpiDao()->unsetDefaultKpi($kpi->getId());
            }
            $this->commitTransaction();
            return $kpi;
        } catch (Exception $e) {
            $this->rollBackTransaction();
            throw new TransactionException($e);
        }
    }
}
