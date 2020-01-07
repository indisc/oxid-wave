<?php
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 16.12.2015
 * Time: 13:52
 */

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;

class ShoppingCartItem {

	/**
	 * EAN
	 *
	 * @required false
	 * @var string
	 */
	private $sEan;

	/**
	 * The description for the specific item
	 *
	 * @required false
	 * @var string
	 */
	private $sDescription;

	/**
	 * The amount of the specific item
	 *
	 * @required false
	 * @var double
	 */
	private $dAmount;

	/**
	 * The list price of the specific item
	 *
	 * @required false
	 * @var double
	 */
	private $dListPrice;

	/**
	 * The selling price of the specific team
	 *
	 * @required false
	 * @var double
	 */
	private $dSellingPrice;

	/**
	 * If the specific item is part of a promotion
	 *
	 * @required false
	 * @var boolean
	 */
	private $bPromotion;


	/**
	 * Konstruktor
	 *
	 * @param array $aInput
	 * @throws \Exception If input is no ARRAY
	 */
	function __construct( $aInput ) {
		if ( is_array($aInput) ) {
			// Initialize array with input-data
			if ( isset($aInput["ean"]) ) {
				$this->sEan = $aInput["ean"];
			}
			if ( isset($aInput["description"]) ) {
				$this->sDescription = $aInput["description"];
			}
			if ( isset($aInput["amount"]) ) {
				$this->dAmount = $aInput["amount"];
			}
			if ( isset($aInput["listPrice"]) ) {
				$this->dListPrice = $aInput["listPrice"];
			}
			if ( isset($aInput["sellingPrice"]) ) {
				$this->dSellingPrice = $aInput["sellingPrice"];
			}
			if ( isset($aInput["promotion"]) ) {
				$this->bPromotion = $aInput["promotion"];
			}
		} else {
			throw new \Exception('Wrong input type');
		}
	}

	/**
	 * Check if This Object has valid Data
	 *
	 */
	public function isValid() {
		// Since the API does not require data, everything is valid.
		return true;

	}


	/**
	 * returns the Data as Array for API
	 * --> empty array if not valid
	 *
	 */
	public function getApiArray() {

		$aApiData = array();

		if ( $this->isValid() ) {
			$aApiData = array(
				"ean" => $this->sEan,
				"description" => $this->sDescription,
				"amount" => $this->dAmount,
				"listPrice" => $this->dListPrice,
				"sellingPrice" => $this->dSellingPrice,
				"promotion" => $this->bPromotion
			);
		}

		return $aApiData;
	}

	/**
	 * set Ean
	 *
	 * @param string $sEan
	 * @return $this
	 */
	public function setEan( $sEan ) {
		$this->sEan = $sEan;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->sDescription;
	}

	/**
	 * @param string $sDescription
	 * @return $this
	 */
	public function setDescription( $sDescription ) {
		$this->sDescription = $sDescription;
		return $this;
	}

	/**
	 * @return double
	 */
	public function getAmount() {
		return $this->dAmount;
	}

	/**
	 * @param double $dAmount
	 * @return $this
	 */
	public function setAmount( $dAmount ) {
		$this->dAmount = $dAmount;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getListPrice() {
		return $this->dListPrice;
	}

	/**
	 * @param float $dListPrice
	 * @return $this
	 */
	public function setListPrice( $dListPrice ) {
		$this->dListPrice = $dListPrice;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getSellingPrice() {
		return $this->dSellingPrice;
	}

	/**
	 * @param float $dSellingPrice
	 * @return $this
	 */
	public function setSellingPrice( $dSellingPrice ) {
		$this->dSellingPrice = $dSellingPrice;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isPromotion() {
		return $this->bPromotion;
	}

	/**
	 * @param boolean $bPromotion
	 * @return $this
	 */
	public function setPromotion( $bPromotion ) {
		$this->bPromotion = $bPromotion;
		return $this;
	}

}
