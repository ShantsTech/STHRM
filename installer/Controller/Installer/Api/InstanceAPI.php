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

use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Framework\Http\Response;
use ShantsHRM\Installer\Controller\AbstractInstallerRestController;
use ShantsHRM\Installer\Util\InstanceCreationHelper;
use ShantsHRM\Installer\Util\StateContainer;

class InstanceAPI extends AbstractInstallerRestController
{
    /**
     * @inheritDoc
     */
    protected function handlePost(Request $request): array
    {
        $organizationName = $request->request->get('organizationName');
        if (empty($organizationName)) {
            $this->getResponse()->setStatusCode(Response::HTTP_BAD_REQUEST);
            return [
                'error' => [
                    'status' => $this->getResponse()->getStatusCode(),
                    'message' => '`organizationName` is required'
                ]
            ];
        }

        $countryCode = $request->request->get('countryCode');
        if (!in_array($countryCode, array_column(InstanceCreationHelper::COUNTRIES, 'id'))) {
            $this->getResponse()->setStatusCode(Response::HTTP_BAD_REQUEST);
            return [
                'error' => [
                    'status' => $this->getResponse()->getStatusCode(),
                    'message' => 'Invalid `countryCode`'
                ]
            ];
        }

        $langCode = null;
        if ($request->request->has('langCode') && '' != $langCode = $request->request->get('langCode')) {
            if (!in_array($langCode, array_column(InstanceCreationHelper::LANGUAGES, 'id'))) {
                $this->getResponse()->setStatusCode(Response::HTTP_BAD_REQUEST);
                return [
                    'error' => [
                        'status' => $this->getResponse()->getStatusCode(),
                        'message' => 'Invalid `langCode`'
                    ]
                ];
            }
        }

        $timezone = null;
        if ($request->request->has('timezone') && '' != $timezone = $request->request->get('timezone')) {
            if (!in_array($timezone, array_column(InstanceCreationHelper::getTimezones(), 'id'))) {
                $this->getResponse()->setStatusCode(Response::HTTP_BAD_REQUEST);
                return [
                    'error' => [
                        'status' => $this->getResponse()->getStatusCode(),
                        'message' => 'Invalid `timezone`'
                    ]
                ];
            }
        }

        StateContainer::getInstance()->storeInstanceData($organizationName, $countryCode, $langCode, $timezone);
        return [
            'organizationName' => $organizationName,
            'countryCode' => $countryCode,
            'langCode' => $langCode,
            'timezone' => $timezone,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function handleGet(Request $request): array
    {
        $instanceData = StateContainer::getInstance()->getInstanceData();
        $countryCode = is_null($instanceData[StateContainer::INSTANCE_COUNTRY_CODE])
            ? null
            : InstanceCreationHelper::getCountryByCode($instanceData[StateContainer::INSTANCE_COUNTRY_CODE]);
        $langCode = is_null($instanceData[StateContainer::INSTANCE_LANG_CODE])
            ? null
            : InstanceCreationHelper::getLanguageByCode($instanceData[StateContainer::INSTANCE_LANG_CODE]);
        $timezone = is_null($instanceData[StateContainer::INSTANCE_TIMEZONE])
            ? null
            : [
                'id' => $instanceData[StateContainer::INSTANCE_TIMEZONE],
                'label' => $instanceData[StateContainer::INSTANCE_TIMEZONE],
            ];
        return [
            'data' => [
                'organizationName' => $instanceData[StateContainer::INSTANCE_ORG_NAME],
                'countryCode' => $countryCode,
                'langCode' => $langCode,
                'timezone' => $timezone,
            ],
        ];
    }
}
