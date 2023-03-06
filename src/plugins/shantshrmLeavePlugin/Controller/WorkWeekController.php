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
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\WorkWeek;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\I18N\Traits\Service\I18NHelperTrait;

class WorkWeekController extends AbstractVueController
{
    use ServiceContainerTrait;
    use I18NHelperTrait;
    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('work-week');
        $dayTypes = [
            ["id" => WorkWeek::WORKWEEK_LENGTH_FULL_DAY, "label" => 'Full Day'],
            ["id" => WorkWeek::WORKWEEK_LENGTH_HALF_DAY, "label" => 'Half Day'],
            ["id" => WorkWeek::WORKWEEK_LENGTH_NON_WORKING_DAY, "label" => 'Non-working Day']
        ];
        $component->addProp(
            new Prop(
                'day-types',
                Prop::TYPE_ARRAY,
                array_map(
                    fn (array $dayType) => [
                        'id' => $dayType['id'],
                        'label' => $this->getI18NHelper()->transBySource($dayType['label'])
                    ],
                    $dayTypes
                )
            )
        );
        $this->setComponent($component);
    }

    /**
     * @inheritDoc
     */
    protected function getDataGroupsForCapabilityCheck(): array
    {
        return ['work_week'];
    }
}
