<?php
/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @copyright (c) Proud Sourcing GmbH & shoptimax GmbH | 2017
 * @link www.proudcommerce.com / www.shoptimax.de
 * @package psExceptionHandler
 * @version 1.0.0
 **/

namespace ProudSourcing\ExceptionHandler\Core\Exception;

/**
 * Class ExceptionHandler
 * @package ProudSourcing\ExceptionHandler\Core\Exception
 */
class ExceptionHandler extends \OxidEsales\Eshop\Core\Exception\ExceptionHandler
{
    /**
     * Handles exception/throwable/error
     *
     * @param $exception
     */
    public function handleUncaughtThrowable($exception)
    {
        if (version_compare(PHP_VERSION, '7.0.0') >= 0 && $exception instanceof \Error) {
            $this->handleUncaughtError($exception);
        } else {
            parent::handleUncaughtException($exception); // Calling Oxid handler.
        }
    }

    /**
     * Handler for uncaught errors.
     *
     * @param \Error $error exception object
     *
     * @return void
     **/
    private function handleUncaughtError(\Error $error)
    {
        if ($this->_iDebug) {
            var_dump($error);
        }

        $this->writeErrorToLog($error);
        if (defined('OXID_PHP_UNIT')) {
            return;
        }
        $this->exitApplication();
    }

    /**
     * Write a formatted log entry to the log file.
     *
     * @param \Error $error
     *
     * @return int|false The function returns the number of bytes that were written to the file, or false on failure.
     */
    private function writeErrorToLog(\Error $error)
    {
        /** self::_sFileName is @deprecated since v6.0 (2017-03-30); Logging mechanism will change in the future. */
        $logFile = dirname(OX_LOG_FILE) . DIRECTORY_SEPARATOR . $this->_sFileName;
        $logMessage = $this->getFormattedError($error);

        return file_put_contents($logFile, $logMessage, FILE_APPEND) !== false ? true : false;
    }

    /**
     * Return a formatted error to be written to the log file.
     *
     * @param \Error $error
     *
     * @return string
     */
    protected function getFormattedError(\Error $error)
    {
        $time = microtime(true);
        $micro = sprintf("%06d", ($time - floor($time)) * 1000000);
        $date = new \DateTime(date('Y-m-d H:i:s.' . $micro, $time));
        $timestamp = $date->format('d M H:i:s.u Y');

        $class = get_class($error);

        /** report the error */
        $trace = $error->getTraceAsString();
        $lines = explode(PHP_EOL, $trace);
        $logMessage = "[$timestamp] [exception] [type {$class}] [code {$error->getCode()}] [file {$error->getFile()}] [line {$error->getLine()}] [message {$error->getMessage()}]" . PHP_EOL;
        foreach ($lines as $line) {
            $logMessage .= "[$timestamp] [exception] [stacktrace] " . $line . PHP_EOL;
        }

        return $logMessage;
    }
}