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
 * Boston, MA 02110-1301, USA
 */

namespace ShantsHRM\CorporateBranding\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\CorporateBranding\Dto\PartialTheme;

class ThemeModel implements Normalizable
{
    use ModelTrait {
        ModelTrait::toArray as entityToArray;
    }

    public function __construct(PartialTheme $theme)
    {
        $this->setEntity($theme);
        $this->setFilters(
            [
                'name',
                'variables',
                ['showSocialMediaIcons'],
            ]
        );
        $this->setAttributeNames(
            [
                'name',
                'variables',
                'showSocialMediaImages',
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = $this->entityToArray();
        $result['clientLogo'] = null;
        $result['clientBanner'] = null;
        $result['loginBanner'] = null;
        /** @var PartialTheme $theme */
        $theme = $this->getEntity();
        is_null($theme->getClientLogoFilename())
            ?: $result['clientLogo'] = ['filename' => $theme->getClientLogoFilename()];
        is_null($theme->getClientBannerFilename())
            ?: $result['clientBanner'] = ['filename' => $theme->getClientBannerFilename()];
        is_null($theme->getLoginBannerFilename())
            ?: $result['loginBanner'] = ['filename' => $theme->getLoginBannerFilename()];
        return $result;
    }
}
