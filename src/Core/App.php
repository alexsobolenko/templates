<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Database\Database;
use App\Core\DI\Container;
use App\Core\Http\Request;
use App\Core\Http\Router;
use App\Core\Log\Logger;

final class App
{
    /**
     * @var Container
     */
    public static Container $container;

    /**
     * @var Config
     */
    public static Config $config;

    /**
     * @var Request
     */
    public static Request $request;

    /**
     * @var Database
     */
    public static Database $db;

    /**
     * @var Logger
     */
    public static Logger $logger;

    /**
     * @var Router
     */
    public static Router $router;

    /**
     * @var string
     */
    public static string $rootPath;

    private function __construct()
    {
    }

    /**
     * @param Container $container
     * @param Config $config
     * @param Request $request
     * @param Database $db
     * @param Logger $logger
     * @param Router $router
     * @param string $rootPath
     */
    public static function init(
        Container $container,
        Config $config,
        Request $request,
        Database $db,
        Logger $logger,
        Router $router,
        string $rootPath,
    ): void {
        self::$container = $container;
        self::$config = $config;
        self::$request = $request;
        self::$db = $db;
        self::$logger = $logger;
        self::$router = $router;
        self::$rootPath = $rootPath;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        return $value === false ? $default : $value;
    }

    /**
     * @return bool
     */
    public static function isDebug(): bool
    {
        return filter_var(self::env('APP_DEBUG', false), FILTER_VALIDATE_BOOL) === true;
    }

    /**
     * @param string $type
     * @param string $id
     * @param mixed $default
     * @return mixed
     */
    public static function config(string $type, string $id, mixed $default = null): mixed
    {
        return self::$config->get($type, $id, $default);
    }

    /**
     * @param string $id
     * @param mixed $default
     * @return mixed
     */
    public static function serviceConfig(string $id, mixed $default = null): mixed
    {
        return self::config('service', $id, $default);
    }
}
