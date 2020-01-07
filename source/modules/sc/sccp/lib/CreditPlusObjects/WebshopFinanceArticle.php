<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;
/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 12.02.16
 * Time: 10:19
 */
class WebshopFinanceArticle {
	public $id;
	public $amount;
	public $unitprice;
	public $productTypeID;
	public $productClassID;
	public $cheapestInterestRate;
	public $mostExpensiveInterestRate;

	public function __construct( $aArray = array() ) {
		if ( $aArray && is_array($aArray) ) {
			foreach ( $aArray as $sKey => $sValue ) {
				if ( property_exists(get_class($this),$sKey) ) {
					$this->$sKey = $sValue;
				}
			}
		}
	}
}
