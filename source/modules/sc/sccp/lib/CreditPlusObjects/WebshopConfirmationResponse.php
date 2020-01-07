<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 04.02.16
 * Time: 12:10
 */
class WebshopConfirmationResponse {
	/** @var WebshopConfirmationItems $confirmation */
	public $confirmation = null;
	/** @var bool $isSucceed */
	public $isSucceed = false;

	/**
	 * @param WebshopConfirmationResponse $oObject
	 * @return WebshopConfirmationResponse
	 */
	public static function fromObject( $oObject ) {
		$oConfirmationResponse = new WebshopConfirmationResponse();
		$oConfirmationResponse->confirmation = WebshopConfirmationItems::fromObject($oObject->confirmation);
		$oConfirmationResponse->isSucceed = $oObject->isSucceed;
		return $oConfirmationResponse;
	}
}
