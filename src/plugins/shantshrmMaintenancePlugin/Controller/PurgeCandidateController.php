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

namespace ShantsHRM\Maintenance\Controller;

use ShantsHRM\Authentication\Controller\AdminPrivilegeController;
use ShantsHRM\Authentication\Controller\Traits\AdministratorAccessTrait;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Framework\Http\Request;

class PurgeCandidateController extends AbstractVueController implements AdminPrivilegeController
{
    use AuthUserTrait;
    use AdministratorAccessTrait;
    use ConfigServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        $component = new Component('purge-candidate');

        $component->addProp(
            new Prop('instance-identifier', Prop::TYPE_STRING, $this->getConfigService()->getInstanceIdentifier())
        );

        $this->setComponent($component);
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request)
    {
        if (!$this->getAuthUser()->getHasAdminAccess()) {
            return $this->forwardToAdministratorAccess($request);
        }
        return parent::handle($request);
    }
}
