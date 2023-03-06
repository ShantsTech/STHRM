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

namespace ShantsHRM\Pim\Controller;

use Exception;
use ShantsHRM\Core\Authorization\Controller\CapableViewController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Helper\VueControllerHelper;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Pim\Service\PIMLeftMenuService;

abstract class BaseViewEmployeeController extends AbstractVueController implements CapableViewController
{
    use ConfigServiceTrait;
    use UserRoleManagerTrait;

    /**
     * @var PIMLeftMenuService|null
     */
    protected ?PIMLeftMenuService $pimLeftMenuService = null;

    /**
     * @return PIMLeftMenuService|null
     */
    public function getPimLeftMenuService(): ?PIMLeftMenuService
    {
        if (!$this->pimLeftMenuService instanceof PIMLeftMenuService) {
            $this->pimLeftMenuService = new PIMLeftMenuService();
        }
        return $this->pimLeftMenuService;
    }

    /**
     * @inheritDoc
     */
    public function render(Request $request): string
    {
        $empNumber = $request->attributes->get('empNumber');
        if (empty($empNumber)) {
            throw new Exception('`empNumber` required attribute for ' . __METHOD__);
        }
        $menuTabs = $this->getPimLeftMenuService()->getPreparedMenuItems($empNumber);
        $this->getComponent()->addProp(
            new Prop('tabs', Prop::TYPE_ARRAY, $menuTabs)
        );
        $this->getComponent()->addProp(
            new Prop('allowed-file-types', Prop::TYPE_ARRAY, $this->getConfigService()->getAllowedFileTypes())
        );
        $this->getComponent()->addProp(
            new Prop('max-file-size', Prop::TYPE_NUMBER, $this->getConfigService()->getMaxAttachmentSize())
        );
        return parent::render($request);
    }

    /**
     * @return string[]
     */
    abstract protected function getDataGroupsForCapabilityCheck(): array;

    /**
     * @inheritDoc
     */
    public function isCapable(Request $request): bool
    {
        $empNumber = $request->attributes->get('empNumber');
        if (!$this->isEmployeeAccessible($empNumber)) {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        $permission = $this->getUserRoleManagerHelper()->getDataGroupPermissionsForEmployee(
            $this->getDataGroupsForCapabilityCheck(),
            $empNumber
        );
        return $permission->canRead();
    }

    /**
     * @param array $dataGroups
     * @param int $empNumber
     */
    protected function setPermissionsForEmployee(array $dataGroups, int $empNumber)
    {
        $permissions = $this->getUserRoleManagerHelper()->getDataGroupPermissionCollectionForEmployee(
            $dataGroups,
            $empNumber
        );
        $this->getContext()->set(
            VueControllerHelper::PERMISSIONS,
            $permissions->toArray()
        );
    }

    /**
     * @param int|null $empNumber
     * @return bool
     */
    protected function isEmployeeAccessible(?int $empNumber): bool
    {
        return $this->getUserRoleManager()->isEntityAccessible(Employee::class, $empNumber) ||
            $this->getUserRoleManagerHelper()->isSelfByEmpNumber($empNumber);
    }
}
