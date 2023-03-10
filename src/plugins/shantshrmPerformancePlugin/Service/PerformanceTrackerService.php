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

use ShantsHRM\Entity\PerformanceTracker;
use ShantsHRM\Performance\Dao\PerformanceTrackerDao;
use ShantsHRM\Performance\Exception\PerformanceTrackerServiceException;

class PerformanceTrackerService
{
    /**
     * @var PerformanceTrackerDao|null
     */
    private ?PerformanceTrackerDao $performanceTrackerDao = null;

    /**
     * @return PerformanceTrackerDao
     */
    public function getPerformanceTrackerDao(): PerformanceTrackerDao
    {
        if (!($this->performanceTrackerDao instanceof PerformanceTrackerDao)) {
            $this->performanceTrackerDao = new PerformanceTrackerDao();
        }
        return $this->performanceTrackerDao;
    }

    /**
     * @param PerformanceTracker $performanceTracker
     * @param array $reviewerEmpNumbers
     * @return void
     * @throws PerformanceTrackerServiceException
     */
    public function updateTracker(PerformanceTracker $performanceTracker, array $reviewerEmpNumbers): void
    {
        if (! $this->getPerformanceTrackerDao()
            ->isTrackerOwnerEditable($performanceTracker->getId())
        ) {
            throw PerformanceTrackerServiceException::cannotEditEmployeeWithLogs();
        }
        $this->getPerformanceTrackerDao()->updatePerformanceTracker($performanceTracker, $reviewerEmpNumbers);
    }
}
