<?php
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 16.12.2015
 * Time: 10:57
 */

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;
use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\CreditPlusMainData;
use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\Validation;
use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\DataHandling;
//namespace CreditPlusObjects;
//hat mit Namespaces nicht funtkioniert...

class WebshopCreditOffer {

	/**
	 * Default Data
	 *
	 * @var array
	 */
	private $aDefaultApiData;

	/**
	 * Agent of the partner who placed the
	 * order
	 * agentId length > 27    14    technical
	 *
	 * @required false
	 * @maxlength 27
	 * @var string
	 */
	public $sAgentId;

	/**
	 * Loan Amount
	 * amount > maxamount    30    business
	 *
	 * @required true
	 * @var double
	 */
	public $dLoanAmount;

	/**
	 * The Ordernumber in the Dealer-System
	 * (FSI WON)
	 * Dealerordernumber length > 40    14    technical
	 *
	 * @required true
	 * @maxlength 40
	 * @var string
	 */
	public $sDealerOrderNumber;

	/**
	 * Customer details
	 *
	 * firstName length > 27    14    technical
	 * lastName length > 27    14    technical
	 * street length > 27    14    technical
	 * postalCode > 5    14    technical
	 * city length > 27    14    technical
	 * postalCode and city have to match    26    business
	 * salutation not 1 or 2    27    technical
	 *
	 * @required true
	 * @var WebshopCustomerData
	 */
	public $oCustomerAddress;

	/**
	 * Customer information to which the order
	 * Is shipped     *
	 *
	 * @required true
	 * @var WebshopCustomerData
	 */
	public $oShippingAddress;

	/**
	 * Additional Parameters for Shop
	 * Integration
	 *
	 * @required false
	 * @var ShopIntegrationData
	 */
	public $oShopIntegrationData;

	/**
	 * Konstruktor
	 *
	 * @param array $aInput
	 * @param CreditPlusMainData $oDefaultData
	 * @param array $aCustomerAddress
	 * @param array $aShippingAddress
	 * @param array $aShopIntegrationData
	 * @throws \Exception If input is no ARRAY
	 */
	function __construct( $aInput = array() , $oDefaultData = null, $aCustomerAddress = array(), $aShippingAddress = array(), $aShopIntegrationData = array() ) {

		$this->setApiMainData($oDefaultData);

		$this->oCustomerAddress = new WebshopCustomerData($aCustomerAddress);
		$this->oShippingAddress = new WebshopCustomerData($aShippingAddress);
		$this->oShopIntegrationData = new ShopIntegrationData($aShopIntegrationData);

		if ( is_array($aInput) ) {
			if ( isset($aInput["agentId"]) ) {
				$this->sAgentId = $aInput["agentId"];
			}
			if ( isset($aInput["loanAmount"]) ) {
				$this->dLoanAmount = $aInput["loanAmount"];
			}
			if ( isset($aInput["dealerOrderNumber"]) ) {
				$this->sDealerOrderNumber = $aInput["dealerOrderNumber"];
			}

		} else {
			throw new \Exception('Wrong input type');
		}
	}

	/**
	 * @return array
	 */
	private function getApiMainData(){
		if ( $this->aDefaultApiData === null ) {
			$this->setApiMainData();
		}
		return $this->aDefaultApiData;
	}

	/**
	 * @param CreditPlusMainData $oDefaultData
	 */
	private function setApiMainData( $oDefaultData = null ) {
		if(!isset($oDefaultData)){
			$oDefaultData = new CreditPlusMainData();
		}
		$this->aDefaultApiData = $oDefaultData->getApiMainData();
	}

