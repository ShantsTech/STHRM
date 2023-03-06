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

namespace ShantsHRM\Admin\Controller;

use ShantsHRM\Admin\Traits\Service\LocalizationServiceTrait;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Framework\Http\Request;

class LocalizationController extends AbstractVueController
{
    use LocalizationServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('localization-configuration');

        $component->addProp(
            new Prop(
                'language-list',
                Prop::TYPE_ARRAY,
                $this->getLocalizationService()->getSupportedLanguages()
            )
        );
        $component->addProp(
            new Prop(
                'date-format-list',
                Prop::TYPE_ARRAY,
                $this->getLocalizationService()->getLocalizationDateFormats()
            )
        );
        $this->setComponent($component);
    }
}
