<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 22.01.16
 * Time: 14:27
 */
class WebshopRemittance {
	public $id = 0;
	public $dealerNumber = 0;
	public $dealerOrderNumber = '';
	public $cpReferenceNumber = 0;
	public $paidDate = '';
	public $invoiceNumber = '';
	public $amount = 0.00;
	public $withdrawn = false;
	public $contractId = 0;
	public $voucherNumber = '';

	public function __construct( $amount = 0.00,
	                             $voucherNumber = '',
	                             $paidDate = '',
	                             $withdrawn = false,
	                             $contractId = 0,
	                             $cpReferenceNumber = 0,
	                             $dealerOrderNumber = '',
	                             $id = 0,
	                             $invoiceNumber = 0,
	                             $dealerNumber = '' ) {
		$this->id = $id;
		$this->dealerNumber = $dealerNumber;
		$this->dealerOrderNumber = $dealerOrderNumber;
		$this->cpReferenceNumber= $cpReferenceNumber;
		$this->paidDate = $paidDate;
		$this->invoiceNumber = $invoiceNumber;
		$this->amount = $amount;
		$this->withdrawn = $withdrawn;
		$this->contractId = $contractId;
		$this->voucherNumber = $voucherNumber;
	}

	/**
	 * @param WebshopRemittance $oObject
	 */
	public function fromObject( $oObject ) {
		$this->id = $oObject->id;
		$this->dealerNumber = $oObject->dealerNumber;
		$this->dealerOrderNumber = $oObject->dealerOrderNumber;
		$this->cpReferenceNumber= $oObject->cpReferenceNumber;
		$this->paidDate = $oObject->paidDate;
		$this->invoiceNumber = $oObject->invoiceNumber;
		$this->amount = $oObject->amount;
		$this->withdrawn = $oObject->withdrawn;
		$this->contractId = $oObject->contractId;
		$this->voucherNumber = $oObject->voucherNumber;
	}
}
