<?php

namespace Sinkacom\CreditPlusModule\Lib\Controller;

use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\CreditPlusMainData;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopCreditOffer;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopVoucher;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopRateTableMonthRow;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopCurrency;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopFinanceArticle;
use OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface;
/**
 * Credit-Plus Webshop API
 *
 * @author Sven Keil
 */
class CreditPlusWebshopAPI {

	/**
	 * Zählt die Requests
	 *
	 * @var integer
	 */
	private $iCallCount = 0;

	/**
	 * Debug-Ausgabe steuern
	 *
	 * @var bool
	 */
	private $bDebugInfo = false;
	private $bDebugPrintInfo = false;

	/**
	 * WSSoapClient Object
	 * @var WSSoapClient
	 */
	private $oWSSoapClient;

	/**
	 * WSSoapClient Object
	 * @var WSSoapClient
	 */
	private $oMainData;

	private $_aVouchers = array();

	/**
	 * @param array $aParams Set params like soap-user, soap-pass, soap-type, wsdl
	 */
	public function __construct($aParams = array()) {
		//initialize
		$this->oMainData = new CreditPlusMainData();
		if ( isset($aParams['soap-user']) ) {
			$this->oMainData->setSoapUser($aParams['soap-user']);
		}
		if ( isset($aParams['soap-pass']) ) {
			$this->oMainData->setSoapPass($aParams['soap-pass']);
		}
		if ( isset($aParams['soap-type']) ) {
			$this->oMainData->setSoapType($aParams['soap-type']);
		}
		if ( isset($aParams['wsdl']) ) {
			$this->oMainData->setWSDL($aParams['wsdl']);
		}
		
		$aMainData = $this->oMainData->getDefaultData();
		$this->createWSSoap($aMainData['wsdl'], $aMainData["soap-user"], $aMainData["soap-pass"], $aMainData["soap-type"]);
	}

	/**
	 * @param bool $bDebugInfo
	 * @param bool $bDebugPrintInfo
	 */
	public function setDebugState( $bDebugInfo = false, $bDebugPrintInfo = false ) {
		$this->bDebugInfo = $bDebugInfo;
		$this->bDebugPrintInfo = $bDebugPrintInfo;
	}

	/**
	 * Write SoapClient Request-Info
	 * to Log-File
	 *
	 */
	private function writeLastRequestInfo( $sToday = false, $bError = false, $sFunctionName = '' ) {
		if ( $this->oWSSoapClient != null ) {
			if ( $this->bDebugInfo ) {
				$sLastRequest = $this->oWSSoapClient->__getLastRequest();

				$sDirAndFileName = $this->getRelativeLogFileNamePath($sFunctionName."_request", $sToday, $bError);

				$sFSFileName = $this->getDocRoot().$sDirAndFileName;

				file_put_contents($sFSFileName, $sLastRequest);

				return '<a href="'.$sDirAndFileName.'"  data-info="'.$sFSFileName.'">request here</a>';
			}
		}

		return false;
	}

	/**
	 * Write SoapClient Request-Info
	 * to Log-File
	 */
	private function writeLastResponseInfo( $sToday = false, $bError = false, $sFunctionName = '' ) {
		if ( $this->oWSSoapClient != null ) {
			if ( $this->bDebugInfo ) {
				$sLastResponse = $this->oWSSoapClient->__getLastResponse();

				$sDirAndFileName = $this->getRelativeLogFileNamePath($sFunctionName."_response", $sToday, $bError);

				if ( $sLastResponse ) {
					$sFSFileName = $this->getDocRoot().$sDirAndFileName;
					file_put_contents($sFSFileName, $sLastResponse);
				}
				return '<a href="'.$sDirAndFileName.'">response here</a>';
			}
		}

		return false;
	}

	/**
	 * Write Request-Information-Data
	 * to Log-File
	 *
	 * @param string $sFunctionName The called API function name (e.g. getContracts)
	 * @param array $aArgumentData The arguments passed to $sFunctionName
	 * @param bool|string $sToday False, if time should be taken at the write time or string with file-system friendly timestamp
	 * @param bool $bError true, if logged event is an error
	 * @return bool|string False if $this->oWSSoapClient == null or $this->bDebugInfo == false, else <a> tag with link to log file
	 */
	private function writeRequestInformation( $sFunctionName, $aArgumentData, $sToday = false, $bError = false ) {
		if ( $this->oWSSoapClient != null ) {
			if ( $this->bDebugInfo ) {
				$strRequestInformation = $sFunctionName."\n".print_r($aArgumentData, true);


				$sDirAndFileName = $this->getRelativeLogFileNamePath($sFunctionName."_information", $sToday, $bError);

				file_put_contents($this->getDocRoot().$sDirAndFileName, $strRequestInformation);

				return '<a href="'.$sDirAndFileName.'">information here</a>';
			}
		}

		return false;
	}

