<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 08.04.16
 * Time: 11:41
 */

namespace Sinkacom\CreditPlusModule\Controller;

class AccountOrder extends AccountOrder_parent {

	public function render() {
		$sReturn = parent::render();
		$oConfig = $this->getConfig();
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
		
		/** @var \OxidEsales\Eshop\Core\Theme $oTheme */
		$oTheme = oxNew(\OxidEsales\Eshop\Core\Theme::class);
		$oTheme->load($oTheme->getActiveThemeId());
		$bFlowTheme = false;
		if ( $oTheme->getId() == 'flow' ) {
			$bFlowTheme = true;
		} else {
			if ( $oParentTheme = $oTheme->getParent() ) {
				$bFlowTheme = ($oParentTheme->getId() == 'flow');
			}
		}
		$this->_aViewData['bFlowTheme'] = $bFlowTheme;
		return $sReturn;
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder
	 * @return string
	 */
	public function getSccpPayNowURL( $oOrder ) {
		$sReturn = '';
		if ( $oOrder ) {
			/** @var \Sinkacom\CreditPlusModule\Controller\RestartOrder $oRestartOrder */
			$oRestartOrder = oxNew(\Sinkacom\CreditPlusModule\Controller\RestartOrder::class);
			if ( !$oRestartOrder->isFinished($oOrder) ) {
				if ( $oOrder->oxorder__oxpaymenttype->value == 'sccp_financing' ) {
					$oConfig = $this->getConfig();
					$sShownAs = $oConfig->getShopConfVar('sCPShownAs', null, 'module:sccp');
					if ( $sShownAs == 'popup' ) {
						$sReturn = $this->getViewConfig()->getSelfActionLink().'cl=sinkacom_creditplusmodule_trigger&fnc=showSccpPopup&don='.$oOrder->oxorder__oxtransid->value.'&oxid='.$oOrder->oxorder__oxid->value;
					} elseif ($sShownAs == 'iframe') {
						$sReturn = $this->getViewConfig()->getSelfActionLink().'cl=sinkacom_creditplusmodule_trigger&fnc=showSccpIframe&don='.$oOrder->oxorder__oxtransid->value.'&oxid='.$oOrder->oxorder__oxid->value;
					}
				}

			}
		}
		return $sReturn;
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder
	 * @return string
	 */
	public function getSccpCancelURL( $oOrder ) {
		$sReturn = '';
		if ( $oOrder ) {
			/** @var \Sinkacom\CreditPlusModule\Controller\RestartOrder $oRestartOrder */
			$oRestartOrder = oxNew(\Sinkacom\CreditPlusModule\Controller\RestartOrder::class);
			if ( $oRestartOrder->isInStateForRerun($oOrder) ) {
				if ( $oOrder->oxorder__oxpaymenttype->value == 'sccp_financing' ) {
					$sReturn = $this->getViewConfig()->getSelfActionLink().'cl=sinkacom_creditplusmodule_restartorder&don='.$oOrder->oxorder__oxtransid->value.'&oxid='.$oOrder->oxorder__oxid->value;
				}
			}
		}
		return $sReturn;
	}

}

//class_alias(\Sinkacom\CreditPlusModule\Controller\AccountOrder::class,'sccp_account_order');

// Code completion
if ( false ) {
	class AccountOrder_parent extends \OxidEsales\Eshop\Application\Controller\AccountOrderController {

	}
}
