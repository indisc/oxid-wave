<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

/**
 * Add custom functions here.
 *
 * Shop operators could overwrite the standard exception handler here to use different logging mechanism for example.
 */

define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/modules/ps/psexceptionhandler/functions.php');

$debugMode = (bool) \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class)->getVar('iDebug');
if (class_exists('\Fastlane\HfContact\Core\Exception\ExceptionHandler')) {
    set_exception_handler(
        [
            new \Fastlane\HfContact\Core\Exception\ExceptionHandler($debugMode),
            'handleUncaughtThrowable'
        ]
    );
}
unset($debugMode);