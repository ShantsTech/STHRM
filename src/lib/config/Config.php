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

namespace ShantsHRM\Config;

use Conf;
use ShantsHRM\ORM\Exception\ConfigNotFoundException;

class Config
{
    public const PLUGINS = 'sthrm_plugins';
    public const PLUGIN_PATHS = 'sthrm_plugin_paths';
    public const PLUGIN_CONFIGS = 'sthrm_plugin_configs';
    public const BASE_DIR = 'sthrm_base_dir';
    public const SRC_DIR = 'sthrm_src_dir';
    public const PLUGINS_DIR = 'sthrm_plugins_dir';
    public const PUBLIC_DIR = 'sthrm_public_dir';
    public const LOG_DIR = 'sthrm_log_dir';
    public const CACHE_DIR = 'sthrm_cache_dir';
    public const CONFIG_DIR = 'sthrm_config_dir';
    public const CRYPTO_KEY_DIR = 'sthrm_crypto_key_dir';
    public const SESSION_DIR = 'sthrm_session_dir';
    public const DOCTRINE_PROXY_DIR = 'sthrm_doctrine_proxy_dir';
    public const APP_TEMPLATE_DIR = 'sthrm_app_template_dir';
    public const TEST_DIR = 'sthrm_test_dir';
    public const CONF_FILE_PATH = 'sthrm_conf_file_path';
    public const I18N_ENABLED = 'sthrm_i18n_enabled';
    public const DATE_FORMATTING_ENABLED = 'sthrm_date_formatting_enabled';
    public const VUE_BUILD_TIMESTAMP = 'sthrm_vue_build_timestamp';
    public const MAX_SESSION_IDLE_TIME = 'sthrm_max_session_idle_time';

    public const MODE_DEV = 'dev';
    public const MODE_PROD = 'prod';
    public const MODE_TEST = 'test';
    public const MODE_DEMO = 'demo';

    public const PRODUCT_NAME = 'ShantsHRM OS';
    public const PRODUCT_VERSION = '5.3';
    public const PRODUCT_MODE = self::MODE_PROD;
    public const REGISTRATION_URL = 'https://hrm.shants-tech.com';

    public const DEFAULT_MAX_SESSION_IDLE_TIME = 1800;

    /**
     * @var array
     */
    protected static array $configs = [];

    /**
     * @var Conf|null
     */
    protected static ?Conf $conf = null;

    /**
     * @var bool
     */
    protected static bool $initialized = false;

    private function __construct()
    {
    }

    private static function init(): void
    {
        if (!self::$initialized) {
            $configHelper = new ConfigHelper();
            self::add($configHelper->getConfigs());

            self::$initialized = true;
        }
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed|null
     */
    public static function get(string $name, $default = null)
    {
        self::init();
        return self::$configs[$name] ?? $default;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        self::init();
        return array_key_exists($name, self::$configs);
    }

    /**
     * @param string $name
     * @param $value
     */
    public static function set(string $name, $value)
    {
        self::$configs[$name] = $value;
    }

    /**
     * @param array $parameters
     */
    public static function add(array $parameters = [])
    {
        self::$configs = array_merge(self::$configs, $parameters);
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        self::init();
        return self::$configs;
    }

    public static function clear(): void
    {
        self::$configs = [];
    }

    /**
     * @return bool
     */
    public static function isInstalled(): bool
    {
        try {
            Config::getConf();
            return true;
        } catch (ConfigNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param bool $force
     * @return Conf
     * @throws ConfigNotFoundException
     */
    public static function getConf(bool $force = false): Conf
    {
        if (!self::$conf instanceof Conf || $force) {
            if (!@include_once(self::get(self::CONF_FILE_PATH))) {
                throw ConfigNotFoundException::notInstalled();
            }
            self::$conf = new Conf();
        }
        return self::$conf;
    }
}