	/**
	 * @param string $sFileName Desired file name, will be prefixed with send_$sToday_ and extended by .xml
	 * @param bool|string $sToday False, if time should be taken at the write time or string with file-system friendly timestamp
	 * @param bool $bError true, if logged event is an error
	 * @return string Relative filename for log file
	 */
	private function getRelativeLogFileNamePath( $sFileName, $sToday = false, $bError = false ) {
		if ( !$sToday ) {
			$sToday = date("Y-m-d-H-i-s");
		}
		$sError = "";
		if ( $bError ) {
			$sError = "_error";
		}
		$sLogDir = "log";
		$sFileName = preg_replace('/^\\_/', '', $sFileName);
		$sRelativeFileName = $sLogDir."/send_".$sToday.$sError."_".$sFileName.".xml";

		$this->makeDirIfNecessary($sLogDir);

		return $sRelativeFileName;
	}

	/**
	 * Creates directory if necessary
	 * @param string $sDir Directory name
	 */
	private function makeDirIfNecessary( $sDir ) {
		$sLogDir = $this->getDocRoot().$sDir;
		if ( !is_dir($sLogDir) ) {
			mkdir($sLogDir);
		}
	}

	/**
	 * Returns $_SERVER['DOCUMENT_ROOT'] in required format.
	 * @return string DocumentRoot with trailing / or \ (depending on used System)
	 */
	private function getDocRoot() {
		$sDocRoot = $_SERVER['DOCUMENT_ROOT'];

		if ( substr($sDocRoot,-1) !== DIRECTORY_SEPARATOR ) {
			$sDocRoot .= DIRECTORY_SEPARATOR;
		}
		
		return $sDocRoot;
	}

	/**
	 * Set WS-Security credentials
	 *
	 * @param string $sUsername
	 * @param string $sPassword
	 * @param string $sPasswordType
	 */
	public function createWSSoap( $sWSDL, $sUsername, $sPassword, $sPasswordType ) {
		$this->oWSSoapClient = new WSSoapClient($sWSDL, array(
			'encoding' => 'UTF-8',
			"trace" => true,
			"exceptions" => true
		));

		$this->oWSSoapClient->__setUsernameToken($sUsername, $sPassword, $sPasswordType);

	}

	/**
	 * Ping the Webshop API.
	 * Only checks whether the API is responding or not
	 *
	 * @return mixed|string
	 */
	public function pingCPWebshop() {
		$oResponse = "Error - Call failed";
		$sCallFunctionName = "ping";
		$aMainData = $this->oMainData->getDefaultData();
		$aData = array(
			'dealerNumber' => $aMainData['dealerNumber']
		);
		try {
			$oResponse = $this->callApi($sCallFunctionName, $aData);
		} catch ( \Exception $e ) {
			$this->printDebugCode(__FUNCTION__.' Exception', $e);
		}
		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * call createCreditOffer from Webshop API.
	 * Dummy if no Data given
	 *
	 * @param bool|array $aInput
	 * @param CreditPlusMainData $oDefaultData
	 * @param array $aCustomerAddress
	 * @param array $aShippingAddress
	 * @param array $aShopIntegrationData
	 * @return string|object "Error" or API Response Object
	 */
	public function createCreditOfferCPWebshop( $aInput = false, $oDefaultData = null, $aCustomerAddress = array(), $aShippingAddress = array(), $aShopIntegrationData = array() ) {
		$oResponse = "Error - Call failed";
		$sCallFunctionName = "createCreditOffer";

		$bIsDummyRequest = false;
		if ( $aInput == false ) {
			$bIsDummyRequest = true;
		}

		try {
			if ( $bIsDummyRequest ) {
				$shopIData = new WebshopCreditOffer();
				$aArgumentData = $shopIData->getDummyArray();
			} else {
				$shopIData = new WebshopCreditOffer($aInput, $oDefaultData, $aCustomerAddress, $aShippingAddress, $aShopIntegrationData);
				$aArgumentData = $shopIData->getCompleteApiArray();
			}

			$this->printDebugCode("createCreditOffer with ARRAY", $aArgumentData);

			$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);

		} catch ( \Exception $e ) {
			$this->printDebugCode(__FUNCTION__.' Exception', $e);
		}
		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}


