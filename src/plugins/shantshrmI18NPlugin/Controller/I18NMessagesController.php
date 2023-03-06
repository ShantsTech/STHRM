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

namespace ShantsHRM\I18N\Controller;

use ShantsHRM\Config\Config;
use ShantsHRM\Core\Controller\AbstractFileController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Framework\Services;
use ShantsHRM\I18N\Service\I18NService;

class I18NMessagesController extends AbstractFileController implements PublicControllerInterface
{
    use ConfigServiceTrait;

    /**
     * @return I18NService
     */
    public function getI18NService(): I18NService
    {
        return $this->getContainer()->get(Services::I18N_SERVICE);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $locale = $request->query->get('locale');
        if (!$request->query->has('locale')) {
            $locale = $this->getConfigService()->getAdminLocalizationDefaultLanguage();
        }

        $response = $this->getResponse();
        $response->setEtag($this->getI18NService()->getETagByLangCode($locale));

        if (!$response->isNotModified($request)) {
            $response->setContent($this->getI18NService()->getTranslationMessagesAsJsonString($locale));
            $this->setCommonHeaders($response, 'application/json');
        }

        return $response;
    }

    private function setCommonHeaders($response, string $contentType)
    {
        $response->headers->set('Content-Type', $contentType);
        $response->setPublic();
        $response->setMaxAge(Config::get(Config::MAX_SESSION_IDLE_TIME));
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set('Pragma', 'Public');
    }
}
