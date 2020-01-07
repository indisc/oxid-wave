<?php
$debugMode = (bool) \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class)->getVar('iDebug');
if (class_exists('\ProudSourcing\ExceptionHandler\Core\Exception\ExceptionHandler')) {
    set_exception_handler(
        [
            new \ProudSourcing\ExceptionHandler\Core\Exception\ExceptionHandler($debugMode),
            'handleUncaughtThrowable'
        ]
    );
}
unset($debugMode);