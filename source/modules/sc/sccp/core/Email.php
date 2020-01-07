<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 11.04.16
 * Time: 18:01
 */

namespace Sinkacom\CreditPlusModule\Core;

class Email extends Email_parent {
	public function sendOrderEmailToUser($oOrder, $sSubject = null) {
		if ( $oOrder->oxorder__oxpaymenttype->value == 'sccp_financing' ) {
			$this->addRetryURL($oOrder);
		}
		return parent::sendOrderEmailToUser($oOrder, $sSubject);
	}
	public function sendOrderEmailToOwner($oOrder, $sSubject = null) {
		if ( $oOrder->oxorder__oxpaymenttype->value == 'sccp_financing' ) {
			$this->addRetryURL($oOrder);
		}
		return parent::sendOrderEmailToOwner($oOrder, $sSubject);
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order|\OxidEsales\Eshop\Application\Model\Order $oOrder
	 */
	protected function addRetryURL($oOrder) {
		$oConfig = $this->getConfig();
		if ( $oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'postorder' ) {
			$sDON = $oOrder->oxorder__oxtransid->value;
			$sOXID = $oOrder->oxorder__oxid->value;
			$oShop = $oConfig->getActiveShop();
			/** @var \OxidEsales\Eshop\Core\UtilsUrl $oUtilsURL */
			$oUtilsURL = oxNew(\OxidEsales\Eshop\Core\UtilsUrl::class);
			$sShownAs = $oConfig->getShopConfVar('sCPShownAs',$oShop->oxshops__oxid->value,'module:sccp');
			$sRetryURL = $oConfig->getConfigParam('sShopURL').$oUtilsURL->processUrl('index.php?cl=sinkacom_creditplusmodule_trigger&fnc=showSccpPopup&don='.$sDON.'&oxid='.$sOXID);
			if ( $sShownAs == 'popup' ) {
				// Since Popup == default, this is already set in the line before the if above
				// $sRetryURL = $oConfig->getConfigParam('sShopURL').$oUtilsURL->processUrl('index.php?cl=sccp_trigger&fnc=showSccpPopup&don='.$sDON.'&oxid='.$sOXID);
			} elseif ( $sShownAs == 'iframe' ) {
				$sRetryURL = $oConfig->getConfigParam('sShopURL').$oUtilsURL->processUrl('index.php?cl=sinkacom_creditplusmodule_trigger&fnc=showSccpIframe&don='.$sDON.'&oxid='.$sOXID);
			}
			
			$this->_aViewData['sRetryURL'] = $sRetryURL;
		}
	}
}

if ( false ) {
	class Email_parent extends \OxidEsales\Eshop\Core\Email {

	}
}
