<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 22.01.16
 * Time: 12:39
 */
class WebshopVoucher {
	public $id = 0;
	public $value = 0.00;
	public $number = '';
	public $date = '';
	public $agentName = '';
	public $done = false;
	public $contractId = 0;
	public $dealerOrderNumber = '';
	public $dealerNumber = '';

	public function __construct( $value = 0.00,
	                             $number = '',
	                             $date = '',
	                             $agentName = '',
	                             $done = false,
	                             $contractId = 0,
	                             $dealerOrderNumber = '',
	                             $dealerNumber = '',
	                             $id = 0 ) {
		$this->value = $value;
		$this->number = $number;
		$this->date = $date;
		$this->agentName = $agentName;
		$this->done = $done;
		$this->contractId = $contractId;
		$this->dealerOrderNumber = $dealerOrderNumber;
		$this->id = $id;
		$this->dealerNumber = $dealerNumber;
	}

	/**
	 *
	 * @param WebshopVoucher $oObjectVoucher Webshop Voucher or stdClass with same properties
	 * @return WebshopVoucher new Instance with stuff already set
	 */
	public static function fromObject( $oObjectVoucher ) {
		$oVoucher = new WebshopVoucher();
		$oVoucher->value = $oObjectVoucher->value;
		$oVoucher->date = $oObjectVoucher->date;
		$oVoucher->number = $oObjectVoucher->number;
		$oVoucher->agentName = $oObjectVoucher->agentName;
		$oVoucher->done = $oObjectVoucher->done;
		$oVoucher->contractId = $oObjectVoucher->contractId;
		$oVoucher->dealerOrderNumber = $oObjectVoucher->dealerOrderNumber;
		$oVoucher->id = $oObjectVoucher->id;
		$oVoucher->dealerNumber = $oObjectVoucher->dealerNumber;
		return $oVoucher;
	}
}
