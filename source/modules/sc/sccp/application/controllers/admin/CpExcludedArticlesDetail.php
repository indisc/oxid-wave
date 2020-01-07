<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 08.04.16
 * Time: 15:28
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;

//class sccp_cpexcluded_articles_detail extends oxAdminDetails {
class CpExcludedArticlesDetail extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController {

	protected $_sThisTemplate = 'sccp_cpexcluded_articles_detail.tpl';

	public function render() {
		$sReturn = parent::render();
		return $sReturn;
	}

	public function showFinancingOptions() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		if ( $sArtNum = $oRequest->getRequestParameter('sArtNum') )  {
			$this->_aViewData['sArtNum'] = htmlspecialchars($sArtNum);
			$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
			$sArtNumDB = $oDB->quote($sArtNum);
			// SELECT oxid FROM oxartices WHERE oxartnum = $sArtNum to load the object afterwards
			/** @var \OxidEsales\Eshop\Application\Model\ArticleList $oArtList */
			$oArtList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
			$oArtList->selectString("SELECT * FROM oxarticles WHERE OXARTNUM = $sArtNumDB");
			$this->_aViewData['aFinancingOptions'] = array();
			if ( $oArtList->count() > 0 ) {
				$this->_aViewData['sMessage'] = 'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS';
				/** @var \Sinkacom\CreditPlusModule\Model\Article $oArticle */
				foreach ( $oArtList as $oArticle ) {
					$dPrice = $oArticle->getPrice()->getBruttoPrice();
					/** @var \OxidEsales\Eshop\Application\Model\Payment $oPayment */
					$oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
					$oPayment->load('sccp_financing');
					$dFromPrice = floatval($oPayment->oxpayments__oxfromamount->value);
					$dToPrice = floatval($oPayment->oxpayments__oxtoamount->value);
					if ( $dPrice < $dFromPrice ) {
						$aTableArticle = $oArticle->getSccpFinancingMonths(0.00, $dFromPrice);
					} elseif ( $dPrice > $dToPrice ) {
						$aTableArticle = $oArticle->getSccpFinancingMonths(0.00, $dToPrice);
					} else {
						$aTableArticle = $oArticle->getSccpFinancingMonths(0.00, $dPrice);
					}
					$this->_aViewData['aFinancingOptions'][$oArticle->oxarticles__oxartnum->value.': '.$oArticle->oxarticles__oxtitle->value] = $aTableArticle;
				}
			} else {
				$this->_aViewData['sMessage'] = 'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_ARTICLE_FOUND';
			}
		} else {
			if ( $sArtNum === '' ) {
				$this->_aViewData['sMessage'] = 'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_ARTICLE_REQUESTED';
			}
		}
	}

	public function showList() {
		$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
		// SELECT product groups with assigned options without articles (= default groups)
		/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface $oRes */
		$oRes = $oDB->select('
			SELECT pg.oxid oxid, pg.sccp_name sccp_name
			FROM sccp_prodgroup pg
			LEFT JOIN sccp_offered_option_prodgroup oopg ON oopg.sccp_prodgroup_id = pg.oxid
			LEFT JOIN sccp_offered_option oo ON oo.oxid = oopg.sccp_offered_option_id
			LEFT JOIN sccp_prodgroup_article pga ON pga.sccp_prodgroup_id = pg.oxid
			WHERE oo.sccp_active = 1
			AND pga.oxid IS NULL
			GROUP BY pg.oxid
			');
		$this->_aViewData['aArticles'] = array();
		if ( $oRes && $oRes->count() ) {
			$this->_aViewData['sMessage'] = 'SCCP_CPEXCLUDED_ARTICLES_DEFAULT_GROUP_ACTIVE';
			$this->_aViewData['sDefaultGroups'] = '<ul class="sccp-default-groups">';
			for ( $oRes->MoveFirst() ; !$oRes->EOF ; $oRes->MoveNext() ) {
				$sGroupOxid = $oRes->fields['oxid'];
				$sGroupName = $oRes->fields['sccp_name'];
				$this->_aViewData['sDefaultGroups'] .= "<li class='sccp-group-$sGroupOxid'>$sGroupName</li>";
			}
			$this->_aViewData['sDefaultGroups'] .= '</ul>';
		} else {
			$sSQL = '
					SELECT a.OXID oxid, a.OXTITLE oxtitle, a.OXARTNUM oxartnum, a.OXACTIVE oxactive
					FROM oxarticles a
					LEFT JOIN sccp_prodgroup_article pga ON pga.oxartid = a.OXID
					WHERE pga.oxid IS NULL';

			// display variants or not ?
			if ( !$this->getConfig()->getConfigParam('blVariantsSelection') ) {
				$sSQL .= " AND a.oxparentid = '' ";
			}

			$oRes = $oDB->select($sSQL);

			if ( $oRes && $oRes->count() ) {
				$this->_aViewData['sMessage'] = 'SCCP_CPEXCLUDED_ARTICLES_FOLLOWING_ARTICLES_MISSING';
				for ( $oRes->MoveFirst() ; !$oRes->EOF ; $oRes->MoveNext() ) {
					$aArticle = $oRes->fields;
					$this->_aViewData['aArticles'][] = $aArticle;
				}
			} else {
				$this->_aViewData['sMessage'] = 'SCCP_CPEXCLUDED_ARTICLES_ALL_ARTICLES_ASSIGNED';
			}
		}
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpExcludedArticlesDetail::class,'sccp_cpexcluded_articles_detail');
