<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 15.02.16
 * Time: 15:42
 */

namespace Sinkacom\CreditPlusModule\Controller;

class Thankyou extends Thankyou_parent {
	public function render() {
		$sReturn = parent::render();
		$oConfig = $this->getConfig();
		$oShop = $oConfig->getActiveShop();
		// Add StyleSheet
		$aStyles = (array)$oConfig->getGlobalParameter('styles');
		$sShopRoot = $_SERVER['DOCUMENT_ROOT'];
		if ( substr($sShopRoot,-1) == '/' ) {
			$sShopRoot = substr($sShopRoot,0,-1);
		}
		$sCSSFile = dirname(dirname(dirname(__FILE__))).'/sccp.css';
		$aStyles[] = str_replace(realpath($sShopRoot),'',realpath($sCSSFile));
		$aStyles = array_unique($aStyles);
		$oConfig->setGlobalParameter('styles', $aStyles);
		$oOrder = $this->getOrder();
		if ( $oOrder->oxorder__oxpaymenttype->value == 'sccp_financing' ) {
			$sShownAs = $oConfig->getShopConfVar('sCPShownAs',$oShop->oxshops__oxid->value,'module:sccp');
			if ( $sShownAs == 'popup' ) {
				$this->_aViewData['sContinueToPaymentURL'] = $this->getViewConfig()->getSelfActionLink().'cl=sinkacom_creditplusmodule_trigger&fnc=showSccpPopup&don='.$oOrder->oxorder__oxtransid->value.'&oxid='.$oOrder->oxorder__oxid->value;
			} elseif ($sShownAs == 'iframe') {
				$this->_aViewData['sContinueToPaymentURL'] = $this->getViewConfig()->getSelfActionLink().'cl=sinkacom_creditplusmodule_trigger&fnc=showSccpIframe&don='.$oOrder->oxorder__oxtransid->value.'&oxid='.$oOrder->oxorder__oxid->value;
			}

			$this->_aViewData['sCancelAndSwapPaymentURL'] = $this->getViewConfig()->getSelfActionLink().'cl=sinkacom_creditplusmodule_restartorder&don='.$oOrder->oxorder__oxtransid->value.'&oxid='.$oOrder->oxorder__oxid->value;
		}
		$oSession = $this->getSession();
		/** @var \Sinkacom\CreditPlusModule\Model\Order $oSavedOrder */
		if ( $oSession->hasVariable('oCreatedOrder') ) {
			$oSession->deleteVariable('oCreatedOrder');
		}
		return $sReturn;
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Thankyou::class,'sccp_thankyou');

if ( false ) {
	class Thankyou_parent extends \OxidEsales\Eshop\Application\Controller\ThankYouController {

	}
}
