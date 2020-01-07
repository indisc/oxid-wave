<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusHelper;

/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 22.12.2015
 * Time: 11:25
 */
class CreditPlusMainData {

	private $_aCreditOfferDefaults = array(
		"dealerNumber" => 713000,
		"dealerName" => "Test HDL Sinkacom",
		"wsdl" => "https://kesstest.creditplus.de/ws_webshop/cxf/Webshop?wsdl",
		"soap-user" => "sinkacomTest",
		"soap-pass" => "SinkaCom2015",
		"soap-type" => "PasswordDigest",
		"partnername" => "DIRM",
		// Fixed to 11, Webshop Article
		"productclassId" => 11,
		// Set by CreditPlus-defined product groups, overridden by value from basket
		"producttypeId" => 56,
		"token" => "68LkehxSFbNv678aCuSg"
	);

	function __construct() {
		//initialize
		// Single values are set to the current installations values by using or chaining setPartnerName and so on...
	}

	/**
	 * @deprecated Use getDefaultData instead
	 * @return array
	 */
	public function getDefaultDat() {
		return $this->getDefaultData();
	}

	/**
	 * @return array Default Data
	 */
	public function getDefaultData() {
		return $this->_aCreditOfferDefaults;
	}

	public function getApiMainData() {

		$aApiMainData = array(
			'dealerNumber' => $this->_aCreditOfferDefaults["dealerNumber"],
			'partnername' => $this->_aCreditOfferDefaults["partnername"],
			'productclassId' => $this->_aCreditOfferDefaults["productclassId"],
			'producttypeId' => $this->_aCreditOfferDefaults["producttypeId"]
		);
		return $aApiMainData;
	}

	/**
	 * @param string $sDealerNumber
	 * @return CreditPlusMainData
	 */
	public function setDealerNumber( $sDealerNumber ) {
		$this->_aCreditOfferDefaults['dealerNumber'] = $sDealerNumber;
		return $this;
	}

	/**
	 * @param string $sPartnerName
	 * @return CreditPlusMainData
	 */
	public function setPartnerName( $sPartnerName ) {
		$this->_aCreditOfferDefaults['partnername'] = $sPartnerName;
		return $this;
	}

	/**
	 * @param int $sProductClassID
	 * @return CreditPlusMainData
	 */
	public function setProductClassID( $sProductClassID ) {
		$this->_aCreditOfferDefaults['productclassId'] = $sProductClassID;
		return $this;
	}

	/**
	 * @param int $sProductTypeID
	 * @return CreditPlusMainData
	 */
	public function setProductTypeID( $sProductTypeID ) {
		$this->_aCreditOfferDefaults['producttypeId'] = $sProductTypeID;
		return $this;
	}

	/**
	 * @param string $sDealerName
	 * @return CreditPlusMainData
	 */
	public function setDealerName( $sDealerName ) {
		$this->_aCreditOfferDefaults['dealerName'] = $sDealerName;
		return $this;
	}

	/**
	 * @param string $sWSDL
	 * @return CreditPlusMainData
	 */
	public function setWSDL( $sWSDL ) {
		$this->_aCreditOfferDefaults['wsdl'] = $sWSDL;
		return $this;
	}

	/**
	 * @param string $sSoapUser
	 * @return CreditPlusMainData
	 */
	public function setSoapUser( $sSoapUser ) {
		$this->_aCreditOfferDefaults['soap-user'] = $sSoapUser;
		return $this;
	}

	/**
	 * @param string $sSoapPass
	 * @return CreditPlusMainData
	 */
	public function setSoapPass( $sSoapPass ) {
		$this->_aCreditOfferDefaults['soap-pass'] = $sSoapPass;
		return $this;
	}

	/**
	 * @param string $sSoapType
	 * @return CreditPlusMainData
	 */
	public function setSoapType( $sSoapType ) {
		$this->_aCreditOfferDefaults['soap-type'] = $sSoapType;
		return $this;
	}
}
