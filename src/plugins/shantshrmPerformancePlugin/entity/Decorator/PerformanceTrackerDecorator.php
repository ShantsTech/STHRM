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

namespace ShantsHRM\Entity\Decorator;

use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\PerformanceTracker;

class PerformanceTrackerDecorator
{
    use EntityManagerHelperTrait;
    use DateTimeHelperTrait;

    protected PerformanceTracker $performanceTracker;

    /**
     * @param PerformanceTracker $performanceTracker
     */
    public function __construct(PerformanceTracker $performanceTracker)
    {
        $this->performanceTracker = $performanceTracker;
    }

    /**
     * @param int $empNumber
     * @return void
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getPerformanceTracker()->setEmployee($employee);
    }

    /**
     * @return PerformanceTracker
     */
    public function getPerformanceTracker(): PerformanceTracker
    {
        return $this->performanceTracker;
    }

    /**
     * @param int $empNumber
     * @return void
     */
    public function setAddedByByEmpNumber(int $empNumber): void
    {
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getPerformanceTracker()->setAddedBy($employee);
    }

    /**
     * @return string|null
     */
    public function getAddedDate(): ?string
    {
        return $this->getDateTimeHelper()->formatDate($this->getPerformanceTracker()->getAddedDate());
    }

    /**
     * @return string|null
     */
    public function getModifiedDate(): ?string
    {
        return $this->getDateTimeHelper()->formatDate($this->getPerformanceTracker()->getModifiedDate());
    }
}
