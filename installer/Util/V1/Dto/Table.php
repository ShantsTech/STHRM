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

namespace ShantsHRM\Installer\Util\V1\Dto;

use Doctrine\DBAL\Schema\AbstractSchemaManager;

class Table extends \Doctrine\DBAL\Schema\Table
{
    private AbstractSchemaManager $schemaManager;

    /**
     * @param AbstractSchemaManager $schemaManager
     */
    public function setSchemaManager(AbstractSchemaManager $schemaManager): void
    {
        $this->schemaManager = $schemaManager;
    }

    /**
     * @param string $name
     * @param string $typeName
     * @param array $options ['Type' => \Doctrine\DBAL\Types\Type, 'Length' => int|null, 'Precision' => int, 'Scale' => int, 'Unsigned' => bool, 'Fixed' => bool, 'Notnull' => bool, 'Default' => mixed, 'Autoincrement' => bool, 'Comment' => string|null, 'CustomSchemaOptions' => array]
     * @return self
     */
    public function addColumn($name, $typeName, array $options = []): self
    {
        parent::addColumn($name, $typeName, $options);
        return $this;
    }

    /**
     * Execute SQL to create the table
     */
    public function create(): void
    {
        $this->schemaManager->createTable($this);
    }
}
