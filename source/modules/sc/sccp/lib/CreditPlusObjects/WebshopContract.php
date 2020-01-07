<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

/**
 * Created by PhpStorm.
 * User: mihovil.bubnjar
 * Date: 22.01.16
 * Time: 10:45
 *
 * Transfer Class for getContracts Response items
 */
class WebshopContract {
	/** @var bool $addressChanged */
	public $addressChanged = false;

	/** @var string $areaCode */
	public $areaCode = '';

	/** @var string $cancelationDate */
	public $cancelationDate = '';

	/** @var string $cancelationFrom */
	public $cancelationFrom = '';

	/** @var string $conditionName */
	public $conditionName = '';

	/** @var int $cpReferenceNumber */
	public $cpReferenceNumber = 0;

	/** @var int $creditDuration */
	public $creditDuration = 0;

	/** @var int $dealerNumber */
	public $dealerNumber = 0;

	/** @var string $dealerOrderNumber */
	public $dealerOrderNumber = '';

	/** @var string $decisionDate */
	public $decisionDate = '';

	/** @var bool deliveryDone */
	public $deliveryDone = false;

	/** @var bool $finallyApproved */
	public $finallyApproved = '';

	/** @var string $firstDecisionDate */
	public $firstDecisionDate = '';

	/** @var string $forename */
	public $forename = '';

	/** @var int $id */
	public $id = 0;

	/** @var bool $informationRequest */
	public $informationRequest = false;

	/** @var int $krebesReference */
	public $krebesReference = 0;

	/** @var bool $loginWebshop */
	public $loginWebshop = false;

	/** @var string $name */
	public $name = '';

	/** @var string $orderDate */
	public $orderDate = '';

	/** @var string $price */
	public $price = '';

	/** @var string $state */
	public $state = '';

	/** @var string $stateText */
	public $stateText = '';

	/** @var string $street */
	public $street = '';

	/** @var string $town */
	public $town = '';

	/** @var WebshopVoucher[] $voucher */
	public $voucher = array();

	/** @var int $iError */
	public $iError = 0;

	/**
	 * Creates a WebshopContract out of transferred Data
	 *
	 * @param WebshopContract $oObject
	 * @return WebshopContract
	 */
	public static function fromObject($oObject) {
		$oContract = new WebshopContract();

		$oContract->addressChanged = $oObject->addressChanged;
		$oContract->areaCode = $oObject->areaCode;
		$oContract->cancelationDate = $oObject->cancelationDate;
		$oContract->cancelationFrom = $oObject->cancelationFrom;
		$oContract->conditionName = $oObject->conditionName;
		$oContract->cpReferenceNumber = $oObject->cpReferenceNumber;
		$oContract->creditDuration = $oObject->creditDuration;
		$oContract->dealerNumber = $oObject->dealerNumber;
		$oContract->dealerOrderNumber = $oObject->dealerOrderNumber;
		$oContract->decisionDate = $oObject->decisionDate;
		$oContract->deliveryDone = $oObject->deliveryDone;
		$oContract->finallyApproved = $oObject->finallyApproved;
		$oContract->firstDecisionDate = $oObject->firstDecisionDate;
		$oContract->forename = $oObject->forename;
		$oContract->id = $oObject->id;
		$oContract->informationRequest = $oObject->informationRequest;
		$oContract->krebesReference = $oObject->krebesReference;
		$oContract->loginWebshop = $oObject->loginWebshop;
		$oContract->name = $oObject->name;
		$oContract->orderDate = $oObject->orderDate;
		$oContract->price = $oObject->price;
		$oContract->state = $oObject->state;
		$oContract->stateText = $oObject->stateText;
		$oContract->street = $oObject->street;
		$oContract->town = $oObject->town;

		$aVoucher = array();
		if ( property_exists($oObject,'voucher') && $oObject->voucher ) {
			foreach ( $oObject->voucher as $oObjectVoucher ) {
				$oVoucher = WebshopVoucher::fromObject($oObjectVoucher);
				$aVoucher[] = $oVoucher;
			}
		}
		$oContract->voucher = $aVoucher;

		return $oContract;
	}

}
