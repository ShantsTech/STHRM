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

namespace ShantsHRM\Admin\Api\Model;

use ShantsHRM\Core\Api\V2\Serializer\ModelTrait;
use ShantsHRM\Core\Api\V2\Serializer\Normalizable;
use ShantsHRM\Entity\Organization;

/**
 * @OA\Schema(
 *     schema="Admin-OrganizationModel",
 *     type="object",
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="registrationNumber", type="string"),
 *     @OA\Property(property="phone", type="string"),
 *     @OA\Property(property="fax", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="country", type="string"),
 *     @OA\Property(property="province", type="string"),
 *     @OA\Property(property="city", type="string"),
 *     @OA\Property(property="zipCode", type="string"),
 *     @OA\Property(property="street1", type="string"),
 *     @OA\Property(property="street2", type="string"),
 *     @OA\Property(property="note", type="string"),
 * )
 */
class OrganizationModel implements Normalizable
{
    use ModelTrait;

    public function __construct(Organization $organization)
    {
        $this->setEntity($organization);
        $this->setFilters(
            [
                'name',
                'taxId',
                'registrationNumber',
                'phone',
                'fax',
                'email',
                'country',
                'province',
                'city',
                'zipCode',
                'street1',
                'street2',
                'note',
            ]
        );
    }
}
