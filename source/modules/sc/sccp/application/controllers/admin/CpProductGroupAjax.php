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
 * Class manages category articles
 */
//class sccp_cpproduct_group_ajax extends ajaxListComponent {
class CpProductGroupAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax {

	/**
	 * If true extended column selection will be build
	 *
	 * @var bool
	 */
	protected $_blAllowExtColumns = true;

	/**
	 * Columns array
	 *
	 * @var array
	 */
	protected $_aColumns = array(
		'container1' => array( // field , table, visible, multilanguage, ident
			array(
				'oxartnum',
				'oxarticles',
				1,
				0,
				0
			),
			array(
				'oxtitle',
				'oxarticles',
				1,
				1,
				0
			),
			array(
				'oxean',
				'oxarticles',
				1,
				0,
				0
			),
			array(
				'oxmpn',
				'oxarticles',
				0,
				0,
				0
			),
			array(
				'oxprice',
				'oxarticles',
				0,
				0,
				0
			),
			array(
				'oxstock',
				'oxarticles',
				0,
				0,
				0
			),
			array(
				'oxid',
				'oxarticles',
				0,
				0,
				1
			)
		),
		'container2' => array(
			array(
				'oxartnum',
				'oxarticles',
				1,
				0,
				0
			),
			array(
				'oxtitle',
				'oxarticles',
				1,
				1,
				0
			),
			array(
				'oxean',
				'oxarticles',
				1,
				0,
				0
			),
			array(
				'oxmpn',
				'oxarticles',
				0,
				0,
				0
			),
			array(
				'oxprice',
				'oxarticles',
				0,
				0,
				0
			),
			array(
				'oxstock',
				'oxarticles',
				0,
				0,
				0
			),
			array(
				'oxid',
				'oxarticles',
				0,
				0,
				1
			)
		)
	);

	/**
	 * Returns SQL query for data to fetch
	 *
	 * @return string
	 */
	protected function _getQuery() {
		$sArticleTable = $this->_getViewName('oxarticles');
		$sPG2AView = 'sccp_prodgroup_article';


		$sOxid = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('oxid');
		$sSynchOxid = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('synchoxid');
		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

		// copied from oxadminview
		$sJoin = " {$sArticleTable}.oxid={$sPG2AView}.oxartid ";

		// category selected or not ?
		if ( !$sOxid ) {

			// dodger performance
			$sQAdd = ' from '.$sArticleTable.' where 1 ';

			if ( $sSynchOxid && $sOxid != $sSynchOxid ) {

				$sSubSelect = ' and '.$sArticleTable.'.oxid not in ( ';
				$sSubSelect .= "select $sArticleTable.oxid from $sPG2AView left join $sArticleTable ";
				$sSubSelect .= "on $sJoin where $sPG2AView.sccp_prodgroup_id =  ".$oDb->quote($sSynchOxid)." ";
				$sSubSelect .= 'and '.$sArticleTable.'.oxid is not null ) ';

				$sQAdd .= $sSubSelect;
			}
		} else {
			if ( $sSynchOxid ) {
				$sO2CView = $this->_getViewName('oxobject2category');
				$sCatView = $this->_getViewName('oxcategories');
				$sJoinO2C = " {$sArticleTable}.oxid={$sO2CView}.oxobjectid ";

				$sQAdd = " from $sO2CView inner join $sCatView c1 on $sO2CView.oxcatnid = c1.oxid
					inner join $sCatView croot on c1.oxrootid = croot.oxid
					inner join $sCatView c2 on ( (c2.oxrootid = croot.oxid) AND (c2.oxleft <= c1.oxleft) AND (c2.oxright >= c1.oxright) )
				    inner join $sArticleTable ON $sJoinO2C ";
				$sQAdd .= "where c2.oxid = ".$oDb->quote($sOxid);

				if ( $sSynchOxid && $sOxid != $sSynchOxid ) {
					$sSubSelect = ' and '.$sArticleTable.'.oxid not in ( ';
					$sSubSelect .= "select $sArticleTable.oxid from $sPG2AView inner join $sArticleTable ";
					$sSubSelect .= "on $sJoin where $sPG2AView.sccp_prodgroup_id =  ".$oDb->quote($sSynchOxid).") ";
					$sQAdd .= $sSubSelect;
				}
			} else {
				$sQAdd = " from $sPG2AView inner join $sArticleTable ";
				$sQAdd .= " on $sJoin where $sPG2AView.sccp_prodgroup_id = ".$oDb->quote($sOxid);
			}
		}

		return $sQAdd;
	}

