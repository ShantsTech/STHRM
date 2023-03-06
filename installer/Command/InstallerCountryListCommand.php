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
 * Boston, MA 02110-1301, USA
 */

namespace ShantsHRM\Installer\Command;

use ShantsHRM\Installer\Framework\InstallerCommand;
use ShantsHRM\Installer\Util\InstanceCreationHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallerCountryListCommand extends InstallerCommand
{
    /**
     * @inheritDoc
     */
    public function getCommandName(): string
    {
        return 'install:country-list';
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $countries = array_combine(
            array_column(InstanceCreationHelper::COUNTRIES, 'id'),
            array_column(InstanceCreationHelper::COUNTRIES, 'label')
        );
        asort($countries);
        $countries = array_map(fn ($country) => strtolower($country), $countries);
        $countries = array_map(static function ($k, $v) {
            return " <comment>[$k]</comment> $v";
        }, array_keys($countries), array_values($countries));
        $this->getIO()->writeln($countries);
        return self::SUCCESS;
    }
}
