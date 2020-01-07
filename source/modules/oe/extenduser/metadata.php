<?php
/**
 * @package       extenduser
 * @category      module
 * @author        OXID eSales AG
 * @link          http://www.oxid-esales.com/en/
 * @licenses      GNU GENERAL PUBLIC LICENSE. More info can be found in LICENSE file.
 * @copyright (C) OXID e-Sales, 2003-2017
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'extenduser',
    'title'       => array(
        'de' => 'Extends Nutzer',
        'en' => 'Extends Nutzer',
    ),
    'description'  => array(
        'de' => 'Extends Nutzer.',
        'en' => 'Extends user.',
    ),
    'version'      => '1.0.0',
    'author'       => 'OXID eSales AG',
    'url'          => 'http://www.oxid-esales.com',
    'email'        => 'mantas.vaitkunas@oxid-esales.com',
    'extend'       => array(
        \OxidEsales\Eshop\Application\Model\User\UserUpdatableFields::class => \OxidEsales\ExtendUser\UserUpdatableFields::class
    ),
    'events'       => array(
        'onActivate'   => \OxidEsales\ExtendUser\ModuleEvents::class.'::onActivate',
        'onDeactivate' => \OxidEsales\ExtendUser\ModuleEvents::class.'::onDeactivate'
    ),
    'blocks' => array(
        array(
            'template' => 'form/fieldset/user_billing.tpl',
            'block'=>'form_user_billing_country',
            'file'=>'/views/user.tpl'
        ),
        array(
            'template' => 'form/register.tpl',
            'block' => 'user_checkbox_registration',
            'file' => 'views/register.tpl'
        )
    ),
    'settings' => array(),
);