	/**
	 * Return a product via API, initiates a "refund" and diminishes payout
	 * @param WebshopVoucher[] $aVouchers
	 * @return string|Object "Error", Error Message or the API Return Object
	 */
	public function returnProductCPWebshop( $aVouchers = array() ) {

		$oResponse = "Error - Call failed";
		$sCallFunctionName = "returnProduct";

		$bWorkStack = false;
		// If we are not sending specific Vouchers, we are using Vouchers from stack,
		// which we need to clean after we sent it.
		if ( !$aVouchers ) {
			$aVouchers = $this->_aVouchers;
			$bWorkStack = true;
		}

		if ( $aVouchers ) {
			$aArgumentData = array(
				'param1' => array(
					'voucher' => $aVouchers,
					'language' => 'de'
				)
			);
			try {
				$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);
				if ( $bWorkStack ) {
					// Clean up after pushing all stacked Vouchers into the API
					$this->_aVouchers = array();
				}
			} catch ( \Exception $e ) {
				$oResponse = $e->getMessage();
				$this->printDebugCode(__FUNCTION__.' Exception', $e);
			}
		}

		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * @param WebshopVoucher $oWebshopVoucher
	 */
	public function addReturnProduct( $oWebshopVoucher ) {
		$aVoucher = array(
			'value' => $oWebshopVoucher->value,
			'number' => $oWebshopVoucher->number,
			'date' => $oWebshopVoucher->date,
			'agentName' => $oWebshopVoucher->agentName,
			'done' => $oWebshopVoucher->done,
			'contractId' => $oWebshopVoucher->contractId,
			'dealerOrderNumber' => $oWebshopVoucher->dealerOrderNumber,
			'dealerNumber' => $oWebshopVoucher->dealerNumber
		);
		$this->_aVouchers[] = $aVoucher;
	}


