<?php
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 16.12.2015
 * Time: 12:50
 */

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;
use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\UrlHandling;

//require '../CreditPlusHelper/DataHandling.php';
//require_once('../CreditPlusHelper/Validation.php.php');

class ShopIntegrationData extends UrlHandling {

	/**
	 * Main Shop URL
	 * Used for transmission between CreditPlus Service and Customer Server
	 *
	 * @required true
	 * @var string
	 */
	public $sTriggerUrl;

	/**
	 * Base Shop URL
	 *
	 * @required false
	 * @var array
	 */
	protected $aData;

	/**
	 * List of the shoppingCart items in the the
	 * remote webshop
	 *
	 * ShoppingCartItem[]
	 *
	 * @required false
	 * @var ShoppingCartItem[]
	 */
	public $aShoppingCart;

	/**
	 * Konstruktor
	 *
	 * Info: baseUrl overrides data
	 *
	 * @param array $aInput
	 * @throws \Exception If input is no ARRAY
	 */
	function __construct( $aInput = array() ) {

		parent::__construct();

		if ( is_array($aInput) ) {
			// Initialize array with input-data

			if ( isset($aInput['triggerUrl']) ) {
				$this->setTriggerUrl($aInput['triggerUrl']);
			} else {
				$this->setTriggerUrl($this->getTriggerUrl());
			}

			if ( isset($aInput['shoppingCart']) ) {
				$this->aShoppingCart = $aInput['shoppingCart'];
			}

		} else {
			throw new \Exception('Wrong input type');
		}
	}

	/**
	 * Check if This Object has valid Data
	 *
	 * @return boolean
	 */
	public function isValid() {

		//baseUrl

		//shoppingCart

		return true;

	}


	/**
	 * returns the Data as Array for API
	 * --> empty array if not valid
	 *
	 */
	public function getApiArray() {

		$aApiData = $this->getDummyArray();
		$aApiData['shoppingCart'] = $this->getShoppingCartApiArray();
		return $aApiData;
	}


	/**
	 *
	 * returns a Dummy-Data Array for API Requests
	 *
	 * @return array
	 */
	public function getDummyArray() {

		$aDummyData = array(
			'baseUrl' => $this->sTriggerUrl,
			'shoppingCart' => array(
				array(
					'amount' => 1,
					'description' => '1L Coca Cola PET Mehrwegflasche',
					'ean' => '5449000017888',
					'listPrice' => 1000.11,
					'promotion' => false,
					'sellingPrice' => 1000.00
				),
				array(
					'amount' => 1,
					'description' => 'Versandkosten',
					'ean' => '2000000000008',
					'listPrice' => 500.55,
					'promotion' => false,
					'sellingPrice' => 500.00
				)
			)
		);

		return $aDummyData;
	}

	/**
	 * Set TriggerURL for continued communication between CreditPlus Service and Customer Server
	 *
	 * If the given URL does not end in ampersand (&),
	 * and does not contain a question mark (?),
	 * a question mark (?) will be added at the end.
	 *
	 * If the given URL does not end in ampersand (&),
	 * but a question mark (?) exists,
	 * an ampersand (&) will be added.
	 *
	 * @param string $sTriggerUrl Trigger URL for system. e.g. http://oxid.creditplus.de/index.php?cl=order&fnc=dispatch&don=CP201601012231& - This will be enriched by CreditPlus with parameters 'callback' and 'signature'
	 * @throws \Exception
	 */
	public function setTriggerUrl( $sTriggerUrl ) {
		$sLastChar = substr($sTriggerUrl, -1);
		if ( $sLastChar != '&' ) {
			if ( strpos($sTriggerUrl, '?') === false ) {
				$sTriggerUrl .= '?';
			} else {
				$sTriggerUrl .= '&';
			}
		}
		$this->sTriggerUrl = $sTriggerUrl;
	}

	/**
	 * @return ShoppingCartItem[]
	 */
	public function getShoppingCart() {
		return $this->aShoppingCart;
	}

	/**
	 * @param ShoppingCartItem[] $aShoppingCart
	 */
	public function setShoppingCart( $aShoppingCart ) {
		$this->aShoppingCart = $aShoppingCart;
	}

	/**
	 * Makes an array of simple value assignments out of an ShoppingCartItem Array using "getApiArray"
	 * @return array
	 * @see ShoppingCartItem::getApiArray()
	 */
	protected function getShoppingCartApiArray() {
		$aCart = $this->getShoppingCart();
		$aReturn = array();
		foreach ( $aCart as $oItem ) {
			$aReturn[] = $oItem->getApiArray();
		}
		return $aReturn;
	}
}
