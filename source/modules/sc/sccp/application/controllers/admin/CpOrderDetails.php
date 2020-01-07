<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 * @version   OXID eShop CE
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
/**
 * Admin order overview manager.
 * Collects order overview information, updates it on user submit, etc.
 * Admin Menu: Orders -> CreditPlus Orders -> Details.
 */
class CpOrderDetails extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController {

	protected $_sThisTemplate = 'sccp_cporder_details.tpl';

	/**
	 * Executes parent method parent::render(), creates oxOrder, passes
	 * it's data to Smarty engine and returns name of template file
	 * "order_overview.tpl".
	 *
	 * @return string
	 */
	public function render() {
		$oConfig = $this->getConfig();
		$sReturn = parent::render();

		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
		$oCur = $oConfig->getActShopCurrencyObject();
		$oLang =  \OxidEsales\Eshop\Core\Registry::getLang();

		$sOxid = $this->getEditObjectId();
		if ( $sOxid != "-1" && isset($sOxid) ) {
			// load object
			$oOrder->load($sOxid);

			$this->_aViewData['edit'] = $oOrder;
			$this->_aViewData['oShipSet'] = $oOrder->getShippingSetList();
			$this->_aViewData['paymentType'] = $this->_getPaymentType($oOrder);
		} else {
			$this->_aViewData['oShipSet'] = array();
			$this->_aViewData['paymentType'] = array();
		}

		$this->_aViewData['afolder'] = $oConfig->getConfigParam('aOrderfolder');
		$this->_aViewData['alangs'] = $oLang->getLanguageNames();

		$this->_aViewData["currency"] = $oCur;

		$this->assignContractData($oOrder);

		return $sReturn;
	}