	/**
	 * getContracts the Webshop API.
	 * @param array $aFilter Filters based on the CreditPlus API description
	 * @return string|Object String on Error, Object if response from CreditPlus API
	 */
	public function getContractsCPWebshop( $aFilter ) {
		$oResponse = "Error - Call failed";

		$sCallFunctionName = 'getContracts';

		$aMainData = $this->oMainData->getDefaultData();
		$aData = array(
			'dealerNumber' => $aMainData['dealerNumber']
		);

		$aData = array_merge($aData, $aFilter);
		$aArgumentData = array(
			'param1' => array(
				'filter' => $aData,
				'language' => 'de'
			)
		);

		$this->printDebugCode("getContracts with ARRAY", $aArgumentData);

		try {
			$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);
		} catch ( \Exception $e ) {
			$this->printDebugCode(__FUNCTION__.' Exception', $e);
		}

		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * getContracts the Webshop API.
	 * @param string $sDate Date Filter based on the CreditPlus API description - Currently requires xsd:dateTime format (=date('c'))
	 * @return string|Object String on Error, Object if response from CreditPlus API
	 */
	public function getRemittancesCPWebshop( $sDate = '' ) {
		$oResponse = "Error - Call failed";

		$sCallFunctionName = 'getRemittance';

		if ( !$sDate || ($sDate === '') ) {
			$sDate = date('c', time() - 86400); // 1 Day back
		}

		$aArgumentData = array(
			'param1' => array(
				'remittanceDate' => $sDate,
				'language' => 'de'
			)
		);

		$this->printDebugCode(__FUNCTION__." with ARRAY", $aArgumentData);

		try {
			$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);
		} catch ( \Exception $e ) {
			$this->printDebugCode(__FUNCTION__.' Exception', $e);
		}
		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * commitDelivery the Webshop API.
	 * @param array('dealerNumber' => '', 'dealerOrderNumber' => '', 'invoiceNumber' => '', 'invoicePrice' => '', 'deliveryDate' => '') $aOrderData
	 * @return string|object Text with error or object with data
	 */
	public function commitDeliveryCPWebshop( $aOrderData ) {
		$oResponse = "Call failed with Exception";
		$sCallFunctionName = 'commitDelivery';

		$aMainData = $this->oMainData->getDefaultData();
		$aData = array(
			'dealerNumber' => $aMainData['dealerNumber'],
			'dealerOrderNumber' => '',
			'invoiceNumber' => '',
			'invoicePrice' => 0.00,
			'deliveryDate' => date('c')
		);

		$aData = array_merge($aData, $aOrderData);
		$aArgumentData = array(
			'param1' => array(
				'delivery' => $aData,
				'language' => 'de'
			)
		);

		$this->printDebugCode("commitDeliery with ARRAY", $aArgumentData);
		if ( $aData['dealerOrderNumber'] && $aData['invoiceNumber'] && $aData['invoicePrice'] && $aData['deliveryDate'] ) {
			try {
				$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);
			} catch ( \Exception $e ) {
				$this->printDebugCode(__FUNCTION__.' Exception', $e);
			}
		} else {
			$oResponse = 'Missing mandatory parameters.';
			$aMissing = array();
			if ( !$aData['dealerOrderNumber'] ) {
				$aMissing[] = 'dealerOrderNumber missing.';
			}
			if ( !$aData['invoiceNumber'] ) {
				$aMissing[] = 'invoiceNumber missing.';
			}
			if ( !$aData['invoicePrice'] ) {
				$aMissing[] = 'invoicePrice missing.';
			}
			if ( !$aData['deliveryDate'] ) {
				$aMissing[] = 'deliveryDate missing.';
			}
			$this->printDebugCode(__FUNCTION__.' Exception', $aMissing);
		}

		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * Cancel an Order before it is delivered.
	 * This may not be called after the Contract has deliveryDone set to true.
	 * @param array('dealerNumber' => '', 'dealerOrderNumber' => '', 'invoiceNumber' => '', 'invoicePrice' => '', 'deliveryDate' => '') $aCancelData
	 * @return string|object Text with error or object with data
	 */
	public function cancelOrderCPWebshop( $aCancelData ) {
		$oResponse = "Call failed with Exception";
		$sCallFunctionName = 'cancelOrder';

		$aMainData = $this->oMainData->getDefaultData();
		$aData = array(
			'dealerNumber' => $aMainData['dealerNumber'],
			'dealerOrderNumber' => '',
			'cancelationDate' => date('c'),
			'cancelationFrom' => ''
		);

		$aData = array_merge($aData, $aCancelData);
		$aArgumentData = array(
			'param1' => array(
				'cancelation' => $aData,
				'language' => 'de'
			)
		);

		$this->printDebugCode("cancelOrderCPWebshop with ARRAY", $aArgumentData);
		if ( $aData['dealerOrderNumber'] && $aData['cancelationDate'] && $aData['cancelationFrom'] ) {
			try {
				$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);
			} catch ( \Exception $e ) {
				$this->printDebugCode(__FUNCTION__.' Exception', $e);
			}
		} else {
			$oResponse = 'Missing mandatory parameters.';
			$aMissing = array();
			if ( !$aData['dealerOrderNumber'] ) {
				$aMissing[] = 'dealerOrderNumber missing.';
			}
			if ( !$aData['cancelationDate'] ) {
				$aMissing[] = 'cancelationDate missing.';
			}
			if ( !$aData['cancelationFrom'] ) {
				$aMissing[] = 'cancelationFrom missing.';
			}
			$this->printDebugCode(__FUNCTION__.' Exception', $aMissing);
		}
		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * Change an Order before it is delivered.
	 * This may not be called after the Contract has deliveryDone set to true.
	 * @param array('dealerNumber' => '', 'dealerOrderNumber' => '', 'invoiceNumber' => '', 'invoicePrice' => '', 'deliveryDate' => '') $aChangeData
	 * @return string|object Text with error or object with data
	 */
	public function changeOrderCPWebshop( $aChangeData ) {
		$oResponse = "Call failed with Exception";
		$sCallFunctionName = 'changeOrder';

		$aMainData = $this->oMainData->getDefaultData();
		$aData = array(
			'dealerOrderNumber' => '',
			'changeDate' => date('c'),
			'dealerNumber' => $aMainData['dealerNumber'],
			'loanAmount' => 0.00,
			'cpReferenceNumber' => 0
		);

		$aData = array_merge($aData, $aChangeData);
		$aArgumentData = array(
			'param1' => array(
				'change' => $aData,
				'language' => 'de'
			)
		);

		$this->printDebugCode("cancelOrderCPWebshop with ARRAY", $aArgumentData);
		if ( $aData['dealerOrderNumber'] && $aData['changeDate'] && isset($aData['loanAmount']) ) {
			try {
				$oResponse = $this->callApi($sCallFunctionName, $aArgumentData);
			} catch ( \Exception $e ) {
				$this->printDebugCode(__FUNCTION__.' Exception', $e);
			}
		} else {
			$oResponse = 'Missing mandatory parameters.';
			$aMissing = array();
			if ( !$aData['dealerOrderNumber'] ) {
				$aMissing[] = '<br />dealerOrderNumber missing.';
			}
			if ( !$aData['changeDate'] ) {
				$aMissing[] = '<br />changeDate missing.';
			}
			if ( !$aData['loanAmount'] ) {
				$aMissing[] = '<br />loanAmount missing.';
			}
			$this->printDebugCode(__FUNCTION__.' Exception', $aMissing);
		}

		$this->printDebugCode($sCallFunctionName, $this->oWSSoapClient);

		return $oResponse;
	}