	/**
	 * Adds filter SQL to current query
	 *
	 * @param string $sQ query to add filter condition
	 *
	 * @return string
	 */
	protected function _addFilter( $sQ ) {
		$sArtTable = $this->_getViewName('oxarticles');
		$sQ = parent::_addFilter($sQ);

		// display variants or not ?
		if ( !$this->getConfig()->getConfigParam('blVariantsSelection') ) {
			$sQ .= " and {$sArtTable}.oxparentid = '' ";
		}

		return $sQ;
	}

	/**
	 * Adds article to product group
	 */
	public function addArticle() {

		$aArticles = $this->_getActionIds('oxarticles.oxid');
		$sPGID = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('synchoxid');
		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
		$sArticleTable = $this->_getViewName('oxarticles');

		// adding
		if ( \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('all') ) {
			$aArticles = $this->_getAll($this->_addFilter("select $sArticleTable.oxid ".$this->_getQuery()));
		}

		if ( is_array($aArticles) ) {

			$sO2CView = $this->_getViewName('sccp_prodgroup_article');

			/** @var \OxidEsales\Eshop\Core\Model\BaseModel $oNew */
			$oNew = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
			$oNew->init('sccp_prodgroup_article');

			if ( class_exists('OxidEsales\Eshop\Core\Registry') ) {
				$oUtilsObject = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsObject::class);
			} else {
				$oUtilsObject = \OxidEsales\Eshop\Core\UtilsObject::getInstance();
			}

			foreach ( $aArticles as $sAdd ) {

				// check, if it's already in, then don't add it again
				$sSelect = "select 1 from $sO2CView as sccp_prodgroup_article where sccp_prodgroup_article.sccp_prodgroup_id= "
					.$oDb->quote($sPGID)." and sccp_prodgroup_article.oxartid = ".$oDb->quote($sAdd);
				if ( $oDb->getOne($sSelect, false, false) ) {
					continue;
				}

				$oNew->sccp_prodgroup_article__oxid = new \OxidEsales\Eshop\Core\Field($oNew->setId($oUtilsObject->generateUId()));
				$oNew->sccp_prodgroup_article__oxartid = new \OxidEsales\Eshop\Core\Field($sAdd);
				$oNew->sccp_prodgroup_article__sccp_prodgroup_id = new \OxidEsales\Eshop\Core\Field($sPGID);

				$oNew->save();
			}
		}
	}

	/**
	 * Removes article from product group
	 */
	public function removeArticle() {
		$aArticles = $this->_getActionIds('oxarticles.oxid');
		$sCategoryID = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('oxid');
		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

		if ( \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('all') ) {
			$sArticleTable = $this->_getViewName('oxarticles');
			$aArticles = $this->_getAll($this->_addFilter("select $sArticleTable.oxid ".$this->_getQuery()));
		}

		// removing
		if ( is_array($aArticles) && count($aArticles) ) {
			if ( method_exists($oDb, 'quoteArray') ) {
				$sProdIds = implode(", ", $oDb->quoteArray($aArticles));
			} elseif ( method_exists($oDb, 'qstr') ) {
				$sProdIds = '';
				foreach ( $aArticles as $sArticle ) {
					if ( $sProdIds ) { $sProdIds .= ', '; }
					$sProdIds .= $oDb->qstr($sArticle);
				}
			}

			$sDelete = "DELETE FROM sccp_prodgroup_article WHERE ".
				"sccp_prodgroup_id=".$oDb->quote($sCategoryID);
			if ( isset($sProdIds) && $sProdIds ) {
				if ( !$this->getConfig()->getConfigParam('blVariantsSelection') ) {
					$sQ = $sDelete." and oxartid in
	                    ( select oxid from oxarticles where oxparentid in ( {$sProdIds} ) )";
					$oDb->execute($sQ);
				}
				$sQ = $sDelete." and oxartid in ( {$sProdIds} )";
				$oDb->execute($sQ);
			}
		}
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpProductGroupAjax::class,'sccp_cpproduct_group_ajax');
