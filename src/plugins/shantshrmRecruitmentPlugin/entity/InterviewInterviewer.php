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

/**
 * @ORM\Table(name="ohrm_job_interview_interviewer")
 * @ORM\Entity
 */
class InterviewInterviewer
{
    /**
     * @var Interview
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ShantsHRM\Entity\Interview", cascade={"persist"})
     * @ORM\JoinColumn(name="interview_id", referencedColumnName="id", nullable=false)
     */
    private Interview $interview;

    /**
     * @var Employee
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ShantsHRM\Entity\Employee", cascade={"persist"})
     * @ORM\JoinColumn(name="interviewer_id", referencedColumnName="emp_number", nullable=false)
     */
    private Employee $interviewer;
}
