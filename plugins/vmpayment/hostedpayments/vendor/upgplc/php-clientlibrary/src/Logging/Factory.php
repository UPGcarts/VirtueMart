<?php

namespace Upg\Library\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Upg\Library\Config;

/**
 * Class Factory
 * Provides an Logger
 * @package Upg\Library\Logging
 */
class Factory
{
    private static $loggers = array();

    /**
     * @param Config $config
     * @param $logLocation
     * @return LoggerInterface
     */
    public static function getLogger(Config $config, $logLocation)
    {
        $log = new Blank();
        if (!array_key_exists($logLocation, self::$loggers)) {
            if ($config->getLogEnabled()) {
                $log = new Logger('payco');
                $log->pushHandler(new StreamHandler($logLocation, $config->getLogLevel()));
            }
        }
        self::$loggers[$logLocation] = $log;
        return self::$loggers[$logLocation];
    }
}