	/**
	 * Print out debug information or write them into the log - depending on settings
	 * @param string $sMessage Message to print
	 * @param object $oObject Related object
	 * @return int
	 */
	private function printDebugCode( $sMessage = '', $oObject = null ) {

		//SKeil 2015-12-09
		//zum debuggen
		if ( $this->bDebugPrintInfo == true ) {
			echo "<div style='background-color: yellow; display:none;' class='debug'>";
			echo "<p>DEBUG :: <b>START -- START -- START -- START</b> :: DEBUG</p>";

			$aBacktrace = debug_backtrace();
			$aLast = next($aBacktrace);
			$sLastClass = $aLast['class'];
			$sLastFunction = $aLast['function'];

			echo "<h3>$sLastClass | $sLastFunction</h3>";
			echo "<p>Ausgabe <b>MESSAGE</b></p>";
			var_dump($sMessage);

			echo "<p>Ausgabe <b>OBJECT</b></p>";
			var_dump($oObject);

			echo "<p>DEBUG :: <b>END -- END -- END -- END</b> :: DEBUG</p>";
			echo "</div>";

			return 1;
		}
		$this->writeLastResponseInfo(date('Y-m-d-H-i-s'), true, 'printDebugCode');

		return 0;
	}


	/**
	 * Overwrites the original method adding the security header.
	 * As you can see, if you want to add more headers, the method needs to be modified.
	 *
	 * @param string $sFunctionName Which API function this will call
	 * @param array $aArgumentData Which parameteres are being transmitted?
	 * @return object|string
	 */
	private function callApi( $sFunctionName, $aArgumentData ) {
		$this->iCallCount++;
		$sToday = date("Y-m-d-H-i-s");

		try {
			$oResponse = $this->oWSSoapClient->__call($sFunctionName, $aArgumentData);
			$this->writeLastRequestInfo($sToday, false, $sFunctionName);
			$this->writeLastResponseInfo($sToday, false, $sFunctionName);
		} catch ( \Exception $e ) {
			$oResponse = $e->getMessage();
			$this->writeLastRequestInfo($sToday, true, $sFunctionName);
			$this->writeLastResponseInfo($sToday, true, $sFunctionName);
			$this->writeRequestInformation($sFunctionName, $aArgumentData, $sToday, true);
		}

		return $oResponse;
	}

	/**
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface|oxLegacyDb $oDB
	 * @param string $sDealerNumber
	 * @param double $dProductPrice
	 * @param double $dInterest
	 * @param double $dMinRate
	 * @param int[] $aMonths
	 * @param WebshopCurrency $oCurrency
	 * @return WebshopRateTableMonthRow[]
	 */
	public function getFinancingOptions( $oDB, $sDealerNumber, $dProductPrice, $dInterest, $dMinRate, $aMonths, $oCurrency ) {

		/** @var $oRes ResultSetInterface */
		$oRes = $this->getResultSet($oDB, "SELECT scmonths, scfactor FROM sccp_rates WHERE sceffzins = '".number_format($dInterest, 1, ',', '')."' AND scmonths IN ('".implode("','", $aMonths)."') ORDER BY CAST(scmonths AS UNSIGNED INTEGER) ASC");

		$aMonthRows = array();
		if ( $this->getResultsNotEmpty($oDB, $oRes) ) {
			while ( $aFields = $this->getNextResult($oDB, $oRes) ) {
				$sFactor = floatval(str_replace(',', '.', $aFields['scfactor']));
				$fRate = ((($dProductPrice * $sFactor) / 100) + $dProductPrice) / floatval($aFields['scmonths']);
				// This is called "Kaufmännisches Runden" in Germany...
				$fRate = round(round(round(round($fRate, 5), 4), 3), 2);
				if ( $fRate >= $dMinRate ) {
					$fMonths = floatval($aFields['scmonths']);
					$fDarlehen = ($fMonths * $fRate);
					$fZinsen = ($fDarlehen - $dProductPrice);
					if ( $oCurrency->side && ($oCurrency->side == 'left') ) {
						$aMonthRow = new WebshopRateTableMonthRow(array(
							'months' => $fMonths,
							'interest' => $oCurrency->sign.' '.number_format($fZinsen, $oCurrency->decimals, $oCurrency->decimalSeparator, $oCurrency->thousandSeparator),
							'totalAmount' => $oCurrency->sign.' '.number_format($fDarlehen, $oCurrency->decimals, $oCurrency->decimalSeparator, $oCurrency->thousandSeparator),
							'monthlyRate' => $oCurrency->sign.' '.number_format($fRate, $oCurrency->decimals, $oCurrency->decimalSeparator, $oCurrency->thousandSeparator)
						));
					} else {
						$aMonthRow = new WebshopRateTableMonthRow(array(
							'months' => $fMonths,
							'interest' => number_format($fZinsen, $oCurrency->decimals, $oCurrency->decimalSeparator, $oCurrency->thousandSeparator).' '.$oCurrency->sign,
							'totalAmount' => number_format($fDarlehen, $oCurrency->decimals, $oCurrency->decimalSeparator, $oCurrency->thousandSeparator).' '.$oCurrency->sign,
							'monthlyRate' => number_format($fRate, $oCurrency->decimals, $oCurrency->decimalSeparator, $oCurrency->thousandSeparator).' '.$oCurrency->sign
						));
					}
					$aMonthRows[] = $aMonthRow;
				}
			}
		}
		return $aMonthRows;
	}

