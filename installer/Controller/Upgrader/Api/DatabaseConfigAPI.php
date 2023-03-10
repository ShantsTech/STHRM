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

namespace ShantsHRM\Installer\Controller\Upgrader\Api;

use ShantsHRM\Authentication\Dto\UserCredential;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Installer\Controller\AbstractInstallerRestController;
use ShantsHRM\Installer\Exception\SystemCheckException;
use ShantsHRM\Installer\Util\StateContainer;
use ShantsHRM\Installer\Util\UpgraderConfigUtility;

class DatabaseConfigAPI extends AbstractInstallerRestController
{
    /**
     * @inheritDoc
     */
    protected function handlePost(Request $request): array
    {
        $dbHost = $request->request->get('dbHost');
        $dbPort = $request->request->get('dbPort');
        $dbUser = $request->request->get('dbUser');
        $dbPassword = $request->request->get('dbPassword');
        $dbName = $request->request->get('dbName');

        StateContainer::getInstance()->storeDbInfo($dbHost, $dbPort, new UserCredential($dbUser, $dbPassword), $dbName);

        $response = $this->getResponse();
        $upgraderConfigUtility = new UpgraderConfigUtility();
        try {
            $upgraderConfigUtility->checkDatabaseConnection();
        } catch (SystemCheckException $e) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            return
                [
                    'error' => [
                        'status' => $response->getStatusCode(),
                        'message' => $e->getMessage(),
                    ]
                ];
        }

        return [
            'data' => [
                'dbHost' => $dbHost,
                'dbPort' => $dbPort,
                'dbUser' => $dbUser,
                'dbName' => $dbName,
            ],
            'meta' => []
        ];
    }

    /**
     * @inheritDoc
     */
    protected function handleGet(Request $request): array
    {
        $dbInfo = StateContainer::getInstance()->getDbInfo();
        return [
            'data' => [
                'dbHost' => $dbInfo[StateContainer::DB_HOST],
                'dbPort' => $dbInfo[StateContainer::DB_PORT],
                'dbName' => $dbInfo[StateContainer::DB_NAME],
                'dbUser' => $dbInfo[StateContainer::DB_USER],
            ],
            'meta' => []

        ];
    }
}
