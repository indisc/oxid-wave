<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 26.02.16
 * Time: 15:58
 */

namespace Sinkacom\CreditPlusModule\Controller;

class Basket extends Basket_parent {
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
		return $sReturn;
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Basket::class,'sccp_basket');
if ( false ) {
	class Basket_parent extends \OxidEsales\Eshop\Application\Controller\BasketController {

	}
}
