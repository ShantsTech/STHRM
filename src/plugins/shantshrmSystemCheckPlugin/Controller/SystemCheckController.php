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

namespace ShantsHRM\SystemCheck\Controller;

use ShantsHRM\Config\Config;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Helper\VueControllerHelper;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\CorporateBranding\Dto\ThemeVariables;
use ShantsHRM\CorporateBranding\Traits\ThemeServiceTrait;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;

class SystemCheckController extends AbstractVueController implements PublicControllerInterface
{
    use ConfigServiceTrait;
    use ThemeServiceTrait;

    /***
     * @inheritDoc
     */
    public function init(): void
    {
        if (!$this->getConfigService()->showSystemCheckScreen()) {
            $response = $this->getResponse();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $this->setResponse($response);
        }
    }

    /***
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('system-check');
        $this->setComponent($component);
        $this->setTemplate('no_header.html.twig');
    }

    /***
     * @inheritDoc
     */
    public function render(Request $request): string
    {
        $this->getContext()->add(
            [
                VueControllerHelper::COMPONENT_NAME => $this->getComponent()->getName(),
                VueControllerHelper::COMPONENT_PROPS => $this->getComponent()->getProps(),
                VueControllerHelper::PUBLIC_PATH => $request->getBasePath(),
                VueControllerHelper::BASE_URL => $request->getBaseUrl(),
                VueControllerHelper::ASSETS_VERSION => Config::get(Config::VUE_BUILD_TIMESTAMP),
                VueControllerHelper::COPYRIGHT_YEAR => date('Y'),
                VueControllerHelper::PRODUCT_VERSION => Config::PRODUCT_VERSION,
                VueControllerHelper::PRODUCT_NAME => Config::PRODUCT_NAME,
                VueControllerHelper::THEME_VARIABLES => $this->getDefaultThemeVariables(),
            ]
        );
        return $this->getTwig()->render(
            $this->getTemplate(),
            $this->getContext()->all(),
        );
    }

    /**
     * @return array
     */
    private function getDefaultThemeVariables(): array
    {
        return $this->getThemeService()->getDerivedCssVariables(
            ThemeVariables::createFromArray([
                'primaryColor' => '#FF7B1D',
                'primaryFontColor' => '#FFFFFF',
                'secondaryColor' => '#76BC21',
                'secondaryFontColor' => '#FFFFFF',
                'primaryGradientStartColor' => '#FF920B',
                'primaryGradientEndColor' => '#F35C17'
            ])
        );
    }
}
