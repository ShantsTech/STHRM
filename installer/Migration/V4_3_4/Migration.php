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

namespace ShantsHRM\Installer\Migration\V4_3_4;

use Doctrine\DBAL\Types\Types;
use ShantsHRM\Installer\Util\V1\AbstractMigration;

class Migration extends AbstractMigration
{
    /**
     * @inheritDoc
     */
    public function up(): void
    {
        if (!$this->getSchemaHelper()->tableExists(['ohrm_employee_subscription'])) {
            $this->getSchemaHelper()->createTable('ohrm_employee_subscription')
                ->addColumn('id', Types::INTEGER, ['Unsigned' => true, 'Autoincrement' => true])
                ->addColumn('employee_id', Types::INTEGER, ['Length' => 7, 'Notnull' => true])
                ->addColumn('status', Types::SMALLINT, ['Length' => 6, 'Notnull' => true])
                ->addColumn('created_at', Types::DATETIME_MUTABLE, ['Notnull' => true])
                ->setPrimaryKey(['id'])
                ->create();
        }
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return '4.3.4';
    }
}
