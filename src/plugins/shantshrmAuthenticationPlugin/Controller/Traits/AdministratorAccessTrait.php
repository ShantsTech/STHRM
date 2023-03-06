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

namespace ShantsHRM\Authentication\Controller\Traits;

use LogicException;
use ShantsHRM\Authentication\Controller\AdministratorAccessController;
use ShantsHRM\Authentication\Controller\AdminPrivilegeController;
use ShantsHRM\Authentication\Controller\ForbiddenController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\Service\TextHelperTrait;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Framework\Routing\UrlMatcher;
use ShantsHRM\Framework\Services;
use Symfony\Component\OptionsResolver\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

trait AdministratorAccessTrait
{
    use TextHelperTrait;

    /**
     * @param Request $request Provide request from handle method in controller
     * @return Response
     */
    public function forwardToAdministratorAccess(Request $request): Response
    {
        if (!$this instanceof AdminPrivilegeController) {
            throw new LogicException(
                'Trait should be used in class that implements ' . AdminPrivilegeController::class
            );
        }
        if (!$this instanceof AbstractVueController) {
            throw new LogicException(
                'Trait should be used in class that extends ' . AbstractVueController::class
            );
        }

        $currentRequest = $this->getCurrentRequest();
        $forwardUrl = $currentRequest->getPathInfo();

        $backUrl = $request->headers->get('referer');

        // Some instances where null: if page is accessed via bookmark, user manually entered URL, etc.
        if (is_null($backUrl)) {
            return $this->forward(
                AdministratorAccessController::class . '::handle',
                [],
                ['forward' => $forwardUrl, 'back' => $backUrl]
            );
        }

        $baseUrl = $currentRequest->getSchemeAndHttpHost() . $currentRequest->getBaseUrl();
        $textHelper = $this->getTextHelper();

        // Will fail if backUrl: contains a different base url or host || contains api/v2 in the string
        if (!$textHelper->strContains($backUrl, $baseUrl) || $textHelper->strContains($backUrl, 'api/v2')) {
            throw new RequestForwardableException(ForbiddenController::class . '::handle');
        }

        $formattedBackUrl = str_replace($baseUrl, '', $backUrl);
        /** @var UrlMatcher $urlMatcher */
        $urlMatcher = $this->getContainer()->get(Services::ROUTER);
        try {
            $urlMatcher->match($formattedBackUrl);
        } catch (ResourceNotFoundException | NoConfigurationException | MethodNotAllowedException $e) {
            throw new RequestForwardableException(ForbiddenController::class . '::handle');
        }

        return $this->forward(
            AdministratorAccessController::class . '::handle',
            [],
            ['forward' => $forwardUrl, 'back' => $formattedBackUrl]
        );
    }
}
