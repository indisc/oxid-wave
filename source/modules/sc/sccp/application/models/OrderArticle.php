<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 02.02.16
 * Time: 17:03
 */

namespace Sinkacom\CreditPlusModule\Model;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopVoucher;

class OrderArticle extends OrderArticle_parent {

	/**
	 * @param string $sArticleID oxartid on from oxorderarticles entry
	 * @return bool|string[]
	 */
	public function delete( $sArticleID = '' ) {
		$aError = $this->sccpReturnProduct();
		if ( $aError ) {
			return $aError;
		}
		return parent::delete($sArticleID);
	}

	/**
	 * @return string[]|null Array on error, null on success
	 */
	public function sccpReturnProduct() {
		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = $this->getOrder();
		if ( (substr($oOrder->oxorder__oxpaymenttype->value, 0, 5) == 'sccp_') && $oOrder->oxorder__oxordernr->value && $oOrder->oxorder__oxtransid->value ) {
			$sDealerOrderNumber = $oOrder->oxorder__oxtransid->value;
			$sDate = date('c');
			$oContractData = $oOrder->getContractData($sDealerOrderNumber);

			/** @var oxPrice $oBasePrice */
			$oBasePrice = $this->getBasePrice($this->oxorderarticles__oxamount->rawValue);
			$dBasePrice = $oBasePrice->getBruttoPrice();
			$dReturnAmount = $dBasePrice * floatval($this->oxorderarticles__oxamount->rawValue);
			$oConfig = $this->getConfig();
			$aSoapParams = array(
				'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
				'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
				'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
				'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
			);
			$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
			$oWSApi->setDebugState(true,false);

			$sUserName = 'User';

			if ( isAdmin() ) {
				$sUserName = $this->getSession()->getUser()->oxuser__oxusername->value;
			}
			if ( strlen($sUserName) > 27 ) {
				$sUserName = substr($sUserName,0,27);
			}
			$sArtNum = $this->getArticle()->oxarticles__oxartnum->value;


			// Zuerst versuchen Bestellung zu ändern
			$aOrderData = array(
				'dealerOrderNumber' => $oContractData->dealerOrderNumber,
				'changeDate' => $sDate,
				'changedBy' => $sUserName,
				'dealerNumber' => $oContractData->dealerNumber,
				// Storno = Restwert auf Gesamtsumme-Preis des stornierten Artikels setzen
				'loanAmount' => (floatval($oOrder->oxorder__oxtotalordersum->value)-$dReturnAmount),
				'cpReferenceNumber' => $oContractData->cpReferenceNumber
			);

			$oResponse = $oWSApi->changeOrderCPWebshop($aOrderData);
			// Fehlerhandling
			if ( is_object($oResponse) && ( property_exists($oResponse,'confirmation') ) && ( property_exists($oResponse->confirmation,'confirmationItems') )  && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
				$iErrorCode = $oResponse->confirmation->confirmationItems->errorCode;
				$sErrorMessage = $oResponse->confirmation->confirmationItems->errorMessage;
				$aError = array(
					'sError' => (500+$iErrorCode),
					'sErrorMessage' => $sErrorMessage
				);
				if ( $iErrorCode == 1 ) {
					// Kein Auftrag gefunden
				} elseif ( $iErrorCode == 2 ) {
					// Pflichtfelder fehlen, kann durch Code heraus nicht passieren
				} elseif ( ($iErrorCode == 6) || ($iErrorCode == 11)  || ($iErrorCode == 18) || ($iErrorCode == 15) ) {
					// Wenn Status = "Bezahlt", "Ausgeliefert", "In Bezahlung" oder "Lieferfreigabe" - Retoure nutzen
					$oVoucher = new WebshopVoucher($dReturnAmount, $sArtNum, $sDate, $sUserName, false, $oContractData->id, $oContractData->dealerOrderNumber, $oContractData->dealerNumber);

					$oWSApi->addReturnProduct($oVoucher);
					$oResponse = $oWSApi->returnProductCPWebshop();
					if ( is_object($oResponse) && ( property_exists($oResponse,'confirmation') ) && ( property_exists($oResponse->confirmation,'confirmationItems') )  && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
						$iErrorCode = $oResponse->confirmation->confirmationItems->errorCode;
						$sErrorMessage = $oResponse->confirmation->confirmationItems->errorMessage;
						$aError = array(
							'sError' => (500+$iErrorCode),
							'sErrorMessage' => $sErrorMessage
						);
					} else {
						$aError = null;
					}
				} elseif ( $iErrorCode == 12 ) {
					// Summe der Retouren übersteigt Restwert des Kreditvertrags
				} elseif ( $iErrorCode == 14 ) {
					// Irgendein Pflichtfeld sprengt sein Format/Länge
					// dealerOrderNumber > 40
					// cancelationFrom > 27
				}  elseif ( $iErrorCode == 17 ) {
					// Auftrag ist bereits storniert.
				} else {
					// Keine Ahnung was passiert ist
				}
				return $aError;
			}
		}
		return null;
	}
}
/*
class_alias(\Sinkacom\CreditPlusModule\Model\OrderArticle::class,'sccp_oxorderarticle');

if ( false ) {
	class OrderArticle_parent extends \OxidEsales\Eshop\Application\Model\OrderArticle {

	}
}
*/
