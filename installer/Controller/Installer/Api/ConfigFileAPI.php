<?php
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 Shants Tech LLC., http://www.hrm.shants-tech.com
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

namespace ShantsHRM\Installer\Controller\Installer\Api;

use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\Core\Exception\KeyHandlerException;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Installer\Util\AppSetupUtility;
use ShantsHRM\Installer\Util\DataRegistrationUtility;
use ShantsHRM\Installer\Util\StateContainer;

class ConfigFileAPI extends \ShantsHRM\Installer\Controller\Upgrader\Api\ConfigFileAPI
{
    /**
     * @inheritDoc
     */
    protected function handlePost(Request $request): array
    {
        if (StateContainer::getInstance()->isSetDbInfo()) {
            $dbInfo = StateContainer::getInstance()->getDbInfo();

            if ($dbInfo[StateContainer::ENABLE_DATA_ENCRYPTION]) {
                try {
                    $appSetupUtility = new AppSetupUtility();
                    $appSetupUtility->writeKeyFile();
                } catch (KeyHandlerException $exception) {
                    $this->getResponse()->setStatusCode(Response::HTTP_CONFLICT);
                    return
                        [
                            'error' => [
                                'status' => $this->getResponse()->getStatusCode(),
                                'message' => $exception->getMessage()
                            ]
                        ];
                }
            }

            $dbUser = $dbInfo[StateContainer::ORANGEHRM_DB_USER] ?? $dbInfo[StateContainer::DB_USER];
            $dbPassword = isset($dbInfo[StateContainer::ORANGEHRM_DB_USER])
                ? $dbInfo[StateContainer::ORANGEHRM_DB_PASSWORD]
                : $dbInfo[StateContainer::DB_PASSWORD];
            StateContainer::getInstance()->storeDbInfo(
                $dbInfo[StateContainer::DB_HOST],
                $dbInfo[StateContainer::DB_PORT],
                new UserCredential($dbUser, $dbPassword),
                $dbInfo[StateContainer::DB_NAME]
            );
        }
        return parent::handlePost($request);
    }

    /**
     * @inheritDoc
     */
    protected function getRegistrationType(): int
    {
        return DataRegistrationUtility::REGISTRATION_TYPE_INSTALLER_STARTED;
    }
}
