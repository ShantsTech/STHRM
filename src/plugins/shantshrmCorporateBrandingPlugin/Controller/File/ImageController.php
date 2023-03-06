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

namespace ShantsHRM\CorporateBranding\Controller\File;

use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\CorporateBranding\Dto\ThemeImage;
use ShantsHRM\CorporateBranding\Traits\ThemeServiceTrait;
use ShantsHRM\Framework\Http\Request;

class ImageController extends AbstractFileController implements PublicControllerInterface
{
    use ThemeServiceTrait;

    /**
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $imageName = $request->attributes->get('imageName');
        $map = [
            'clientLogo' => 'client_logo',
            'clientBanner' => 'client_banner',
            'loginBanner' => 'login_banner',
        ];
        $imageKey = $map[$imageName];
        $response = $this->getResponse();
        $response->setEtag($this->getThemeService()->getImageETag($imageKey));

        if (!$response->isNotModified($request)) {
            $image = $this->getThemeService()->getImage($imageKey);
            if ($image instanceof ThemeImage) {
                $response->setContent($image->getContent());
                $this->setCommonHeaders($response, $image->getFileType());
            }
        }

        return $response;
    }

    private function setCommonHeaders($response, string $contentType)
    {
        $response->headers->set('Content-Type', $contentType);
        $response->setPublic();
        $response->setMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set('Pragma', 'Public');
    }
}
