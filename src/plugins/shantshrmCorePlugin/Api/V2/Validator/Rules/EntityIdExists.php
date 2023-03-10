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

namespace ShantsHRM\Core\Api\V2\Validator\Rules;

use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;

class EntityIdExists extends AbstractRule
{
    use EntityManagerHelperTrait;

    /**
     * @var string
     */
    private string $entityName;

    /**
     * @var EntityIdExistsOption
     */
    private EntityIdExistsOption $option;

    public function __construct(string $entityName, ?EntityIdExistsOption $option = null)
    {
        $this->entityName = $entityName;
        $this->option = $option ?? new EntityIdExistsOption();
    }


    /**
     * @inheritDoc
     */
    public function validate($input): bool
    {
        if ($this->option->isNumeric() && !is_numeric($input)) {
            return false;
        } elseif ($this->option->isPositive() && !$input > 0) {
            return false;
        }

        $entity = $this->getRepository($this->entityName)->find($input);
        return $entity instanceof $this->entityName;
    }
}
