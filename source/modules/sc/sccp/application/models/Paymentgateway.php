<?php
namespace Sinkacom\CreditPlusModule\Model;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\CreditPlusMainData;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\ShoppingCartItem;

$sModulDir = dirname(dirname(dirname(__FILE__)));
$sModulFileInit = $sModulDir."/lib/createCreditOffer.php";
require_once($sModulFileInit);

class Paymentgateway extends Paymentgateway_parent {
	/**
	 * Payment status (active - true/not active - false) (default false).
	 * @var bool
	 */
	//protected $_blActive       = true;

	protected $_sNextStepActions = 'cl=sinkacom_creditplusmodule_trigger&fnc=showSccpIframe';

	protected $_sLocalTestURLParams = 'cl=order&fnc=showSccpDemo&params=###DATA###&signature=###SIGNATURE###';

	/**
	 * Im Fall, dass die Bezahlung über sccp_financing läuft:
	 * Wenn noch nicht auf Zielseite mit Signatur und Parametern, Daten zusammensuchen und Client auf Webseite umleiten
	 * 
	 * Wenn die Zahlung fehlgeschlagen ist werden $this->_iLastErrorNo und 
	 * $this->_sLastError gesetzt und die Methode gibt false zurück.
	 *
	 * @param float $dAmount Bestellsumme
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder Bestellungsobjekt - muss gelöscht werden, wenn noch Verarbeitung stattfindet.
	 * @return bool Wenn true Bezahlt, sonst false. 
	 */
	public function executePayment( $dAmount, &$oOrder ) {
		$bReturn = parent::executePayment($dAmount, $oOrder);
		if ( $oOrder->oxorder__oxpaymenttype->value != 'sccp_financing' ) {
			return $bReturn;
		}
		/** @var \OxidEsales\Eshop\Core\Config $oConfig */
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		/** @var \OxidEsales\Eshop\Application\Model\Shop $oShop */
		$oShop = $oConfig->getActiveShop();
		$sTransactionMode = $oConfig->getShopConfVar('sTransactionMode', $oShop->oxshops__oxid->value, 'module:sccp');

		$sTransactionID = 'CP'.date('YmdHis');
		if ( $sTransactionMode == 'postorder' ) {
			$oOrder->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field($sTransactionID, \OxidEsales\Eshop\Core\Field::T_RAW);
			$oOrder->save();
			$oSession = $this->getSession();
			if ( $oSession->hasVariable('sTargetURL') ) {
				$oSession->deleteVariable('sTargetURL');
			}
			return true;
		} elseif ( ($sTransactionMode == 'inorder') && (!$_POST['params'] || !$_POST['signature']) ) {
			/** @var \OxidEsales\Eshop\Core\Session $oSession */
			$oSession = $this->getSession();
			/** @var \Sinkacom\CreditPlusModule\Model\Order $oSavedOrder */
			$oSavedOrder = $oSession->getVariable('oCreatedOrder');
			if ( $oSavedOrder ) {
				$oSavedOrder->clearContractDataCache();
			}
			// If saved order && saved order has contract data && dealerOrderNumber exists on saved order
			if ( $oSavedOrder && $oSavedOrder->getContractData() && $oSavedOrder->getContractData()->dealerOrderNumber ) {
				$oOrder->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field($oSavedOrder->oxorder__oxtransid->value, \OxidEsales\Eshop\Core\Field::T_RAW);
				$oOrder->save();
				if ( $oSession->hasVariable('sTargetURL') ) {
					$oSession->deleteVariable('sTargetURL');
				}
				$oSession->deleteVariable('oCreatedOrder');
				return true;
			} elseif ($oSavedOrder && $oSavedOrder->getContractData() && ($oSavedOrder->getContractData()->iError > 0) ) {
				// Magical Error Number...
				$oOrder->delete();
				$this->_iLastErrorNo = 13021;
				$this->_sLastError = 'Es ist ein technischer Fehler aufgetreten. Bitte Versuchen Sie es erneut oder wählen Sie eine andere Zahlungsweise.';
				$oSavedOrder->delete();
				$oSession->deleteVariable('oCreatedOrder');
				return false;
			} else {
				// Treat as incomplete
				$oOrder->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field($sTransactionID, \OxidEsales\Eshop\Core\Field::T_RAW);
				$oOrder->oxorder__oxtransstatus->setValue('PAYMENT_PENDING');
				$oOrder->sDeliveryAddressHash = $oRequest->getRequestParameter('sDeliveryAddressMD5');
				$sRedirectURL = $this->getTargetURL($oOrder, $dAmount);
				$oSession->setVariable('sTargetURL', $sRedirectURL);
				$oSession->setVariable('oCreatedOrder', $oOrder);
				$oOrder->delete();
				if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
					$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
				} else {
					$oUtils = \OxidEsales\Eshop\Core\Utils::getInstance();
				}
	
				$sNextStepURL = $oConfig->getShopSecureHomeUrl().$this->_sNextStepActions;
				$sShownAs = $oConfig->getShopConfVar('sCPShownAs', $oShop->oxshops__oxid->value, 'module:sccp');
				switch ( $sShownAs ) {
					case 'popup':
						$sNextStepURL = str_replace('showSccpIframe', 'showSccpPopup', $sNextStepURL);
						break;
					case 'iframe':
					default:
						// Use standard URL
				}
				$oUtils->redirect($sNextStepURL, false);
				$oUtils->showMessageAndExit('');
				return false;
			}
		} else {
			// Handle
			$oOrder->oxorder__oxtransid = new \OxidEsales\Eshop\Core\Field($sTransactionID, \OxidEsales\Eshop\Core\Field::T_RAW);
			$oOrder->save();
			$oSession = $this->getSession();
			if ( $oSession->hasVariable('sTargetURL') ) {
				$oSession->deleteVariable('sTargetURL');
			}
			return true; // Success
		}
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder The Order, that should be sent
	 * @param float $dAmount The Amount of money being sent
	 * @return string The target URL based on the data provided
	 */
	public function getTargetURL( $oOrder, $dAmount ) {

		if ( $oOrder->oxorder__oxpaymenttype->value != 'sccp_financing' ) {
			return '';
		}
		if ( $oOrder->sccp_oxorder_finance__sccp_order_link->value ) {
			// If order has been started once, use the cached URL
			return $oOrder->sccp_oxorder_finance__sccp_order_link->rawValue;
		}

		/** @var $oConfig \OxidEsales\Eshop\Core\Config */
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();

		$oLang = null;
		if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		} elseif ( method_exists('OxidEsales\\Eshop\\Core\\Language', 'getInstance')) {
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}

