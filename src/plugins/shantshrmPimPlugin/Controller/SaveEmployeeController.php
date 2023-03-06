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

namespace ShantsHRM\Pim\Controller;

use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Service\IDGeneratorService;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\EmpPicture;
use ShantsHRM\Framework\Http\Request;

class SaveEmployeeController extends AbstractVueController
{
    protected ?IDGeneratorService $idGeneratorService = null;

    /**
     * @return IDGeneratorService|null
     */
    public function getIdGeneratorService(): ?IDGeneratorService
    {
        if (!$this->idGeneratorService instanceof IDGeneratorService) {
            $this->idGeneratorService = new IDGeneratorService();
        }
        return $this->idGeneratorService;
    }

    public function preRender(Request $request): void
    {
        $component = new Component('employee-save');
        $employeeId = $this->getIdGeneratorService()->getNextID(Employee::class, false);
        $component->addProp(new Prop('emp-id', Prop::TYPE_NUMBER, $employeeId));
        $component->addProp(new Prop('allowed-image-types', Prop::TYPE_ARRAY, EmpPicture::ALLOWED_IMAGE_TYPES));
        $this->setComponent($component);
    }
}