	/**
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface|oxLegacyDb $oDB
	 * @param string $sStatement
	 * @return bool|ResultSetInterface
	 */
	protected function &getResultSet( &$oDB, $sStatement ) {
		if ( $this->isOxid($oDB) ) {
			// OXID Code
			return $oDB->select($sStatement);
		} elseif ( (class_exists('Zend_Db_Adapter', false)) && ($oDB instanceof Zend_Db_Adapter) ) {
			// Shopware Code
		}
		return false;
	}

	/**
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface|oxLegacyDb $oDB
	 * @param ResultSetInterface $oRes
	 * @return bool
	 */
	protected function getResultsNotEmpty( &$oDB, &$oRes ) {
		if ( $this->isOxid($oDB) ) {
			// OXID Code
			return ($oRes && $oRes->count());
		} elseif ( (class_exists('Zend_Db_Adapter', false)) && ($oDB instanceof Zend_Db_Adapter) ) {
			// Shopware Code
		}
		return false;
	}

	/**
	 * @param \OxidEsales\Eshop\Core\DatabaseProvider|oxLegacyDb $oDB
	 * @param ResultSetInterface $oRes
	 * @return bool
	 */
	protected function getNextResult( &$oDB, &$oRes ) {
		if ( $this->isOxid($oDB) ) {
			// OXID Code
			if ( !$oRes->EOF ) {
				$aFields = $oRes->fields;
				$oRes->MoveNext();
				return $aFields;
			}
		} elseif ( (class_exists('Zend_Db_Adapter', false)) && ($oDB instanceof Zend_Db_Adapter) ) {
			// Shopware Code

		}
		return false;
	}

	/**
	 * @param oxLegacyDb|oxDb|\OxidEsales\Eshop\Core\DatabaseProvider $oDB
	 * @return bool
	 */
	protected function isOxid($oDB) {
		if ( (class_exists('oxLegacyDb', false)) && (get_class($oDB) == 'oxLegacyDb') ) {
			return true;
		} elseif ( (class_exists('oxDb', false)) && (get_class($oDB) == 'oxDb') ) {
			return true;
		} elseif( (class_exists('\OxidEsales\Eshop\Core\DatabaseProvider')) && (get_class($oDB) == '\OxidEsales\Eshop\Core\DatabaseProvider')) {
			return true;
		}
		return false;
	}