		$sTransactionID = $oOrder->oxorder__oxtransid->value?$oOrder->oxorder__oxtransid->value:'CP'.date('YmdHis');
		$aInput = array(
			"agentId" => "Webshop",
			"loanAmount" => $dAmount,
			"dealerOrderNumber" => $sTransactionID
		);
		$iDefaultFinancingTime = 6;

		$aCustomerAddress = array(
			//
			'salutation' => ($oOrder->oxorder__oxbillsal->value == 'MR')?1:2,
			//MR MRS
			'salutation_string' => $oLang->translateString($oOrder->oxorder__oxbillsal->value),
			'city' => $oOrder->oxorder__oxbillcity->value,
			'postalCode' => $oOrder->oxorder__oxbillzip->value,
			'street' => $oOrder->oxorder__oxbillstreet->value." ".$oOrder->oxorder__oxbillstreetnr->value,
			'Hausnummer' => $oOrder->oxorder__oxbillstreetnr->value,
			'firstName' => $oOrder->oxorder__oxbillfname->value,
			'Laufzeit' => $iDefaultFinancingTime,
			'lastName' => $oOrder->oxorder__oxbilllname->value,
			'email' => $oOrder->oxorder__oxbillemail->value
		);
		$aShippingAddress = array();
		if ( $oOrder->oxorder__oxdelfname->value || $oOrder->oxorder__oxdellname->value || $oOrder->oxorder__oxdelcity->value || $oOrder->oxorder__oxdelzip->value || $oOrder->oxorder__oxdelstreet->value ) {
			/** @var \OxidEsales\Eshop\Application\Model\Address $oAddress */
			$aShippingAddress['salutation'] = ($oOrder->oxorder__oxdelsal->value == 'MR')?1:2;
			$aShippingAddress['salutation_string'] = $oLang->translateString($oOrder->oxorder__oxdelsal->value);
			$aShippingAddress['city'] = $oOrder->oxorder__oxdelcity->value;
			$aShippingAddress['postalCode'] = $oOrder->oxorder__oxdelzip->value;
			$aShippingAddress['street'] = $oOrder->oxorder__oxdelstreet->value.' '.$oOrder->oxorder__oxdelstreetnr->value;
			$aShippingAddress['Hausnummer'] = $oOrder->oxorder__oxdelstreetnr->value;
			$aShippingAddress['firstName'] = $oOrder->oxorder__oxdelfname->value;
			$aShippingAddress['Laufzeit'] = $iDefaultFinancingTime;
			$aShippingAddress['lastName'] = $oOrder->oxorder__oxdellname->value;
			$aShippingAddress['email'] = $oOrder->oxorder__oxbillemail->value;
		} else {
			//$aCustomerAdress = array();
			$aShippingAddress = $aCustomerAddress;
		}
		$aData = array(
			'sDeliveryAddressMD5' => $oRequest->getRequestParameter('sDeliveryAddressMD5'),
			'sRequestTime' => date('YmdHis')
		);
		$aData = true;
		$sData = urlencode(base64_encode(gzdeflate(serialize($aData))));
		$sTriggerURL = $oConfig->getCurrentShopUrl().'?cl=sinkacom_creditplusmodule_trigger&data='.$sData.'&fnc=dispatch&don='.$sTransactionID.'&';
		$aCart = array();
		/** @var \OxidEsales\Eshop\Application\Model\OrderArticle[] $oOrderArticles */
		if ( $oOrderArticles = $oOrder->getOrderArticles() ) {
			foreach ( $oOrderArticles as $oOrderArticle ) {
				$oArticle = $oOrderArticle->getArticle();
				$oShoppingCartItem = new ShoppingCartItem(array(
					'amount' => intval(round(floatval($oOrderArticle->oxorderarticles__oxamount->value))),
					'description' => mb_substr($oOrderArticle->oxorderarticles__oxtitle->value,0,45),
					'ean' => $oArticle->oxarticles__oxean->value?substr($oArticle->oxarticles__oxean->value,0,13):'',
					'listPrice' => floatval($oArticle->oxarticles__oxtprice->value?$oArticle->oxarticles__oxtprice->value:$oOrderArticle->oxorderarticles__oxprice->value),
					'promotion' => false,
					'sellingPrice' => floatval($oOrderArticle->oxorderarticles__oxprice->value)
				));
				if ( $oShoppingCartItem->getSellingPrice() < $oShoppingCartItem->getListPrice() ) {
					$oShoppingCartItem->setPromotion(true);
				}
				$aCart[] = $oShoppingCartItem;
			}
		}

