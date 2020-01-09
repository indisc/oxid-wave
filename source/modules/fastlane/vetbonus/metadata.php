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
        \OxidEsales\Eshop\Application\Model\User\UserUpdatableFields::class => \Fastlane\VetBonus\UserUpdatableFields::class,
        \OxidEsales\Eshop\Application\Controller\FrontendController::class => \Fastlane\VetBonus\Application\Controller\FrontendController::class,
        \OxidEsales\Eshop\Application\Controller\AccountController::class => \Fastlane\VetBonus\Application\Controller\VetBonusAccountController::class,
        \OxidEsales\Eshop\Application\Component\UserComponent::class => \Fastlane\VetBonus\Application\Component\UserComponent::class,
        \OxidEsales\Eshop\Application\Model\User::class => \Fastlane\VetBonus\Application\Model\VetBonusUser::class
        /* \OxidEsales\Eshop\Application\Controller\AccountOrderController::class =>
\Fastlane\VetBonus\Application\Controller\VetBonusHistoryController::class */
    ),
    'events'       => array(
        'onActivate'   => \Fastlane\VetBonus\ModuleEvents::class.'::onActivate',
        'onDeactivate' => \Fastlane\VetBonus\ModuleEvents::class.'::onDeactivate'
    ),
    'controllers'   => array(
        'VetBonusHistoryController' => \Fastlane\VetBonus\Application\Controller\VetBonusHistoryController::class
    ),
    'templates' => array(
        'vetbonusHistory.tpl'  => 'fastlane/vetbonus/Application/views/account/vetbonusHistory.tpl'
    ),
    'blocks' => array(
        array(
            'template' => 'form/fieldset/user_billing.tpl',
            'block'=>'form_user_billing_country',
            'file'=>'Application/views/user.tpl'
        ),
        array(
            'template' => 'form/register.tpl',
            'block' => 'user_checkbox_registration',
            'file' => 'Application/views/register.tpl'
        )
    ),
    'settings' => []
);