	/**
	 * @param float $dPrice Financed Price
	 * @param int $iMonths Financed Months
	 * @param float $dInterest Yearly nominal interest
	 * @param bool|float $dRateFactor If not false the factor used to calculate the monthly payment
	 * @return float The amount that will be paid each month
	 */
	public function getMonthRateByPriceMonthsAndInterest( $dPrice = 0.00, $iMonths = 0, $dInterest = 0.00, $dRateFactor = false ) {
		$dInterest = $dInterest/100.0;

		if ( $dRateFactor === false ) {
			// Calculate monthly payments by financial mathematics rate = price * (q/12) / (1 - (1/(1+(q/12))^n) | n = Months, 12 = months per year
			if ( $dInterest != 0 ) {
				//$dRate = $dPrice * ( ($dInterest/12) / ( 1 - pow(1+($dInterest/12),0-$iMonths) ) );

				// Summe mit Optimierung
				$dQ = (1/pow(1+$dInterest,1/12));
				$dS = (((1-pow($dQ,$iMonths+1))/(1-$dQ))-1);

				/*
				// Summe nach Formel - ohne mathematische Optimierung auf geometrische Reihe
				$dQ = pow(1+$dInterest,1/12);
				$dS = 0;
				for ( $iMonth = 1 ; $iMonth <= $iMonths ; $iMonth++ ) {
					$dS += (1/pow($dQ,$iMonth));
				}
				*/
				$dQny = pow((1+$dInterest),15/365);

				$dRate = $dPrice * $dQny * (1 / $dS);
			} else {
				// The formula above crashes because of divisions by zero...
				// As it doesn't need higher mathematics, this will suffice:
				$dRate = $dPrice/$iMonths;
			}
		} else {
			$dRate = $dPrice * $dRateFactor;
		}
		$dRate = round(round(round(round($dRate, 5), 4), 3), 2);
		return $dRate;
	}

	/**
	 * @param float $dInterest Nominal Interest as percentage (9.00 for 9% p.a.)
	 * @return float Effective Interest as Factor
	 */
	public function getEffectiveInterestFromNominalInterest($dInterest = 0.00) {
		$dInterest = $dInterest/100.0;
		$dEffective = pow(( 1 + $dInterest/12),12) -1;
		return round($dEffective*100.0,2);
	}

	/**
	 * @param float $dInterest Effective Interest as percentage (9.00 for 9% p.a.)
	 * @return float Nominal Interest as Factor
	 */
	public function getNominalInterestFromEffectiveInterest( $dInterest = 0.00 ) {
		$dInterest = $dInterest / 100.0;
		$dNominal = 12.0 * ( pow(($dInterest+1),1/12) - 1 );
		return round($dNominal*100.0,2);
	}

	/**
	 * Takes away all the formatting things from a formatted price and returns a float value of it
	 * This is always currency specific, as it will use the currency object from the shop
	 * @param string $sPrice Formatted Price (e.g. 12.421,41 EUR)
	 * @param WebshopCurrency $oCurrency Currency Object (properties sign, thousand, dec, decimal are required)
	 * @return float Float value of price (e.g. 12421.41)
	 */
	public function retrieveFloatPriceFromFormattedPrice($sPrice, &$oCurrency) {
		// Remove Currency sign, spaces, number formatting options and recreate float from formatted price...
		$sPrice = str_replace($oCurrency->sign,'',$sPrice);
		$sPrice = str_replace(' ','',$sPrice);
		$sPrice = str_replace($oCurrency->thousandSeparator,'',$sPrice);
		$sPrice = str_replace($oCurrency->decimalSeparator,'',$sPrice);
		$sPrice = substr($sPrice,0,-$oCurrency->decimals).'.'.substr($sPrice,-$oCurrency->decimals);
		$dPrice = floatval($sPrice);
		return $dPrice;
	}


	/**
	 * Takes away the formatting from a formatted value and returns it as a float.
	 * @param string $sInterestRate Formatted interest rate (e.g. 6,20 %)
	 * @param WebshopCurrency $oCurrency
	 * @return float Float value of interest rate (e.g. 6.2)
	 */
	public function retrieveFloatFromFormattedInterestRate($sInterestRate, &$oCurrency ) {
		return floatval(str_replace(array(
			' ',
			$oCurrency->thousandSeparator,
			$oCurrency->decimalSeparator,
			'%'
		), array(
			'',
			'',
			'.',
			''
		), $sInterestRate));
	}

	/**
	 * Returns the article, which should be used to display the table
	 * @param WebshopFinanceArticle[] $aArticles Array of Basket Articles with corresponding prices and amounts
	 * @param string $sMethod One strategy of "most-expensive", "weighted-majority", "number-majority", "cheapest"
	 * @return WebshopFinanceArticle Article which should be used for reference when creating the table
	 */
	public function getFinancingArticleReference( $aArticles = array(), $sMethod = 'weighted-majority') {
		$oArticle = $aArticles[0];

		if ( $sMethod == 'most-expensive' ) {
			$oArticle = $this->getFinancingArticleReferenceMostExpensive($aArticles);
		} elseif ( $sMethod == 'weighted-majority' ) {
			$oArticle = $this->getFinancingArticleReferenceWeightedMajority($aArticles);
		} elseif ( $sMethod == 'number-majority' ) {
			$oArticle = $this->getFinancingArticleReferenceNumberMajority($aArticles);
		} elseif ( $sMethod == 'cheapest' ) {
			$oArticle = $this->getFinancingArticleReferenceCheapest($aArticles);
		}

		return $oArticle;
	}