	/**
	 * Returns user payment used for current order. In case current order was executed using
	 * credit card and user payment info is not stored in db (if oxConfig::blStoreCreditCardInfo = false),
	 * just for preview user payment is set from oxPayment
	 *
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder Order object
	 *
	 * @return \OxidEsales\Eshop\Application\Model\UserPayment|\OxidEsales\Eshop\Application\Model\Payment
	 */
	protected function _getPaymentType( $oOrder ) {
		/** @var \OxidEsales\Eshop\Application\Model\UserPayment|\OxidEsales\Eshop\Application\Model\Payment $oUserPayment */
		if ( !($oUserPayment = $oOrder->getPaymentType()) && $oOrder->oxorder__oxpaymenttype->value ) {
			$oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
			if ( $oPayment->load($oOrder->oxorder__oxpaymenttype->value) ) {
				// in case due to security reasons payment info was not kept in db
				$oUserPayment = oxNew(\OxidEsales\Eshop\Application\Model\UserPayment::class);
				$oUserPayment->oxpayments__oxdesc = new \OxidEsales\Eshop\Core\Field($oPayment->oxpayments__oxdesc->value);
			}
		}

		return $oUserPayment;
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order|\OxidEsales\Eshop\Application\Model\Order $oOrder The currently loaded Order
	 * @return object CreditPlus API Response to getContracts with Filter to this ID
	 */
	protected function assignContractData($oOrder) {
		$oConfig = $this->getConfig();
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSApi = new CreditPlusWebshopAPI($aSoapParams);

		$bLiveMode = true;

		if ( $bLiveMode ) {
			$oContract = $oOrder->getContractData();
		} else {
			$oCreditOfferResponse = $oWSApi->getContractsCPWebshop( array() );
			if ( !is_array($oCreditOfferResponse->return) ) {
				$oCreditOfferResponse->return = array($oCreditOfferResponse->return);
			}
			$oContract = $oCreditOfferResponse->return[3];
		}

		$oLang = $this->getLang();

		$oContractData = new \stdClass();
		$oContractData->cpConditionName = $oContract->conditionName;
		$oContractData->cpReferenceNumber = $oContract->cpReferenceNumber;
		$oContractData->cpDealerOrderNumber = $oContract->dealerOrderNumber;
		$oContractData->cpState = $this->getContractState($oContract->state,$oContract->finallyApproved,$oContract->deliveryDone);
		$oContractData->cpPrice = $oLang->formatCurrency(floatval($oContract->price));
		$oContractData->cpMonths = $oContract->creditDuration;

		$oLoanAddress = new \stdClass();
		$oLoanAddress->oxfname = $oContract->forename;
		$oLoanAddress->oxlname = $oContract->name;
		$oLoanAddress->oxstreet = $oContract->street;
		$oLoanAddress->oxzip = $oContract->areaCode;
		$oLoanAddress->oxcity = $oContract->town;

		$this->_aViewData['oLoanAddress'] = $oLoanAddress;
		$this->_aViewData['oContractData'] = $oContractData;

		$this->_aViewData['oContracts'] = isset($oCreditOfferResponse)?$oCreditOfferResponse:json_decode('{"return":['.json_encode($oContract).']}');

		return $this->_aViewData['oContracts'];
	}

	protected function getContractState($iStateNum, $bFinallyApproved, $bDeliveryDone) {
		$sHumanReadableState = 'Undefiniert';
		switch ($iStateNum) {
			case 20:
				$sHumanReadableState = 'Offen';
				break;
			case 24:
				$sHumanReadableState = 'Vorläufig Genehmigt';
				if ( $bFinallyApproved ) {
					$sHumanReadableState = 'Lieferfreigabe';
				}
				break;
			case 25:
				$sHumanReadableState = 'Warten auf Unterlagen';
				break;
			case 32:
				$sHumanReadableState = 'Auszahlung';
				break;
			case 92:
				$sHumanReadableState = 'Weich abgelehnt';
				break;
			case 93:
				$sHumanReadableState = 'Abgelehnt';
				break;
			case 95:
				$sHumanReadableState = 'Storniert';
				break;
			case 99:
				$sHumanReadableState = 'Ausgezahlt';
				break;
		}
		if ( $bDeliveryDone ) {
			$sHumanReadableState .= ' und ausgeliefert';
		}

		return $sHumanReadableState;
	}

	/**
	 * Gets proper file name
	 *
	 * @param string $sFilename file name
	 *
	 * @return string
	 */
	public function makeValidFileName( $sFilename ) {
		$sFilename = preg_replace('/[\s]+/', '_', $sFilename);
		$sFilename = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $sFilename);

		return str_replace(' ', '_', $sFilename);
	}


