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

namespace ShantsHRM\Admin\Controller\File;

use ShantsHRM\Admin\Traits\Service\LocalizationServiceTrait;
use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Core\Traits\Service\TextHelperTrait;
use ShantsHRM\Entity\I18NLanguage;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;

class LanguagePackage extends AbstractFileController
{
    use TextHelperTrait;
    use LocalizationServiceTrait;

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $response = $this->getResponse();

        if ($request->attributes->get('languageId')) {
            $languageId = $request->attributes->getInt('languageId');
            $language = $this->getLocalizationService()->getLocalizationDao()
                ->getLanguageById($languageId);

            if (!($language instanceof I18NLanguage)
                || !($language->isAdded()
                    && $language->isEnabled())
            ) {
                return $this->handleBadRequest($response);
            }

            $xliffContent = $this->getLocalizationService()
                ->exportLanguagePackage($language);

            $fileName = sprintf('i18n-%s.xml', $language->getCode());

            $this->setCommonHeadersToResponse(
                $fileName,
                'application/xml',
                $this->getTextHelper()->strLength($xliffContent, '8bit'),
                $response
            );
            $response->setContent($xliffContent);
            return $response;
        }
        return $this->handleBadRequest();
    }
}
