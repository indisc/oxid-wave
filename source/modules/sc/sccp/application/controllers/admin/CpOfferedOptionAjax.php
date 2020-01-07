<?php
/**
 * This file is part of the SinkaCom CreditPlus Module Package.
 *
 * @link      http://www.sinkacom.de/
 * @copyright (C) SinkaCom AG 2015-2016
 * @version   OXID eShop CE
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;

/**
 * Class controls payment option assignment to product groups
 */
// class sccp_cpoffered_option_ajax extends ajaxListComponent {
class CpOfferedOptionAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax {

	/**
	 * Columns array
	 *
	 * @var array
	 */
	protected $_aColumns = array(
		'container1' => array( // field , table, visible, multilanguage, ident
			array(
				'sccp_name',
				'sccp_prodgroup',
				1,
				0,
				0
			),
			array(
				'oxid',
				'sccp_prodgroup',
				0,
				0,
				0
			),
			array(
				'oxid',
				'sccp_prodgroup',
				0,
				0,
				1
			)
		),
		'container2' => array(
			array(
				'sccp_name',
				'sccp_prodgroup',
				1,
				0,
				0
			),
			array(
				'oxid',
				'sccp_prodgroup',
				0,
				0,
				0
			),
			array(
				'oxid',
				'sccp_offered_option_prodgroup',
				0,
				0,
				1
			),
			array(
				'oxid',
				'sccp_prodgroup',
				0,
				0,
				1
			)
		),
	);

	/**
	 * Returns SQL query for data to fetc
	 *
	 * @return string
	 */
	protected function _getQuery() {
		$sProdGroupTable = 'sccp_prodgroup';
		$sOO2PGView = 'sccp_offered_option_prodgroup';
		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

		$sOxid =  \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('oxid');
		$sSynchOxid =  \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('synchoxid');

		if ( $sOxid ) {
			// all categories article is in
			$sQAdd = " from $sOO2PGView left join $sProdGroupTable on $sProdGroupTable.oxid=$sOO2PGView.sccp_prodgroup_id ";
			$sQAdd .= " where $sOO2PGView.sccp_offered_option_id = ".$oDb->quote($sOxid)
				." and $sProdGroupTable.oxid is not null ";
		} else {
			$sQAdd = " from $sProdGroupTable where $sProdGroupTable.oxid not in ( ";
			$sQAdd .= " select $sProdGroupTable.oxid from $sOO2PGView "
				."left join $sProdGroupTable on $sProdGroupTable.oxid=$sOO2PGView.sccp_prodgroup_id";
			$sQAdd .= " where $sOO2PGView.sccp_offered_option_id = ".$oDb->quote($sSynchOxid)
				." and $sProdGroupTable.oxid is not null )";
		}

		return $sQAdd;
	}

	/**
	 * Removes article from chosen category
	 */
	public function removePG() {
		$aRemovePG = $this->_getActionIds('sccp_prodgroup.oxid');

		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
		$soxId = $oDb->quote( \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('oxid'));

		// adding
		if (  \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('all') ) {
			$sProdGroupTable = 'sccp_prodgroup';
			$aRemovePG = $this->_getAll($this->_addFilter("select {$sProdGroupTable}.oxid ".$this->_getQuery()));
		}

		// removing all
		if ( is_array($aRemovePG) && count($aRemovePG) ) {
			//TODO: Add version specific handling
			$sQ = "DELETE FROM sccp_offered_option_prodgroup WHERE sccp_offered_option_prodgroup.sccp_offered_option_id = "
				."$soxId AND "
				." sccp_offered_option_prodgroup.sccp_prodgroup_id IN (".implode(', ', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aRemovePG)).')';
			$oDb->Execute($sQ);
		}

		$this->resetContentCache();

	}

	/**
	 * Adds article to chosen category
	 */
	public function addPG() {
		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
		$aAddPG = $this->_getActionIds('sccp_prodgroup.oxid');
		$sSynchOxid =  \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('synchoxid');
		$sOO2PGView = 'sccp_offered_option_prodgroup';

		// adding
		if (  \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('all') ) {
			$sCategoriesTable = 'sccp_prodgroup';
			$aAddPG = $this->_getAll($this->_addFilter("select $sCategoriesTable.oxid ".$this->_getQuery()));
		}

		if ( isset($aAddPG) && is_array($aAddPG) ) {

			$oNew = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
			$oNew->init('sccp_offered_option_prodgroup');

			if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
				$oUtilsObject = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsObject::class);
			} else {
				$oUtilsObject = \OxidEsales\Eshop\Core\UtilsObject::getInstance();
			}

			foreach ( $aAddPG as $sAdd ) {
				// check, if it's already in, then don't add it again
				$sSelect = "SELECT 1 FROM ".$sOO2PGView." AS sccp_offered_option_prodgroup WHERE sccp_offered_option_prodgroup.sccp_prodgroup_id= "
					.$oDb->quote($sAdd)." AND sccp_offered_option_prodgroup.sccp_offered_option_id = ".$oDb->quote($sSynchOxid)." ";
				if ( $oDb->getOne($sSelect, false, false) ) {
					continue;
				}

				$oNew->setId($oUtilsObject->generateUId());
				$oNew->sccp_offered_option_prodgroup__sccp_offered_option_id = new \OxidEsales\Eshop\Core\Field($sSynchOxid);
				$oNew->sccp_offered_option_prodgroup__sccp_prodgroup_id = new \OxidEsales\Eshop\Core\Field($sAdd);

				$oNew->save();
			}
			$this->resetContentCache();
		}
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpOfferedOptionAjax::class,'sccp_cpoffered_option_ajax');
