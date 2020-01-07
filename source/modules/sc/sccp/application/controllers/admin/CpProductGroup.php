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
//class sccp_cpproduct_group extends oxAdminDetails
class CpProductGroup extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{

	protected $_sThisTemplate = 'sccp_cpproduct_group.tpl';
	/** @var \OxidEsales\Eshop\Application\Model\CategoryList $oCatTree */
	protected $oCatTree = null;

	/**
	 * @return string
	 */
	public function render()
	{
		$sReturn = parent::render();

		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		if ( $oRequest->getRequestParameter('aoc') == 1 ) {
			$this->_createCategoryTree("oCatTreeList");
			/** @var \Sinkacom\CreditPlusModule\Controller\Admin\CpProductGroupAjax $oProductGroupAjax */
			$oProductGroupAjax = oxNew(\Sinkacom\CreditPlusModule\Controller\Admin\CpProductGroupAjax::class);
			$this->_aViewData['oxajax'] = $oProductGroupAjax->getColumns();
			return 'popups/sccp_cpproduct_group.tpl';
		}

		/** @var \OxidEsales\Eshop\Core\Model\ListModel $oList */
		$oList = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
		//$oList->init('sccp_prodgroup');
		$oList->init(\Sinkacom\CreditPlusModule\Model\Prodgroup::class);

		$oList->selectString('SELECT * FROM sccp_prodgroup ORDER BY sccp_name ASC');

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
		foreach ( $aPostedParams as $sOXID => $aValues ) {
			/** @var \Sinkacom\CreditPlusModule\Model\Prodgroup $oProdGroup */
			$oProdGroup = oxNew(\Sinkacom\CreditPlusModule\Model\Prodgroup::class);
			$oProdGroup->load($sOXID);
			if ( intval($aValues['delete']) === 1 ) {
				// Delete relation to other items, then item itself
				$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
				$sDeleteID = $oDB->quote($oProdGroup->getId());
				$oDB->execute("DELETE FROM sccp_offered_option_prodgroup WHERE sccp_prodgroup_id = $sDeleteID");
				$oDB->execute("DELETE FROM sccp_prodgroup_article WHERE sccp_prodgroup_id = $sDeleteID");
				$oProdGroup->delete();
			} else {
				$oProdGroup->setId($sOXID);
				$oProdGroup->assign($aValues);
				$oProdGroup->save();
			}
		}
		$this->_aViewData['sError'] = 200;
		$this->_aViewData['sErrorMessage'] = 'SCCP_FINANCING_PRODUCT_GROUPS_SAVED';
	}

	/**
	 * Backport from 4.10.3
	 * 
	 * @param string $sTplVarName
	 * @param string $sEditCatId
	 * @param bool $blForceNonCache
	 * @param null|string $iTreeShopId
	 *
	 * @return string
	 */
	protected function _createCategoryTree( $sTplVarName, $sEditCatId = '', $blForceNonCache = false, $iTreeShopId = null) {
		if ( method_exists('oxAdminDetails', '_createCategoryTree') ) {
			return parent::_createCategoryTree($sTplVarName, $sEditCatId, $blForceNonCache, $iTreeShopId);
		} else {
			// Fallback with backported code
			// caching category tree, to load it once, not many times
			if (!isset($this->oCatTree) || $blForceNonCache) {
				$this->oCatTree = oxNew(\OxidEsales\Eshop\Application\Model\CategoryList::class);
				$this->oCatTree->setShopID($iTreeShopId);

				// setting language
				/** @var \OxidEsales\Eshop\Application\Model\Category $oBase */
				$oBase = $this->oCatTree->getBaseObject();
				$oBase->setLanguage($this->_iEditLang);

				if ( method_exists($this->oCatTree, 'loadList') ) {
					$this->oCatTree->loadList();
				} elseif ( method_exists($this->oCatTree, 'buildList') ) {
					$this->oCatTree->buildList(true);
				}
			}

			// copying tree
			$oCatTree = $this->oCatTree;
			//removing current category
			if ($sEditCatId && isset($oCatTree[$sEditCatId])) {
				unset($oCatTree[$sEditCatId]);
			}

			// add first fake category for not assigned articles
			$oRoot = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
			$oRoot->oxcategories__oxtitle = new \OxidEsales\Eshop\Core\Field('--');

			$oCatTree->assign(array_merge(array('' => $oRoot), $oCatTree->getArray()));

			// passing to view
			$this->_aViewData[$sTplVarName] = $oCatTree;

			return $oCatTree;
		}
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpProductGroup::class,'sccp_cpproduct_group');
