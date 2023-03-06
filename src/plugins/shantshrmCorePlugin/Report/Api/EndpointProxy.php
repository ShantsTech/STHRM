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

namespace ShantsHRM\Core\Report\Api;

use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\Request;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\Helpers\ValidationDecorator;
use ShantsHRM\Core\Dto\FilterParams;

class EndpointProxy extends Endpoint
{
    /**
     * @inheritDoc
     */
    public function getRequest(): Request
    {
        return parent::getRequest();
    }

    /**
     * @inheritDoc
     */
    public function getRequestParams(): RequestParams
    {
        return parent::getRequestParams();
    }

    /**
     * @inheritDoc
     */
    public function getValidationDecorator(): ValidationDecorator
    {
        return parent::getValidationDecorator();
    }

    /**
     * @inheritDoc
     */
    public function setSortingAndPaginationParams(
        FilterParams $searchParamHolder,
        ?string $defaultSortField = null
    ): FilterParams {
        return parent::setSortingAndPaginationParams($searchParamHolder, $defaultSortField);
    }

    /**
     * @inheritDoc
     */
    public function getSortingAndPaginationParamsRules(
        array $allowedSortFields = [],
        bool $excludeSortField = false
    ): array {
        return parent::getSortingAndPaginationParamsRules($allowedSortFields, $excludeSortField);
    }
}
