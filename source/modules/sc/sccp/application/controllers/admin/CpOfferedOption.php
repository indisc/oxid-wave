<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 23.02.16
 * Time: 12:56
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;

/**
 * Admin Product Group manager.
 * Displays different product groups and gives a possiblity to assign articles to them. One of them is marked as default and has no article assignments
 * Admin Menu: Orders -> CreditPlus Product Groups.
 */
// class sccp_cpoffered_option extends oxAdminDetails
class CpOfferedOption extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{

	protected $_sThisTemplate = 'sccp_cpoffered_option.tpl';

	/** @var oxLang $_oLangObject */
	protected $_oLangObject = null;

	/**
	 * @return string
	 */
	public function render()
	{
		$sReturn = parent::render();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$iAoc = $oRequest->getRequestParameter("aoc");
		if ($iAoc == 1) {
			/** @var \Sinkacom\CreditPlusModule\Controller\Admin\CpOfferedOptionAjax $oOfferedOptionAjax */
			$oOfferedOptionAjax = oxNew(\Sinkacom\CreditPlusModule\Controller\Admin\CpOfferedOptionAjax::class);
			$this->_aViewData['oxajax'] = $oOfferedOptionAjax->getColumns();

			return "popups/sccp_cpoffered_option.tpl";
		}
		/** @var \OxidEsales\Eshop\Core\Model\ListModel $oList */
		$oList = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
		$oList->init(\Sinkacom\CreditPlusModule\Model\OfferedOption::class);

		$oList->selectString('SELECT * FROM sccp_offered_option ORDER BY sccp_prodcode ASC, sccp_months ASC');

		/** @var \OxidEsales\Eshop\Core\UtilsObject $oUtilsObject */
		$oUtilsObject = null;
		if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$oUtilsObject = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsObject::class);
		} elseif ( !class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$oUtilsObject = \OxidEsales\Eshop\Core\UtilsObject::getInstance();
		}
		$sOxidBase = substr($oUtilsObject->generateUId(),0,3);

		$this->_aViewData['sOxidBase'] = $sOxidBase;
		$this->_aViewData['oList'] = $oList;

		return $sReturn;
	}

	public function save() {
		parent::save();
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$aPostedParams = $oRequest->getRequestParameter('editlist');
		$this->_aViewData['aAdditionalMessages'] = array();
		foreach ( $aPostedParams as $sOXID => $aValues ) {
			/** @var \Sinkacom\CreditPlusModule\Model\OfferedOption $oOfferedOption */
			$oOfferedOption = oxNew(\Sinkacom\CreditPlusModule\Model\OfferedOption::class);
			$oOfferedOption->load($sOXID);
			if ( intval($aValues['delete']) === 1 ) {
				// Delete relation to other items, then item itself
				$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
				$sDeleteID = $oDB->quote($oOfferedOption->getId());
				$oDB->execute("DELETE FROM sccp_offered_option_prodgroup WHERE sccp_offered_option_id = $sDeleteID");
				$oOfferedOption->delete();
			} else {
				$oOfferedOption->setId($sOXID);
				if ( !isset($aValues['sccp_ratefactor']) || ( $aValues['sccp_ratefactor'] == '' ) ) {
					$aValues['sccp_ratefactor'] = -1.00;
				} else {
					// If comma is present, it is probably entered the german way
					if ( strpos($aValues['sccp_ratefactor'],',') !== false ) {
						// Assume easy german number format replacement
						$aValues['sccp_ratefactor'] = str_replace(',','.',$aValues['sccp_ratefactor']);
					}
					$aValues['sccp_ratefactor'] = floatval($aValues['sccp_ratefactor']);
				}
				if ( strpos($aValues['sccp_interest'],',') !== false ) {
					// Assume easy german number format replacement
					$aValues['sccp_interest'] = str_replace(',','.',$aValues['sccp_interest']);
				}
				$this->checkMinMonths($aValues);
				$this->checkInterest($aValues);
				$this->checkRateFactor($aValues);

				$oOfferedOption->assign($aValues);
				$oOfferedOption->save();
			}
		}
		$this->_aViewData['sError'] = 200;
		$this->_aViewData['sErrorMessage'] = 'SCCP_FINANCING_OFFERED_OPTIONS_SAVED';
	}

	/**
	 * @return oxLang
	 */
	protected function getLangObject() {
		if ( $this->_oLangObject !== null ) {
			return $this->_oLangObject;
		}
		if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$this->_oLangObject = \OxidEsales\Eshop\Core\Registry::getLang();
		} else {
			$this->_oLangObject = \OxidEsales\Eshop\Core\Language::getInstance();
		}
		return $this->_oLangObject;
	}

	protected function checkMinMonths( &$aValues ) {
		if ( intval($aValues['sccp_months']) < 6 ) {
			$sMessage = $this->getLangObject()->translateString('SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MINMONTHS');
			$sMessage = $this->fillMarkers($sMessage, $aValues);
			$this->_aViewData['aAdditionalMessages'][] = $sMessage;
			$aValues['sccp_months'] = 6;
		}
		return true;
	}

	protected function checkInterest(&$aValues) {
		$dInterest = floatval($aValues['sccp_interest']);
		if ( $dInterest < 0.00 ) {
			$sMessage = $this->getLangObject()->translateString('SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MININTEREST');
			$sMessage = $this->fillMarkers($sMessage, $aValues);
			$this->_aViewData['aAdditionalMessages'][] = $sMessage;
			$aValues['sccp_interest'] = 0.00;
		}
		if ( $dInterest > 17.99 ) {
			$sMessage = $this->getLangObject()->translateString('SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MAXINTEREST');
			$sMessage = $this->fillMarkers($sMessage, $aValues);
			$this->_aViewData['aAdditionalMessages'][] = $sMessage;
			$aValues['sccp_interest'] = 17.99;
		}
		return true;
	}

	protected function checkRateFactor( &$aValues ) {
		$dRateFactor = floatval($aValues['sccp_ratefactor']);
		if ( ($dRateFactor <= 0.0) && ($dRateFactor !== -1.00) ) {
			$sMessage = $this->getLangObject()->translateString('SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MINRATEFACTOR');
			$sMessage = $this->fillMarkers($sMessage, $aValues);
			$this->_aViewData['aAdditionalMessages'][] = $sMessage;
			$aValues['sccp_ratefactor'] = -1.00;
		}
		if ( ($dRateFactor >= 1.0) && ($dRateFactor !== -1.00) ) {
			$sMessage = $this->getLangObject()->translateString('SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MAXRATEFACTOR');
			$sMessage = $this->fillMarkers($sMessage, $aValues);
			$this->_aViewData['aAdditionalMessages'][] = $sMessage;
			$aValues['sccp_ratefactor'] = -1.00;
		}
		return true;
	}

	protected function fillMarkers( $sMessage, $aValues ) {
		return str_replace(
			array(
				'###PRODCODE###',
				'###MONTHS###',
				'###INTEREST###'
			),
			array(
				$aValues['sccp_prodcode'],
				$aValues['sccp_months'],
				$aValues['sccp_interest'],
			),
			$sMessage
		);
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpOfferedOption::class,'sccp_cpoffered_option');
