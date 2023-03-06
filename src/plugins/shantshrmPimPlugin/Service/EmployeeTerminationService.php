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

namespace ShantsHRM\Pim\Service;

use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Pim\Dao\EmployeeTerminationDao;
use ShantsHRM\Pim\Service\Model\TerminationReasonModel;

class EmployeeTerminationService
{
    use NormalizerServiceTrait;

    /**
     * @var EmployeeTerminationDao|null
     */
    protected ?EmployeeTerminationDao $employeeTerminationDao = null;

    /**
     * @return EmployeeTerminationDao
     */
    public function getEmployeeTerminationDao(): EmployeeTerminationDao
    {
        if (!$this->employeeTerminationDao instanceof EmployeeTerminationDao) {
            $this->employeeTerminationDao = new EmployeeTerminationDao();
        }
        return $this->employeeTerminationDao;
    }

    /**
     * @return array
     */
    public function getTerminationReasonsArray(): array
    {
        $terminationReasons = $this->getEmployeeTerminationDao()->getTerminationReasonList();
        return $this->getNormalizerService()->normalizeArray(TerminationReasonModel::class, $terminationReasons);
    }
}