	/**
	 * Check if This Object has valid Data
	 *
	 */
	public function isValid() {

		$validation = new Validation();

		//Validate agentId
		if ( $validation->isNotNull($this->sAgentId) ) {
			if ( $validation->isMaxLengthValid($this->sAgentId, 27) == true ) {
				return false;
			}
		}

		//Validate loanAmount
		if ( !$validation->isNotNull($this->dLoanAmount) ) {
			return false;
		}

		//dealerOrderNumber
		if ( !$validation->isNotNull($this->sDealerOrderNumber) ) {
			return false;
		} else {
			if ( $validation->isMaxLengthValid($this->sDealerOrderNumber, 40) == true ) {
				return false;
			}
		}

		//customerAdress
		if ( !$validation->isNotNull($this->oCustomerAddress) ) {
			return false;
		} else {
			//Adresse validieren
			$this->oCustomerAddress->isValid();
		}

		//shippingAdress
		// --> darf leer sein, aber nicht NULL
		if ( !$validation->isNotNull($this->oShippingAddress) ) {
			return false;
		}

		//shopIntegrationData

		return $this->isApiMainDataValid($validation);

	}

	/**
	 * @param Validation $validation
	 * @return bool
	 */
	public function isApiMainDataValid($validation) {

		//dealerNumber
		if ( $validation->isInteger($this->aDefaultApiData["dealerNumber"]) == false) {
			return false;
		}

		//partnername
		if ( $validation->isNotNull($this->aDefaultApiData["partnername"]) ) {
			//String laengenbeschraenkung nicht bekannt
			return false;
		}

		//productclassId
		if ( $validation->isInteger($this->aDefaultApiData["productclassId"]) == false) {
			return false;
		}

		//producttypeId
		if ( $validation->isInteger($this->aDefaultApiData["producttypeId"]) == false) {
			return false;
		}

		return true;
	}


	/**
	 * returns the Data as Array for API
	 * --> empty array if not valid
	 *
	 * @return array
	 *
	 */
	public function getApiArray() {

		$aApiData = array();

		if ( $this->isValid() ) {

			try {
				$oDataHandling = new DataHandling();
				$aApiData = $oDataHandling->getClassArray($this);

			} catch ( \Exception $e ) {
				echo $e->getMessage();
			}
		}

		return $aApiData;
	}

	/**
	 *
	 * returns a Dummy-Data Array for API Requests
	 *
	 * @return array
	 */
	public function getCompleteApiArray() {
		$aApiData = array();

		if ( true || $this->isValid() ) {
			$aCustomerAddress = $this->oCustomerAddress->getApiArray();
			$aShoppingAddress = $this->oShippingAddress->getApiArray();
			$aShopIntegrationData = $this->oShopIntegrationData->getApiArray();

			$aWebshopCreditOffer = array(
				'agentId' => $this->sAgentId,
				'loanAmount' => $this->dLoanAmount,
				'dealerOrderNumber' => $this->sDealerOrderNumber,
				'customerAdress' => $aCustomerAddress
				,'shippingAdress' => $aShoppingAddress,
				'shopIntegrationData' => $aShopIntegrationData
			);
			$aWebshopCreditOffer = array_merge($this->getApiMainData(), $aWebshopCreditOffer);

			$aApiData = array(
				'param1' => array(
					'webshopCreditOffer' => $aWebshopCreditOffer,
					'language' => 'de'
				)
			);
		}

		return $aApiData;
	}

	/**
	 *
	 * returns a Dummy-Data Array for API Requests
	 *
	 * @param bool/string $heute
	 * @return array
	 */
	public function getDummyArray($sToday = false) {

		if(!$sToday){
			$sToday = date("Ymd-His");
		}

		$dLoanAmount = 1500;
		$sDealerOrderNumber = "SCCP".$sToday;
		$sHdlName = "Sinkacom";

		$aCustomerAdress = $this->oCustomerAddress->getDummyArray();
		$aShoppingAdress = $this->oShippingAddress->getDummyArray();
		$aShopIntegrationData = $this->oShopIntegrationData->getDummyArray();

		$aWebshopCreditOffer = array(
			'agentId' => "$sHdlName",
			'loanAmount' => ($dLoanAmount+400),
			'dealerOrderNumber' => $sDealerOrderNumber,
			'customerAdress' => $aCustomerAdress,
			'shippingAdress' => $aShoppingAdress,
			'shopIntegrationData' => $aShopIntegrationData
		);

		$aWebshopCreditOffer = $aWebshopCreditOffer + $this->getApiMainData();

		$dummyData = array(
			'param1' => array(
				'webshopCreditOffer' => $aWebshopCreditOffer,
				'language' => 'de'
			)
		);

		return $dummyData;
	}


}
