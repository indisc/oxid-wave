<?php
/**
 * @TODO LICENCE HERE
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'hfcontact',
    'title'       => array(
        'de' => 'hfContact module',
        'en' => 'hfContact module',
    ),
    'description' => array(
        'de' => 'hfcontact module',
        'en' => 'hfcontact module',
    ),
    'thumbnail'   => '',
    'version'     => '1.0.0',
    'author'      => 'fastlane.de',
    'url'         => 'https://www.fastlane.de',
    'email'       => 'info@fastlane.de',
    'extend'      => array(
        \OxidEsales\Eshop\Application\Controller\ContactController::class => Fastlane\HfContact\Application\Controller\HfContactController::class,
        \OxidEsales\Eshop\Core\Exception\ExceptionHandler::class => \Fastlane\HfContact\Core\Exception\ExceptionHandler::class,
    ),
    'files'       => array(
        //'hfcontact' => 'fastlane/hfcontact/Controllers/HfContactController.php'
    ),
    'templates'   => array(
        'contact.tpl' => 'fastlane/hfContact/views/contact.tpl',
    ),
    'blocks'      => array(
        array(
            'template' => 'form/contact.tpl',
            'block' => 'contact_form_fields',
            'file' =>   'views/contact.tpl',
            'position' => '2'
        ),
    ),
    'settings' => array(),
    'events'      => array(),
);
