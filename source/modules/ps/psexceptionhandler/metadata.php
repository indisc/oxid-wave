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

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = [
    'id'          => 'psexceptionhandler',
    'title'       => 'psExceptionHandler',
    'description' => [
        'de' => '',
        'en' => ''
    ],
    'thumbnail'   => 'logo_pc-os.jpg',
    'version'     => '1.0.0',
    'author'      => 'Proud Sourcing GmbH / shoptimax GmbH',
    'url'         => 'https://www.proudcommerce.com/',
    'email'       => 'support@proudcommerce.com',
    'extend'      => [
        \OxidEsales\Eshop\Core\Exception\ExceptionHandler::class => \ProudSourcing\ExceptionHandler\Core\Exception\ExceptionHandler::class,
    ],
    'controllers' => [],
    'templates'   => [],
    'blocks'      => [],
    'settings'    => [],
];