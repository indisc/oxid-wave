<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 04.02.16
 * Time: 12:59
 */
class WebshopConfirmationItems {
	/** @var string $dealerOrderNumber */
	public $dealerOrderNumber = '';
	/** @var int $errorCode */
	public $errorCode = 0;
	/** @var string $errorMessage */
	public $errorMessage = '';
	/** @var bool $succeed */
	public $succeed = false;

	/**
	 * @param WebshopConfirmationItems $oObject
	 * @return WebshopConfirmationItems
	 */
	public static function fromObject( $oObject ) {
		$oConfirmationItem = new WebshopConfirmationItems();
		$oConfirmationItem->dealerOrderNumber = $oObject->dealerOrderNumber;
		$oConfirmationItem->errorCode = $oObject->errorCode;
		$oConfirmationItem->errorMessage = $oObject->errorMessage;
		$oConfirmationItem->succeed = $oObject->succeed;
		return $oConfirmationItem;
	}
}
