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

namespace ShantsHRM\Entity;

use Doctrine\ORM\Mapping as ORM;
use ShantsHRM\Entity\Decorator\DecoratorTrait;
use ShantsHRM\Entity\Decorator\SelectedDisplayFieldGroupDecorator;

/**
 * @method SelectedDisplayFieldGroupDecorator getDecorator()
 *
 * @ORM\Table(name="ohrm_selected_display_field_group")
 * @ORM\Entity
 */
class SelectedDisplayFieldGroup
{
    use DecoratorTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @var Report
     *
     * @ORM\ManyToOne(targetEntity="ShantsHRM\Entity\Report")
     * @ORM\JoinColumn(name="report_id", referencedColumnName="report_id")
     */
    private Report $report;

    /**
     * @var DisplayFieldGroup
     *
     * @ORM\ManyToOne(targetEntity="ShantsHRM\Entity\DisplayFieldGroup", inversedBy="selectedDisplayFieldGroups")
     * @ORM\JoinColumn(name="display_field_group_id", referencedColumnName="id")
     */
    private DisplayFieldGroup $displayFieldGroup;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Report
     */
    public function getReport(): Report
    {
        return $this->report;
    }

    /**
     * @param Report $report
     */
    public function setReport(Report $report): void
    {
        $this->report = $report;
    }

    /**
     * @return DisplayFieldGroup
     */
    public function getDisplayFieldGroup(): DisplayFieldGroup
    {
        return $this->displayFieldGroup;
    }

    /**
     * @param DisplayFieldGroup $displayFieldGroup
     */
    public function setDisplayFieldGroup(DisplayFieldGroup $displayFieldGroup): void
    {
        $this->displayFieldGroup = $displayFieldGroup;
    }
}
