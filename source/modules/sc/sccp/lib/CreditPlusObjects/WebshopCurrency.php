<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 11.02.16
 * Time: 15:39
 * @property $side
 * @property $sign
 * @property $name
 * @property $decimals
 * @property $decimalSeparator
 * @property $thousandSeparator
 */
class WebshopCurrency {
	/** @var string $side */
	protected $side = 'left';

	/** @var string $sign */
	protected $sign = '€';

	/** @var string $name */
	protected $name = 'EUR';

	/** @var int $decimals */
	protected $decimals = 2;

	/** @var string $decimal */
	protected $decimalSeparator = ',';

	/** @var string  */
	protected $thousandSeparator = '.';

	/**
	 * @param WebshopCurrency $oObject
	 * @return WebshopCurrency
	 */
	public static function fromObject( $oObject ) {
		$oWebshopCurrency = new WebshopCurrency();
		$oWebshopCurrency->side = $oObject->side;
		$oWebshopCurrency->sign = $oObject->sign;
		$oWebshopCurrency->name = $oObject->name;
		$oWebshopCurrency->decimals = $oObject->decimals;
		$oWebshopCurrency->decimalSeparator = $oObject->decimalSeparator;
		$oWebshopCurrency->thousandSeparator = $oObject->thousandSeparator;
		return $oWebshopCurrency;
	}

	/**
	 * Creates a WebshopCurrency Object from an array with the following keys:
	 * <ul>
	 *  <li>side</li>
	 *  <li>sign</li>
	 *  <li>name</li>
	 *  <li>decimals</li>
	 *  <li>decimalSeparator</li>
	 *  <li>thousandSeparator</li>
	 * </ul>
	 *
	 * @param mixed[] $aObject Array with keys for each of the objects' properties
	 * @return WebshopCurrency Currency Object
	 */
	public static function fromArray( $aObject ) {
		$oWebshopCurrency = new WebshopCurrency();
		foreach ( $aObject as $sKey => $sValue ) {
			$oWebshopCurrency->$sKey = $sValue;
		}
		return $oWebshopCurrency;
	}

	/**
	 * Magic Getter for fields
	 *
	 * @param string $sName
	 * @return null|string|int
	 */
	public function __get( $sName ) {
		if ( property_exists(get_class($this),$sName) ) {
			return $this->$sName;
		}
		return null;
	}

	/**
	 * Magic Setter for fields
	 *
	 * @param string $sName
	 * @param string|int $sValue
	 */
	public function __set( $sName, $sValue ) {
		if ( property_exists(get_class($this),$sName) ) {
			$this->$sName = $sValue;
		}
	}
}
