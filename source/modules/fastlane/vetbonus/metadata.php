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
    'id'           => 'vetbonus',
    'title'        => 'Vetline Bonus',
    'description'  => array(
        'de' => 'vetbonus user.',
        'en' => 'vetbonus user.',
    ),
    'version'      => '1.0.0',
    'author'       => 'Fastlane',
    'url'          => 'http://www.fastlane.de',
    'email'        => 'info@fastlane.de',
    'extend'       => array(
        \OxidEsales\Eshop\Application\Model\User\UserUpdatableFields::class => \Fastlane\VetBonus\UserUpdatableFields::class
    ),
    'events'       => array(
        'onActivate'   => '\Fastlane\Vetbonus\Core\Events\VetBonusEvents::onActivate',
        'onDeactivate' => '\Fastlane\Vetbonus\Core\Events\VetBonusEvents::onDeactivate'
    ),
    'blocks' => array(
        array('template' => 'form/fieldset/user_billing.tpl', 'block'=>'form_user_billing_country', 'file'=>'/views/user.tpl'),
    ),
    'settings' => []
);
