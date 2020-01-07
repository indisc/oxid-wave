<?php
/**
 * Created by PhpStorm.
 * @author mihovil.bubnjar
 * @date 26.07.2016
 * @time 10:19
 */

namespace Sinkacom\CreditPlusModule\Component;
class BasketComponent extends BasketComponent_parent {
	protected function _addItems($aProducts) {
		/** @var \Sinkacom\CreditPlusModule\Model\Basket $oBasket */
		$oBasket = $this->getSession()->getBasket();
		$oBasket->setIsBlockedForCheckout(false);
		return parent::_addItems($aProducts);
	}
}

class_alias(\Sinkacom\CreditPlusModule\Component\BasketComponent::class,'sccp_oxcmp_basket');

if ( false ) {
	class BasketComponent_parent extends \OxidEsales\Eshop\Application\Component\BasketComponent {
		
	}
}
