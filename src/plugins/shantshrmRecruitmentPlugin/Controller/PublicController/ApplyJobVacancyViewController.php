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

namespace ShantsHRM\Recruitment\Controller\PublicController;

use ShantsHRM\Authentication\Traits\CsrfTokenManagerTrait;
use ShantsHRM\Config\Config;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\CorporateBranding\Traits\ThemeServiceTrait;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Recruitment\Service\RecruitmentAttachmentService;
use ShantsHRM\Recruitment\Traits\Service\VacancyServiceTrait;

class ApplyJobVacancyViewController extends AbstractVueController implements PublicControllerInterface
{
    use ThemeServiceTrait;
    use ConfigServiceTrait;
    use CsrfTokenManagerTrait;
    use VacancyServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $id = $request->attributes->getInt('id');
        $vacancy = $this->getVacancyService()
            ->getVacancyDao()
            ->getVacancyById($id);
        if (!$vacancy instanceof Vacancy || !$vacancy->getDecorator()->isActiveAndPublished()) {
            $this->setResponse($this->handleBadRequest());
            return;
        }

        $assetsVersion = Config::get(Config::VUE_BUILD_TIMESTAMP);
        $bannerUrl = $request->getBasePath()
            . "/images/ohrm_branding.png?$assetsVersion";
        if (!is_null($this->getThemeService()->getImageETag('client_banner'))) {
            $bannerUrl = $request->getBaseUrl()
                . "/admin/theme/image/clientBanner?$assetsVersion";
        }

        $component = new Component('apply-job-vacancy');
        $component->addProp(new Prop('vacancy-id', Prop::TYPE_NUMBER, $id));
        $component->addProp(new Prop('success', Prop::TYPE_BOOLEAN, $request->query->getBoolean('success', false)));
        $component->addProp(
            new Prop('banner-src', Prop::TYPE_STRING, $bannerUrl)
        );
        $component->addProp(
            new Prop(
                'allowed-file-types',
                Prop::TYPE_ARRAY,
                RecruitmentAttachmentService::ALLOWED_CANDIDATE_ATTACHMENT_FILE_TYPES
            )
        );
        $component->addProp(
            new Prop(
                'token',
                Prop::TYPE_STRING,
                $this->getCsrfTokenManager()->getToken('recruitment-applicant')->getValue()
            )
        );
        $component->addProp(
            new Prop('max-file-size', Prop::TYPE_NUMBER, $this->getConfigService()->getMaxAttachmentSize())
        );
        $this->setComponent($component);
        $this->setTemplate('no_header.html.twig');
    }

    /**
     * @inheritDoc
     */
    protected function handleBadRequest(?Response $response = null): Response
    {
        return ($response ?? $this->getResponse())->setStatusCode(Response::HTTP_BAD_REQUEST);
    }
}