	/**
	 * Sends order.
	 * Copied from order_main, extended by commitDeliveryCPWebshop
	 * @see order_main::sendorder()
	 * @see CreditPlusWebshopAPI::commitDeliveryCPWebshop()
	 */
	public function sendorder()
	{
		$bDemo = false;
		$soxId = $this->getEditObjectId();
		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
		if ($oOrder->load($soxId)) {
			$oConfig = $this->getConfig();
			$aSoapParams = array(
				'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
				'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
				'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
				'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
			);
			$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
			$oWSApi->setDebugState(true,false);

			$aData = array(
				'dealerNumber' => $oConfig->getShopConfVar('sCPDealer',null,'module:sccp'),
				'dealerOrderNumber' => $oOrder->oxorder__oxtransid->value,
				'invoiceNumber' => $oOrder->oxorder__oxordernr->value,
				'invoicePrice' => $oOrder->oxorder__oxtotalordersum->value,
				'deliveryDate' => date('c')
			);
			if ( $bDemo ) {
				$aData['dealerOrderNumber'] = 'SinkacomEntsch004';
				$aData['invoicePrice'] = 5000.00;
			}
			// $oContractData = $oOrder->getContractData($aData['dealerOrderNumber']);
			// $oCreditOfferResponse = $oWSApi->getContractsCPWebshop( array('dealerOrderNumber' => array($oOrder->oxorder__oxtransid->value) ) );
			$oResponse = $oWSApi->commitDeliveryCPWebshop( $aData );
			if ( is_string($oResponse) ) {
				$this->_aViewData['sError'] = 500;
				$this->_aViewData['sErrorMessage'] = $oResponse;
			} else {
				if ( (property_exists($oResponse,'confirmation')) && (property_exists($oResponse->confirmation,'isSucceed')) && ( $oResponse->confirmation->isSucceed ) ) {
					$this->_aViewData['sError'] = 200;
					$this->_aViewData['sErrorMessage'] = 'Status geändert';
				} else {
					if ( ( property_exists($oResponse,'confirmation') ) && ( property_exists($oResponse->confirmation,'confirmationItems') ) && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
						$iErrorCode = intval($oResponse->confirmation->confirmationItems->errorCode);
						$sErrorMessage = $oResponse->confirmation->confirmationItems->errorMessage;
						$aError = array(
							'sError' => (500+$iErrorCode),
							'sErrorMessage' => $sErrorMessage
						);
						if ( $iErrorCode == 1 ) {
							// Kein Auftrag gefunden
						} elseif ( $iErrorCode == 2 ) {
							// Pflichtfelder fehlen, kann durch Code heraus nicht passieren
						} elseif ( $iErrorCode == 3 ) {
							// Rechnungssumme > Kreditsumme
						} elseif ( $iErrorCode == 4 ) {
							// Lieferdatum in der Zukunft
						} elseif ( $iErrorCode == 5 ) {
							// Vertrag ist nicht genehmigt
						} elseif ( $iErrorCode == 6 ) {
							// Vertrag ist bereits ausbezahlt
						} elseif ( $iErrorCode == 10 ) {
							// Vertrag ist nicht finallyApproved
						} elseif ( $iErrorCode == 11 ) {
							// Vertrag steht bereits auf ausgeliefert
						} elseif ( $iErrorCode == 14 ) {
							// Irgendein Pflichtfeld sprengt sein Format/Länge
							// dealerOrderNumber > 40
							// invoiceNumber > 40
						} elseif ( $iErrorCode == 23 ) {
							// Rechnungssumme < Kreditsumme
						} else {
							// Keine Ahnung was passiert ist
							//TODO: Code
						}
						foreach ( $aError as $sKey => $sValue ) {
							$this->_aViewData[$sKey] = $sValue;
						}
					} else {
						$this->_aViewData['sError'] = 4.99;
						$this->_aViewData['sErrorMessage'] = 'An Error has been Xzibited (An Error in an Error has occured)';
					}
				}
			}

			if ( $this->_aViewData['sError'] == 200 ) {
				// #632A
				$oOrder->oxorder__oxsenddate = new \OxidEsales\Eshop\Core\Field(date("Y-m-d H:i:s", \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsDate::class)->getTime()));
				$oOrder->save();

				// #1071C
				$oOrderArticles = $oOrder->getOrderArticles(true);
				if (\OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter("sendmail")) {
					// send eMail
					$oEmail = oxNew(\OxidEsales\Eshop\Core\Email::class);
					$oEmail->sendSendedNowMail($oOrder);
				}

			}
		}
	}

	/**
	 * Cancels order and its order articles
	 */
	public function storno()
	{
		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
		$oLang = $this->getLang();
		if ($oOrder->load($this->getEditObjectId())) {
			$aError = $oOrder->cancelOrder();
			if ( $aError ) {
				foreach ( $aError as $sKey => $sValue ) {
					$this->_aViewData[$sKey] = $sValue;
				}
			} else {
				$this->_aViewData['sError'] = 200;
				$this->_aViewData['sErrorMessage'] = $oLang->translateString('SCCP_CPORDER_DETAILS_MESSAGE_TOTALLY_RETURNED');
			}
		}

		$this->resetContentCache();

		//we call init() here to load list items after storno()
		$this->init();
	}

	/**
	 * @return \OxidEsales\Eshop\Core\Language
	 */
	protected function getLang() {
		/** @var \OxidEsales\Eshop\Core\Language $oLang */
		$oLang = null;

		if ( class_exists('\OxidEsales\Eshop\Core\Registry') ) {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		} elseif ( method_exists('oxLang', 'getInstance' ) ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}

		return $oLang;
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpOrderDetails::class,'sccp_cporder_details');
