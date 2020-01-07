<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 26.02.16
 * Time: 09:56
 */

namespace Sinkacom\CreditPlusModule\Model;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopCurrency;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopFinanceArticle;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopRateTableMonthRow;

class Basket extends Basket_parent {
	protected $_aFinancingMonths = null;
	protected $_sFinancingMode = null;
	protected $_bIsBlockedForCheckout = false;

	/**
	 * Takes current basket and finds out which article provides the financing options.
	 * Returns empty array if no options exist or array of WebshopRateTableMonthRow if options exist.
	 * Most of the calculation is done in \Sinkacom\CreditPlusModule\Model\Article::getSccpFinancingMonths()
	 * @return array|WebshopRateTableMonthRow[]
	 * @see \Sinkacom\CreditPlusModule\Model\Article::getSccpFinancingMonths()
	 */
	public function getFinancingMonths() {
		$oConfig = $this->getConfig();
		if ( $this->isBlockedForCheckout() ) {
			return null;
		}
		$sBasketStrategy = $oConfig->getShopConfVar('sBasketFinancingMode', null, 'module:sccp');
		// If rules have not changed, return cached months
		if ( ($this->_sFinancingMode === $sBasketStrategy) && ($this->_aFinancingMonths !== null) ) {
			return $this->_aFinancingMonths;
		}
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSAPI = new CreditPlusWebshopAPI($aSoapParams);
		$aWebshopArticles = array();
		$aWebshopFinancingMonths = array();
		/** @var oxBasketItem[] $aBasketItems */
		$aBasketItems = $this->getContents();

		if ( $aBasketItems ) {
			$oCurrency = $this->getBasketCurrency();
			$oWSCurrency = WebshopCurrency::fromArray(array(
				'decimals' => $oCurrency->decimal,
				'decimalSeparator' => $oCurrency->dec,
				'side' => $oCurrency->side,
				'sign' => $oCurrency->sign,
				'thousandSeparator' => $oCurrency->thousandSeparator,
				'name' => $oCurrency->name,
			));
			/** @var \Sinkacom\CreditPlusModule\Model\Article[] $aBasketArticles */
			$aBasketArticles = $this->getBasketArticles();
			$dMinRate = floatval($oConfig->getShopConfVar('sMinRate', null, 'module:sccp'));
			$dBasketPrice = $this->getPrice()->getBruttoPrice();
			foreach ( $aBasketArticles as $sItemKey => $oBasketArticle ) {
				$aWebshopArticle = array(
					'id' => $sItemKey,
					'amount' => $aBasketItems[$sItemKey]->getAmount(),
					'unitprice' => $aBasketItems[$sItemKey]->getUnitPrice()->getBruttoPrice()
				);
				$aMonths = $oBasketArticle->getSccpFinancingMonths($dMinRate, $dBasketPrice);
				if ( !$aMonths ) {
					$this->_sFinancingMode = $sBasketStrategy;
					// One of the articles has no financing options, so this will not be offered for the basket
					// Return the empty array
					return $this->_aFinancingMonths = $aMonths;
				}
				foreach ( $aMonths as $oMonthRow ) {
					$dInterestRate = $oWSAPI->retrieveFloatFromFormattedInterestRate($oMonthRow->interestRate, $oWSCurrency);
					if ( !isset($aWebshopArticle['cheapestInterestRate']) || ($dInterestRate < $aWebshopArticle['cheapestInterestRate']) ) {
						$aWebshopArticle['cheapestInterestRate'] = $dInterestRate;
						$aWebshopArticle['productTypeID'] = $oMonthRow->productTypeID;
						$aWebshopArticle['productClassID'] = $oMonthRow->productClassID;
					}
					if ( !isset($aWebshopArticle['mostExpensiveInterestRate']) || ($dInterestRate > $aWebshopArticle['mostExpensiveInterestRate']) ) {
						$aWebshopArticle['mostExpensiveInterestRate'] = $dInterestRate;
					}
				}
				$oWebshopArticle = new WebshopFinanceArticle($aWebshopArticle);
				$aWebshopArticles[$sItemKey] = $oWebshopArticle;
			}
			$oWebshopArticle = $oWSAPI->getFinancingArticleReference($aWebshopArticles, $sBasketStrategy);
			$aWebshopFinancingMonths = $aBasketArticles[$oWebshopArticle->id]->getSccpFinancingMonths($dMinRate, $dBasketPrice);
			$this->_sFinancingMode = $sBasketStrategy;
		}
		return $this->_aFinancingMonths = $aWebshopFinancingMonths;
	}

	/**
	 * Calls parent::afterUpdate and resets financing months cache for basket
	 */
	public function afterUpdate() {
		parent::afterUpdate();
		// Reset cached financing months, if basket has been updated
		$this->_aFinancingMonths = null;
	}

	/**
	 * Blocks sccp_financing for Payment
	 * @param bool $bIsBlocked true if this should be blocked
	 */
	public function setIsBlockedForCheckout($bIsBlocked = true) {
		$this->_bIsBlockedForCheckout = $bIsBlocked;
	}

	/**
	 * @return bool true if it is blocked for checkout
	 */
	public function isBlockedForCheckout() {
		return $this->_bIsBlockedForCheckout;
	}
}

class_alias(\Sinkacom\CreditPlusModule\Model\Basket::class,'sccp_oxbasket');

if ( false ) {
	class Basket_parent extends \OxidEsales\Eshop\Application\Model\Basket {

	}
}
