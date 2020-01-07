<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 24.02.16
 * Time: 16:26
 */

namespace Sinkacom\CreditPlusModule\Model;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopRateTableMonthRow;

class Article extends Article_parent {

	/** @var WebshopRateTableMonthRow[] $_aAvailableFinancingOptions */
	protected $_aAvailableFinancingOptions = null;

	/**
	 * @param float $dMinRate If there is a min rate, the given rates should be above this
	 * @param float $dFinancePrice Financed Price, defaults to article price, but may be used to finance a basket with this items conditions
	 * @return WebshopRateTableMonthRow[] The month rows for this product
	 */
	public function getSccpFinancingMonths($dMinRate = null, $dFinancePrice = null) {
		// If article financing options are requested, return them cached. If basket price given, do not return cache and do not save the options
		if ( $this->_aAvailableFinancingOptions !== null && ($dFinancePrice == null) ) {
			return $this->_aAvailableFinancingOptions;
		}
		/** @var WebshopRateTableMonthRow[] $aAvailableOptions */
		$aAvailableOptions = array();
		$oConfig = $this->getConfig();
		$oCurrency = $oConfig->getActShopCurrencyObject();
		/** @var \OxidEsales\Eshop\Core\Model\ListModel $oList */
		$oList = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
		$oList->init(\Sinkacom\CreditPlusModule\Model\OfferedOption::class);
		// Rate calculation from WSApi and Product Price
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
		$dProdPrice = $dFinancePrice?$dFinancePrice:$this->getPrice()->getBruttoPrice();
		/** @var \OxidEsales\Eshop\Application\Model\Payment $oPayment */
		$oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
		$oPayment->load('sccp_financing');
		$dMinFinancePrice = floatval($oPayment->oxpayments__oxfromamount->rawValue);
		$dMaxFinancePrice = floatval($oPayment->oxpayments__oxtoamount->rawValue);
		$dMinRate = ($dMinRate !== null)?$dMinRate:floatval($oConfig->getShopConfVar('sMinRate',null,'module:sccp'));

		// Product or basket cheaper than min price => return array()
		if ( $dMinFinancePrice > $dProdPrice ) {
			return array();
		}
		// Product or basket more expensive than max price => return array()
		if ( $dMaxFinancePrice < $dProdPrice ) {
			return array();
		}

		// First thing: Get product specific assigned options
		$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
		$sParentID = $oDB->quote($this->oxarticles__oxparentid->value?$this->oxarticles__oxparentid->value:$this->oxarticles__oxid->value);
		$sSelectString = "SELECT oo.oxid oxid, oo.sccp_months sccp_months, oo.sccp_interest sccp_interest, oo.sccp_prodcode sccp_prodcode, oo.sccp_active sccp_active, oo.sccp_ratefactor sccp_ratefactor, pg.sccp_producttypeid sccp_producttypeid, pg.sccp_productclassid sccp_productclassid, pg.sccp_name sccp_productgroup_name
				FROM sccp_offered_option oo
				INNER JOIN sccp_offered_option_prodgroup oopg ON oo.oxid = oopg.sccp_offered_option_id
				INNER JOIN sccp_prodgroup pg ON oopg.sccp_prodgroup_id = pg.oxid
				INNER JOIN sccp_prodgroup_article pga ON pg.oxid = pga.sccp_prodgroup_id
				WHERE (oo.sccp_active = 1) AND pga.oxartid = $sParentID
				ORDER BY sccp_months ASC, sccp_interest DESC
				";
		if ( $oConfig->getConfigParam('blVariantsSelection') === false ) {
			$sArticleID = $oDB->quote($this->oxarticles__oxid->value);
			$sSelectString = "SELECT oo.oxid oxid, oo.sccp_months sccp_months, oo.sccp_interest sccp_interest, oo.sccp_prodcode sccp_prodcode, oo.sccp_active sccp_active, oo.sccp_ratefactor sccp_ratefactor, pg.sccp_producttypeid sccp_producttypeid, pg.sccp_productclassid sccp_productclassid, pg.sccp_name sccp_productgroup_name
								FROM sccp_offered_option oo
								INNER JOIN sccp_offered_option_prodgroup oopg ON oo.oxid = oopg.sccp_offered_option_id
								INNER JOIN sccp_prodgroup pg ON oopg.sccp_prodgroup_id = pg.oxid
								INNER JOIN sccp_prodgroup_article pga ON pg.oxid = pga.sccp_prodgroup_id
								WHERE (oo.sccp_active = 1) AND ( (pga.oxartid = $sArticleID) OR (pga.oxartid = $sParentID) )
								ORDER BY sccp_months ASC, sccp_interest DESC";
		}

		$oList->selectString($sSelectString);
		/** @var \Sinkacom\CreditPlusModule\Model\OfferedOption $oOption */
		foreach ( $oList as $oOption ) {
			$aOption = array(
				'months' => intval($oOption->sccp_offered_option__sccp_months->value),
				'interestRate' => floatval($oOption->sccp_offered_option__sccp_interest->value),
				'nominalInterestRate' => $oWSApi->getNominalInterestFromEffectiveInterest(floatval($oOption->sccp_offered_option__sccp_interest->value)),
				'productCode' => $oOption->sccp_offered_option__sccp_prodcode->value,
				'productTypeID' => $oOption->sccp_offered_option__sccp_producttypeid->value,
				'productClassID' => $oOption->sccp_offered_option__sccp_productclassid->value,
				'productGroupName' => $oOption->sccp_offered_option__sccp_productgroup_name->value
			);
			$dRateFactor = floatval($oOption->sccp_offered_option__sccp_ratefactor->value);
			if ( $dRateFactor === -1.0 ) {
				$dRateFactor = false;
			}
			$aOption['monthlyRate'] = $oWSApi->getMonthRateByPriceMonthsAndInterest($dProdPrice,$aOption['months'],$aOption['interestRate'],$dRateFactor);
			if ( $aOption['monthlyRate'] < $dMinRate ) {
				// Skip rates, which are too low
				continue;
			}
			$aAvailableOptions[$aOption['months']] = new WebshopRateTableMonthRow($aOption);
		}


		// Second thing: Add missing months from general options and replace more expensive rates from general options as well
		$sSelectString = "SELECT oo.oxid oxid, oo.sccp_months sccp_months, oo.sccp_interest sccp_interest, oo.sccp_prodcode sccp_prodcode, oo.sccp_active sccp_active, oo.sccp_ratefactor sccp_ratefactor, pg.sccp_producttypeid sccp_producttypeid, pg.sccp_productclassid sccp_productclassid, pg.sccp_name sccp_productgroup_name
				FROM sccp_offered_option oo
				INNER JOIN sccp_offered_option_prodgroup oopg ON oo.oxid = oopg.sccp_offered_option_id
				INNER JOIN sccp_prodgroup pg ON oopg.sccp_prodgroup_id = pg.oxid
				LEFT JOIN sccp_prodgroup_article pga ON pg.oxid = pga.sccp_prodgroup_id
				WHERE (oo.sccp_active = 1) AND pga.oxartid IS NULL
				ORDER BY sccp_months ASC, sccp_interest ASC
				";
		$oList->selectString($sSelectString);
		/** @var \Sinkacom\CreditPlusModule\Model\OfferedOption $oOption */
		foreach ( $oList as $oOption ) {
			$aOption = array(
				'months' => intval($oOption->sccp_offered_option__sccp_months->value),
				'interestRate' => floatval($oOption->sccp_offered_option__sccp_interest->value),
				'nominalInterestRate' => $oWSApi->getNominalInterestFromEffectiveInterest(floatval($oOption->sccp_offered_option__sccp_interest->value)),
				'productCode' => $oOption->sccp_offered_option__sccp_prodcode->value,
				'productTypeID' => $oOption->sccp_offered_option__sccp_producttypeid->value,
				'productClassID' => $oOption->sccp_offered_option__sccp_productclassid->value,
				'productGroupName' => $oOption->sccp_offered_option__sccp_productgroup_name->value
			);
			$dRateFactor = floatval($oOption->sccp_offered_option__sccp_ratefactor->value);
			if ( $dRateFactor === -1.0 ) {
				$dRateFactor = false;
			}
			$aOption['monthlyRate'] = $oWSApi->getMonthRateByPriceMonthsAndInterest($dProdPrice,$aOption['months'],$aOption['interestRate'],$dRateFactor);
			if ( $aOption['monthlyRate'] < $dMinRate ) {
				// Skip rates, which are too low
				continue;
			}
			if ( !isset($aAvailableOptions[$aOption['months']]) ) {
				// If not yet set, just add
				$aAvailableOptions[$aOption['months']] = new WebshopRateTableMonthRow($aOption);
			} elseif ( $aAvailableOptions[$aOption['months']]->interestRate > $aOption['interestRate'] ) {
				// If general is cheaper, replace
				$aAvailableOptions[$aOption['months']] = new WebshopRateTableMonthRow($aOption);
			} else {
				// Skip
			}
		}

		// Prepare values for output (Currency sign, number format)
		foreach ( $aAvailableOptions as &$oWSOption ) {
			$oWSOption->totalAmount = $oWSOption->monthlyRate*$oWSOption->months;
			// If interest is 0.00 or above
			if ( $oWSOption->interestRate >= 0.00 ) {
				// If total price is lower than base price
				// or if price is higher than base price with 0.00 interest,
				// something must be off on the single payments
				// which will be corrected on the final payment anyway
				if ( ($oWSOption->totalAmount < $dProdPrice) || (($oWSOption->totalAmount > $dProdPrice) && ($oWSOption->interestRate == 0.00)) ) {
					$oWSOption->totalAmount = $dProdPrice;
				}
			}
			$oWSOption->interest = $oWSOption->totalAmount-$dProdPrice;
			$oWSOption->interestRate = number_format($oWSOption->interestRate, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand).'%';
			$oWSOption->nominalInterestRate = number_format($oWSOption->nominalInterestRate, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand).'%';
			if ( $oCurrency->side && ($oCurrency->side == 'left') ) {
				$oWSOption->interest = $oCurrency->sign.' '.number_format($oWSOption->interest, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand);
				$oWSOption->totalAmount = $oCurrency->sign.' '.number_format($oWSOption->totalAmount, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand);
				$oWSOption->monthlyRate = $oCurrency->sign.' '.number_format($oWSOption->monthlyRate, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand);
			} else {
				$oWSOption->interest = number_format($oWSOption->interest, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand).' '.$oCurrency->sign;
				$oWSOption->totalAmount = number_format($oWSOption->totalAmount, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand).' '.$oCurrency->sign;
				$oWSOption->monthlyRate = number_format($oWSOption->monthlyRate, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand).' '.$oCurrency->sign;
			}
		}
		// Sorty by months ascending
		ksort($aAvailableOptions);
		if ( $dFinancePrice == null ) {
			$this->_aAvailableFinancingOptions = $aAvailableOptions;
		}
		return $aAvailableOptions;
	}
}
/*
class_alias(\Sinkacom\CreditPlusModule\Model\Article::class,'sccp_oxarticle');

if ( false ) {
	class Article_parent extends \OxidEsales\Eshop\Application\Model\Article {

	}
}
*/
