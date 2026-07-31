<?php

namespace App;

/**
 * Application configuration
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_DRIVER = 'mysql';

    /**
     * Database host
     * @var string
     */
    const DB_HOST = '127.0.0.1';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'realestate';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Return the active database configuration.
     *
     * Localhost keeps the default constants above, while shared hosting can
     * provide App/database.php without committing private credentials.
     */
    public static function database(): array
    {
        $configFile = __DIR__ . '/database.php';

        if (is_readable($configFile)) {
            $config = require $configFile;

            if (is_array($config)) {
                return array_merge([
                    'driver' => self::DB_DRIVER,
                    'host' => self::DB_HOST,
                    'database' => self::DB_NAME,
                    'username' => self::DB_USER,
                    'password' => self::DB_PASSWORD,
                    'port' => 3306,
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ], $config);
            }
        }

        return [
            'driver' => self::DB_DRIVER,
            'host' => self::DB_HOST,
            'database' => self::DB_NAME,
            'username' => self::DB_USER,
            'password' => self::DB_PASSWORD,
            'port' => 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ];
    }
}
