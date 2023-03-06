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
 * Boston, MA 02110-1301, USA
 */

namespace ShantsHRM\Core\Command;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ShantsHRM\Config\Config;
use ShantsHRM\Core\Traits\ORM\EntityManagerTrait;
use ShantsHRM\Framework\Console\Command;
use ShantsHRM\Installer\Util\V1\LanguageHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnableTestLanguagePackCommand extends Command
{
    use EntityManagerTrait;

    /**
     * @inheritDoc
     */
    public function getCommandName(): string
    {
        return 'shantshrm:enable-test-lang-pack';
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (Config::PRODUCT_MODE === Config::MODE_PROD) {
            $this->getIO()->error('Not allowed to run in `prod` mode');
            return self::FAILURE;
        }

        $langHelper = new LanguageHelper($this->getEntityManager()->getConnection());
        try {
            $langHelper->createTestLanguagePack();
        } catch (UniqueConstraintViolationException $e) {
            $this->getIO()->warning('Already executed');
            $deleteTestLanguagePack = $this->io->confirm('Delete test language pack?', false);
            if ($deleteTestLanguagePack) {
                $langHelper->deleteTestLanguagePack();
                $this->getIO()->success('Successfully deleted test language package');
                return self::SUCCESS;
            }
            return self::INVALID;
        }

        $this->getIO()->success('Successfully created test language package');
        return self::SUCCESS;
    }
}
