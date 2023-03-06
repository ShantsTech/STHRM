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

namespace ShantsHRM\Authentication\Controller;

use ShantsHRM\Authentication\Traits\CsrfTokenManagerTrait;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Service\EmailService;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Framework\Http\Request;

class RequestPasswordController extends AbstractVueController implements PublicControllerInterface
{
    use CsrfTokenManagerTrait;

    protected ?EmailService $emailService = null;

    /**
     * @return EmailService
     */
    public function getEmailService(): EmailService
    {
        if (!$this->emailService instanceof EmailService) {
            $this->emailService = new EmailService();
        }
        return $this->emailService;
    }

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($this->getEmailService()->isConfigSet()) {
            $component = new Component('request-reset-password');
            $component->addProp(
                new Prop(
                    'token',
                    Prop::TYPE_STRING,
                    $this->getCsrfTokenManager()->getToken('request-reset-password')->getValue()
                )
            );
        } else {
            $component = new Component('email-configuration-warning');
        }

        $this->setTemplate('no_header.html.twig');
        $this->setComponent($component);
    }
}
