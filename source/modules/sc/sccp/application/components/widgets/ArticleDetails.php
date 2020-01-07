<?php

namespace Sinkacom\CreditPlusModule\Component\Widget;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopCurrency;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopRateTableMonthRow;

class ArticleDetails extends ArticleDetails_parent {
	protected $_sCheapestRate = null;
	protected $_aFinancingTable = null;

	/**
	 * This function creates the financing options based on the products price
	 * and shows different monthly rates at the selected financing terms.
	 *
	 * The values are set by the backend (Extensions -> Modules -> Options)
	 * and the minimum value for the payment option with the id sccp_financing.
	 *
	 * If no valid months exist, the aMonthRows array will be empty.
	 *
	 * @return array('sFinancingDescription' => string, 'aMonthRows' => array(array('months' => float, 'interest' => string, 'totalAmount' => string, 'monthlyRate' => string)) The financing table description an rows.
	 */
	public function getFinancingTable() {
		if ( $this->_aFinancingTable !== null ) {
			return $this->_aFinancingTable;
		}
		$oConfig = $this->getConfig();
		$oShop = $oConfig->getActiveShop();
		// if OXID > 4.9 ... , else ...
		if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		} else {
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}
		/** @var \Sinkacom\CreditPlusModule\Model\Article $oProduct */
		$oProduct = $this->getProduct();
		$oCurrency = $oConfig->getActShopCurrencyObject();
		$sMinRate = $oConfig->getShopConfVar('sMinRate', $oShop->getShopId(), 'module:sccp');

		// Calculate Financing Table
		$dMinRate = floatval(str_replace(',', '.', $sMinRate));

		// $aMonthRows = $oCPAPI->getFinancingOptions($oDB,'713000',$fProdPrice,$fEffZins,$fMinRate,$aMonths,$oWebshopCurrency);
		$aMonthRows = $oProduct->getSccpFinancingMonths($dMinRate);
		if ( !$aMonthRows ) {
			$this->_aFinancingTable = array('aMonthRows' => array());
			return $this->_aFinancingTable;
		}

		$dCheapestInterestRate = null;
		foreach ( $aMonthRows as $oMonthRow ) {

			$dEffInterest = floatval(str_replace(array(
					',',
					'%'
			), array(
					'.',
					''
			), $oMonthRow->interestRate));
			if ( ($dCheapestInterestRate === null) || ($dEffInterest < $dCheapestInterestRate) ) {
				$dCheapestInterestRate = $dEffInterest;
			}
		}
		/** @var \OxidEsales\Eshop\Application\Model\Payment oxPayment */
		$oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
		$oPayment->load('sccp_financing');
		$aMarker = array(
			'###MINDESTBESTELLWERT###',
			'###JAHRESZINS###'
		);
		$sMinimalOrderSum = number_format($oPayment->oxpayments__oxfromamount->value, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand);
		$aReplacements = array(
			($oCurrency->side == 'left')?$oCurrency->sign.' '.$sMinimalOrderSum:$sMinimalOrderSum.' '.$oCurrency->sign,
			number_format($dCheapestInterestRate, $oCurrency->decimal, $oCurrency->dec, $oCurrency->thousand)
		);
		$sFinancingDescription = str_replace($aMarker, $aReplacements, $oLang->translateString('SCCP_FINANZIERUNG_DESCRIPTION'));
		$this->_aFinancingTable = array();
		$this->_aFinancingTable['sFinancingDescription'] = $sFinancingDescription;
		$this->_aFinancingTable['aMonthRows'] = $aMonthRows;

		return $this->_aFinancingTable;
	}


	/**
	 * This function gives the lowest financing rate as a string, e.g. "Finance this for $ 14.33 per month over 48 months"
	 * @return string The text with the lowest rate
	 */
	public function getLowestFinancingRate() {
		if ( $this->_sCheapestRate !== null ) {
			return $this->_sCheapestRate;
		}
		if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		} else {
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}
		$aFinancingTable = $this->getFinancingTable();
		if ( !$aFinancingTable || !$aFinancingTable['aMonthRows'] ) {
			return $this->_sCheapestRate = '';
		}
		$aReturn = array();
		$oConfig = $this->getConfig();
		$oCurrency = $oConfig->getActShopCurrencyObject();
		$oWebshopCurrency = WebshopCurrency::fromArray(array(
			'decimals' => $oCurrency->decimal,
			'decimalSeparator' => $oCurrency->dec,
			'name' => $oCurrency->name,
			'side' => $oCurrency->side,
			'sign' => $oCurrency->sign,
			'thousandSeparator' => $oCurrency->thousand
		));
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSAPI = new CreditPlusWebshopAPI($aSoapParams);
		$this->_sCheapestRate = '';
		$dCheapestRate = null;
		if ( $aFinancingTable['aMonthRows'] ) {
			/** @var WebshopRateTableMonthRow $oMonthRow */
			foreach ( $aFinancingTable['aMonthRows'] as $oMonthRow ) {
				$dPrice = $oWSAPI->retrieveFloatPriceFromFormattedPrice($oMonthRow->monthlyRate,$oWebshopCurrency);
				if ( ($dCheapestRate == null) || ($dPrice < $dCheapestRate) ) {
					$aReturn['months'] = $oMonthRow->months;
					$aReturn['cost'] = $oMonthRow->monthlyRate;
					$dCheapestRate = $dPrice;
				}
			}
			$aMarker = array(
				'###PRICE###',
				'###MONTHS###'
			);
			$aReplacements = array(
				$aReturn['cost'],
				$aReturn['months']
			);
			$this->_sCheapestRate = str_replace($aMarker, $aReplacements, $oLang->translateString('SCCP_FINANZIERUNG_MINIINFO'));
		}
		return $this->_sCheapestRate;
	}

}

class_alias(\Sinkacom\CreditPlusModule\Component\Widget\ArticleDetails::class,'sccp_oxwarticledetails');

/** CE Documentation relevant class, for automatic code completion */
if ( false ) {
	class ArticleDetails_parent extends \OxidEsales\Eshop\Application\Component\Widget\ArticleDetails {

	}
}
