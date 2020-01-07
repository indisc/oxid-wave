<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 16.03.16
 * Time: 10:06
 */

namespace Sinkacom\CreditPlusModule\Model;

class Paymentlist extends Paymentlist_parent {

	protected $_bExcludedPayment = false;

	/**
	 * Does what the parent does and takes away the financing payment if unavailable
	 * @param string $sShipSetId
	 * @param float $dPrice
	 * @param null|oxUser $oUser
	 * @return oxPayment[]
	 */
	public function getPaymentList( $sShipSetId, $dPrice, $oUser = null ) {
		/** @var oxPayment[] $aList */
		$aList = parent::getPaymentList($sShipSetId, $dPrice, $oUser);
		if ( $this->isAdmin() ) { return $aList; }
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sPayError = $oRequest->getRequestParameter('payerror');
		if ( ($this->_bExcludedPayment === false) || ($sPayError) ) {
			foreach ( $aList as $sKey => $oPayment ) {
				$bGoodForBasket = $this->sccpCheckBasketAvailability($oPayment->oxpayments__oxid->value, $sPayError);
				if ( $bGoodForBasket === false ) {
					unset($aList[$sKey]);
				}
			}
			$this->_bExcludedPayment = true;
		}
		return $aList;
	}

	/**
	 * Checks whether this payment ID needs to be removed from the available options
	 * Returns false for sccp_financing if no months are returned on \Sinkacom\CreditPlusModule\Model\Basket::getFinancingMonths()
	 * Returns true for all payments not filtered by this
	 * @param string $sPaymentID Payment ID
	 * @param string $sPayError Payment Error
	 * @return bool See description.
	 * @see \Sinkacom\CreditPlusModule\Model\Basket::getFinancingMonths()
	 */
	protected function sccpCheckBasketAvailability($sPaymentID, $sPayError = '') {
		if ( $sPaymentID == 'sccp_financing' ) {
			/** @var \Sinkacom\CreditPlusModule\Model\Basket $oBasket */
			$oBasket = $this->getSession()->getBasket();
			$aMonths = $oBasket->getFinancingMonths();
			if ( !$aMonths ) {
				return false;
			}
			/** @var \Sinkacom\CreditPlusModule\Model\Basket $oBasket */
			$oBasket = $this->getSession()->getBasket();
			if ( $sPayError && ( $sPayError.'' == '13021' ) ) {
				if ( $oBasket ) { $oBasket->setIsBlockedForCheckout(true); }
				return false;
			}
			if ( $oBasket ) {
				if ( $oBasket->isBlockedForCheckout() ) {
					return false;
				}
			}
		}
		return true;
	}
}

class_alias(\Sinkacom\CreditPlusModule\Model\Paymentlist::class,'sccp_oxpaymentlist');

if ( false ) {
	class Paymentlist_parent extends \OxidEsales\Eshop\Application\Model\PaymentList {

	}
}
