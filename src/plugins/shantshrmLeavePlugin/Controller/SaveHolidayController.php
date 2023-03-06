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

namespace ShantsHRM\Leave\Controller;

use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Holiday;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;

class SaveHolidayController extends AbstractVueController
{
    use ConfigServiceTrait;

    public const HOLIDAY_LENGTH_LIST = [
        ['id' => Holiday::HOLIDAY_HALF_DAY_LENGTH, 'label' => Holiday::HOLIDAY_HALF_DAY_LENGTH_NAME],
        ['id' => Holiday::HOLIDAY_FULL_DAY_LENGTH, 'label' => Holiday::HOLIDAY_FULL_DAY_LENGTH_NAME],
    ];

    public function preRender(Request $request): void
    {
        if ($request->attributes->has('id')) {
            $component = new Component('holiday-edit');
            $component->addProp(new Prop('holiday-id', Prop::TYPE_NUMBER, $request->attributes->getInt('id')));
        } else {
            $component = new Component('holiday-save');
        }
        $component->addProp(new Prop('holiday-length-list', Prop::TYPE_ARRAY, self::HOLIDAY_LENGTH_LIST));
        $this->setComponent($component);
    }
}