		$aShopIntegrationData = array(
			'triggerUrl' => $sTriggerURL,
			'shoppingCart' => $aCart
		);
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
		$oWSApi->setDebugState(true,false);
		$oCreditPlusMainData = new CreditPlusMainData();

		$oFinancingArticle = $oOrder->getFinancingArticleReference();

		$oCreditPlusMainData
			->setDealerNumber($oConfig->getShopConfVar('sCPDealer',null,'module:sccp'))
			->setDealerName($oConfig->getShopConfVar('sDealerName',null,'module:sccp'))
			->setSoapUser($oConfig->getShopConfVar('sSoapUser',null,'module:sccp'))
			->setSoapPass($oConfig->getShopConfVar('sSoapPass',null,'module:sccp'))
			->setSoapType($oConfig->getShopConfVar('sSoapType',null,'module:sccp'))
			->setPartnerName($oConfig->getShopConfVar('sPartnerName',null,'module:sccp'))
			->setProductTypeID(intval($oFinancingArticle->productTypeID))
			->setProductClassID(intval($oFinancingArticle->productClassID))
			->setWSDL($oConfig->getShopConfVar('sWSDL',null,'module:sccp'));
		$oCredOfResponse = $oWSApi->createCreditOfferCPWebshop($aInput, $oCreditPlusMainData, $aCustomerAddress, $aShippingAddress, $aShopIntegrationData);

		$sTransactionMode = $oConfig->getShopConfVar('sTransactionMode', null, 'module:sccp');
		if ( $sTransactionMode == 'inorder' ) {
			// Error = Return to Payment Page
			$sRedirectURL = $oConfig->getCurrentShopUrl().'?cl=payment&payerror=4';
		} else {
			/** @var \OxidEsales\Eshop\Application\Model\Content $oContent */
			$oContent = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
			if ( $oContent->loadByIdent('sccptechnicalerror') ) {
				// This error page is installed with the plugin
				$sRedirectURL = $oContent->getLink();
			} else {
				// This only happens, if the error page has been deleted after the install.
				$sRedirectURL = $oConfig->getCurrentShopUrl();
			}
		}
		// Customer URL given = Success, return iframe/popup url
		if ( isset($oCredOfResponse->createCreditOffer->customerUrl) ) {
			// This is the URL we want to see (the one leading to CreditPlus)
			$sRedirectURL = $oCredOfResponse->createCreditOffer->customerUrl;
		}
		return $sRedirectURL;
	}

}


class_alias(\Sinkacom\CreditPlusModule\Model\Paymentgateway::class,'sccp_oxpaymentgateway');

/** CE Documentation relevant class, for automatic code completion */
if ( false ) {
	class Paymentgateway_parent extends \OxidEsales\Eshop\Application\Model\PaymentGateway {

	}
}