	/**
	 * @param WebshopFinanceArticle[] $aArticles The array of possible articles
	 * @return WebshopFinanceArticle The article to be used
	 */
	protected function getFinancingArticleReferenceMostExpensive($aArticles) {
		/** @var WebshopFinanceArticle $oArticle */
		$oArticle = reset($aArticles);
		foreach ( $aArticles as $oPossibleArticle ) {
			if ( $oArticle->mostExpensiveInterestRate < $oPossibleArticle->mostExpensiveInterestRate ) {
				$oArticle = $oPossibleArticle;
			} elseif ( $oArticle->mostExpensiveInterestRate == $oPossibleArticle->mostExpensiveInterestRate ) {
				if ( $oArticle->cheapestInterestRate < $oPossibleArticle->cheapestInterestRate ) {
					// If most expensive interest rate is equal, use cheapest for comparison
					$oArticle = $oPossibleArticle;
				}
			}
		}
		return $oArticle;
	}
	/**
	 * @param WebshopFinanceArticle[] $aArticles The array of possible articles
	 * @return WebshopFinanceArticle The article to be used
	 */
	protected function getFinancingArticleReferenceWeightedMajority($aArticles) {
		/** @var WebshopFinanceArticle[] $aSortable */
		$aSortable = array();
		foreach ( $aArticles as $oArticle ) {
			$dIndex = ($oArticle->amount*$oArticle->unitprice);
			// If not set, or cheapest rate is smaller or cheapest rate is equal and most expensive rate is smaller
			// == best possible outcome for customer, as it doesn't matter in which way he put the items in his basket
			if (
				!isset($aSortable[$dIndex])
				|| ($aSortable[$dIndex]->cheapestInterestRate > $oArticle->cheapestInterestRate)
				|| (
					($aSortable[$dIndex]->cheapestInterestRate === $oArticle->cheapestInterestRate) &&
					($aSortable[$dIndex]->mostExpensiveInterestRate > $oArticle->mostExpensiveInterestRate)
				)
			) {
				$aSortable[$dIndex] = $oArticle;
			}
		}
		ksort($aSortable);
		$oArticle = array_pop($aSortable);
		return $oArticle;
	}
	/**
	 * @param WebshopFinanceArticle[] $aArticles The array of possible articles
	 * @return WebshopFinanceArticle The article to be used
	 */
	protected function getFinancingArticleReferenceNumberMajority($aArticles) {
		/** @var WebshopFinanceArticle[] $aSortable */
		$aSortable = array();
		foreach ( $aArticles as $oArticle ) {
			$dIndex = ($oArticle->amount);
			// If not set, or cheapest rate is smaller or cheapest rate is equal and most expensive rate is smaller
			// == best possible outcome for customer, as it doesn't matter in which way he put the items in his basket
			if (
				!isset($aSortable[$dIndex])
				|| ($aSortable[$dIndex]->cheapestInterestRate > $oArticle->cheapestInterestRate)
				|| (
					($aSortable[$dIndex]->cheapestInterestRate === $oArticle->cheapestInterestRate) &&
					($aSortable[$dIndex]->mostExpensiveInterestRate > $oArticle->mostExpensiveInterestRate)
				)
			) {
				$aSortable[$dIndex] = $oArticle;
			}
		}
		ksort($aSortable);
		/** @var WebshopFinanceArticle $oArticle */
		$oArticle = array_pop($aSortable);
		return $oArticle;
	}
	/**
	 * @param WebshopFinanceArticle[] $aArticles The array of possible articles
	 * @return WebshopFinanceArticle The article to be used
	 */
	protected function getFinancingArticleReferenceCheapest($aArticles) {
		/** @var WebshopFinanceArticle $oArticle */
		$oArticle = reset($aArticles);
		foreach ( $aArticles as $oPossibleArticle ) {
			if ( $oArticle->cheapestInterestRate > $oPossibleArticle->cheapestInterestRate ) {
				$oArticle = $oPossibleArticle;
			} elseif ( $oArticle->cheapestInterestRate == $oPossibleArticle->cheapestInterestRate ) {
				if ( $oArticle->mostExpensiveInterestRate > $oPossibleArticle->mostExpensiveInterestRate ) {
					// If cheapest rate is equal, compare by most expensive rate
					$oArticle = $oPossibleArticle;
				}
			}
		}
		return $oArticle;
	}
}

?>
