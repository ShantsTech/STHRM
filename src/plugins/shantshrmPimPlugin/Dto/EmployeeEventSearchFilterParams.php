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

namespace ShantsHRM\Pim\Dto;

use ShantsHRM\Core\Dto\DateRange;
use ShantsHRM\Core\Dto\FilterParams;

class EmployeeEventSearchFilterParams extends FilterParams
{
    private ?int $empNumber = null;
    private ?DateRange $dateRange = null;
    private ?string $type = null;
    private ?string $event = null;

    /**
     * @return int|null
     */
    public function getEmpNumber(): ?int
    {
        return $this->empNumber;
    }

    /**
     * @param int|null $empNumber
     */
    public function setEmpNumber(?int $empNumber): void
    {
        $this->empNumber = $empNumber;
    }

    /**
     * @return DateRange|null
     */
    public function getDateRange(): ?DateRange
    {
        return $this->dateRange;
    }

    /**
     * @param DateRange|null $dateRange
     */
    public function setDateRange(?DateRange $dateRange): void
    {
        $this->dateRange = $dateRange;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @param string|null $event
     */
    public function setEvent(?string $event): void
    {
        $this->event = $event;
    }
}
