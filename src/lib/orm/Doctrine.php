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

namespace ShantsHRM\ORM;

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use ShantsHRM\Config\Config;
use ShantsHRM\Core\Traits\ServiceContainerTrait;
use ShantsHRM\Framework\Cache\FilesystemAdapter;
use ShantsHRM\Framework\Framework;
use ShantsHRM\Framework\Services;
use ShantsHRM\ORM\Exception\ConfigNotFoundException;
use ShantsHRM\ORM\Functions\TimeDiff;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class Doctrine
{
    use ServiceContainerTrait;

    /**
     * @var null|Doctrine
     */
    private static ?Doctrine $instance = null;
    /**
     * @var null|EntityManager
     */
    private static ?EntityManager $entityManager = null;

    /**
     * @throws ConfigNotFoundException
     */
    private function __construct()
    {
        $conf = Config::getConf();

        $isDevMode = $this->isDevMode();
        $proxyDir = Config::get(Config::DOCTRINE_PROXY_DIR);
        $cache = new ArrayAdapter();
        $paths = $this->getPaths();
        $config = ORMSetup::createAnnotationMetadataConfiguration(
            $paths,
            $isDevMode,
            $proxyDir,
            $cache
        );
        if (!$isDevMode) {
            $metadataCache = new FilesystemAdapter('doctrine_metadata', 0, Config::get(Config::CACHE_DIR));
            $queryCache = new FilesystemAdapter('doctrine_queries', 0, Config::get(Config::CACHE_DIR));
            $config->setMetadataCache($metadataCache);
            $config->setQueryCache($queryCache);
        }

        $config->setAutoGenerateProxyClasses(
            $isDevMode
                ? AbstractProxyFactory::AUTOGENERATE_ALWAYS
                : AbstractProxyFactory::AUTOGENERATE_NEVER
        );
        $config->addCustomStringFunction('TIME_DIFF', TimeDiff::class);

        $connectionParams = [
            'dbname' => $conf->getDbName(),
            'user' => $conf->getDbUser(),
            'password' => $conf->getDbPass(),
            'host' => $conf->getDbHost(),
            'port' => $conf->getDbPort(),
            'driver' => 'pdo_mysql',
            'charset' => 'utf8mb4'
        ];

        self::$entityManager = EntityManager::create($connectionParams, $config);
        self::$entityManager->getConnection()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');
    }

    /**
     * @return bool
     */
    private function isDevMode(): bool
    {
        try {
            /** @var Framework $kernel */
            $kernel = $this->getContainer()->get(Services::HTTP_KERNEL);
            return $kernel->isDebug();
        } catch (ServiceNotFoundException $e) {
            return false;
        }
    }

    /**
     * @return array
     */
    private function getPaths(): array
    {
        $paths = [];
        $pluginPaths = Config::get('ohrm_plugin_paths');
        foreach ($pluginPaths as $pluginPath) {
            $entityPath = realpath($pluginPath . '/entity');
            if ($entityPath) {
                $paths[] = $entityPath;
            }
        }
        return $paths;
    }

    /**
     * @return Doctrine
     */
    protected static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return EntityManager
     */
    public static function getEntityManager(): EntityManager
    {
        self::getInstance();
        return self::$entityManager;
    }
}
