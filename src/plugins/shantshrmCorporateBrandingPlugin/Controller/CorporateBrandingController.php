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

namespace ShantsHRM\CorporateBranding\Controller;

use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Theme;
use ShantsHRM\Framework\Http\Request;

class CorporateBrandingController extends AbstractVueController
{
    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('corporate-branding');
        $component->addProp(new Prop('allowed-image-types', Prop::TYPE_ARRAY, Theme::ALLOWED_IMAGE_TYPES));
        $component->addProp(new Prop('aspect-ratios', Prop::TYPE_OBJECT, [
            'clientLogo' => Theme::CLIENT_LOGO_ASPECT_RATIO,
            'clientBanner' => Theme::CLIENT_BANNER_ASPECT_RATIO,
            'loginBanner' => Theme::LOGIN_BANNER_ASPECT_RATIO,
        ]));
        $component->addProp(new Prop('aspect-ratio-tolerance', Prop::TYPE_NUMBER, Theme::IMAGE_ASPECT_RATIO_TOLERANCE));
        $this->setComponent($component);
    }
}
