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
use ShantsHRM\Entity\EmployeeSkill;
use ShantsHRM\Entity\Skill;
use ShantsHRM\Entity\Employee;

class EmployeeSkillDecorator
{
    use EntityManagerHelperTrait;

    /**
     * @var EmployeeSkill
     */
    protected EmployeeSkill $employeeSkill;

    /**
     * @param EmployeeSkill $employeeSkill
     */
    public function __construct(EmployeeSkill $employeeSkill)
    {
        $this->employeeSkill = $employeeSkill;
    }

    /**
     * @return EmployeeSkill
     */
    protected function getEmployeeSkill(): EmployeeSkill
    {
        return $this->employeeSkill;
    }

    /**
     * @param int $empNumber
     */
    public function setEmployeeByEmpNumber(int $empNumber): void
    {
        /** @var Employee|null $employee */
        $employee = $this->getReference(Employee::class, $empNumber);
        $this->getEmployeeSkill()->setEmployee($employee);
    }

    /**
     * @param int $skillId
     */
    public function setSkillBySkillId(int $skillId): void
    {
        /** @var Skill|null $skill */
        $skill = $this->getReference(Skill::class, $skillId);
        $this->getEmployeeSkill()->setSkill($skill);
    }
}
