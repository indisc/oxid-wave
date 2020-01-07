<?php

namespace Sinkacom\CreditPlusModule\Controller;

/**
 * Created by PhpStorm.
 * @author mihovil.bubnjar
 * @date 16.02.2016
 * @time 12:49
 */
//class sccp_restart_order extends oxUBase {
class RestartOrder extends \OxidEsales\Eshop\Application\Controller\FrontendController {
	protected $_sThisTemplate = 'sccp_restart_order.tpl';
	protected $_aBasketArticles = null;
	protected $_iWrapCnt = null;
	protected $_oPreparedBasket = null;

	/** @var \Sinkacom\CreditPlusModule\Model\Order $_oOrder  */
	protected $_oOrder = false;

	public function getBreadCrumb() {
		$aPaths = parent::getBreadCrumb();
		if ( $aPaths === null ) {
			$aPaths = array();
		}

		/** @var oxLang $oLang */
		if ( class_exists(\OxidEsales\Eshop\Core\Registry::class) ) {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		} else {
			/** @noinspection PhpUndefinedMethodInspection */
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}

		$iBaseLanguage = $oLang->getBaseLanguage();

		$aPath = array();
		$aPath['title'] = $oLang->translateString('SCCP_FINANCING_BREADCRUMB_REORDER', $iBaseLanguage, false);
		$aPath['link'] = $this->getLink();
		$aPaths[] = $aPath;
		return $aPaths;
	}

	public function cancelAndReorder() {
		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = $this->getOrder();
		if ( $oOrder ) {
			if ( $this->isInStateForRerun( $oOrder ) ) {
				/** @var oxBasket $oBasket */
				$oBasket = oxNew( \OxidEsales\Eshop\Application\Model\Basket::class );
				/** @var oxOrderArticle[]|oxList $oOrderArticles */
				$oOrderArticles = $oOrder->getOrderArticles( true );

				/** @var oxUtils $oUtils */
				/** @var oxUtilsUrl $oUtilsUrl */
				if ( class_exists( \OxidEsales\Eshop\Core\Registry::class ) ) {
					$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
					$oUtilsUrl = \OxidEsales\Eshop\Core\Registry::get( \OxidEsales\Eshop\Core\UtilsUrl::class );
				} else {
					/** @noinspection PhpUndefinedMethodInspection */
					$oUtils = \OxidEsales\Eshop\Core\Utils::getInstance();
					/** @noinspection PhpUndefinedMethodInspection */
					$oUtilsUrl = \OxidEsales\Eshop\Core\UtilsUrl::getInstance();
				}

				if ( !$oOrderArticles || ($oOrderArticles->count() == 0) ) {
					/** @var oxContent $oContent */
					$oContent = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
					$oContent->loadByIdent('sccpnoarticlesleft');
					$oUtils->redirect($oContent->getLink(), false, 301);
					$oUtils->showMessageAndExit('');
				}
				foreach ( $oOrderArticles as $oOrderArticle ) {
					$oArticle    = $oOrderArticle->getArticle();
					$aSel        = $oOrderArticle->getSelectLists();
					$aPersParams = $oOrderArticle->getPersParams();
					$oBasket->addToBasket( $oArticle->getId(), $oOrderArticle->oxorderarticles__oxamount->value, $aSel, $aPersParams, true );
				}
				$oSession = $this->getSession();
				$oSession->setBasket( $oBasket );
				$oSession->setUser( $oOrder->getOrderUser() );
				$oSession->setVariable('usr', $oOrder->oxorder__oxuserid->value);
				$oOrder->cancelOrder();
				$sAddURL = '';
				$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
				if ( $sPayError = $oRequest->getRequestParameter('payerror') ) {
					$sAddURL = '&payerror='.intval($sPayError);
				}
				$sURL = $oUtilsUrl->processUrl( 'index.php?cl=payment'.$sAddURL );
				$oUtils->redirect( $sURL, false, 301 );
				$oUtils->showMessageAndExit( '' );
			} else {
				$this->_aViewData['sState'] = 'paid';
			}
		}
	}

	public function render() {
		$sReturn            = parent::render();
		$oConfig            = $this->getConfig();

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

		$oOrder = $this->getOrder();
		if ( $oOrder ) {
			$this->_aViewData['oOrder'] = $oOrder;
			if ( $this->isInStateForRerun( $oOrder ) ) {
				/** @var \OxidEsales\Eshop\Core\UtilsUrl $oUtilsURL */
				$oUtilsURL = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsUrl::class);
				$aParams = array(
					'cl' => 'sinkacom_creditplusmodule_restartorder',
					'fnc' => 'cancelAndReorder',
					'oxid' => $oOrder->oxorder__oxid->value,
					'don' => $oOrder->oxorder__oxtransid->value
				);
				$sTargetURL = 'index.php?';
				$sPostFields = '';
				foreach ( $aParams as $sKey => $sValue ) {
					if ( $sTargetURL != 'index.php?' ) {
						$sTargetURL .= '&';
					}
					$sPostFields .= "<input type='hidden' name='$sKey' value='$sValue' />\n";
					$sTargetURL .= $sKey.'='.$sValue;
				}
				$sTargetURL = $oUtilsURL->processUrl($sTargetURL);
				if ( ! $this->_aViewData['sTargetURL'] ) {
					$this->_aViewData['sTargetURL'] = $sTargetURL;
					$this->_aViewData['sPostFields'] = $sPostFields;
				}
			} else {
				// Error 2 - Order has advanced to different state
				$this->_aViewData['sState'] = 'paid';
			}
		} else {
			// Error 1 - Parameter missing or load impossible
			$this->_aViewData['sState'] = 'oxid-don-mismatch';
		}

		return $sReturn;
	}

	public function showInfo() {
		$oOrder = $this->getOrder();
		if ( $oOrder ) {
			if ( $this->isFinished($oOrder) === false ) {
				$oOrder->oxorder__oxtransstatus->setValue('PAYMENT_CANCELLED');
				$oOrder->save();
			} elseif ( $this->isInStateForRerun($oOrder) ) {
				// You can't touch this
				// just let it run through render and wait for the results.
			} else {
				// Error 2 - Order has advanced to different state
				$this->_aViewData['sState'] = 'paid';
			}
		} else {
			// Error 1 - Parameter missing or load impossible
			$this->_aViewData['sState'] = 'oxid-don-mismatch';
		}
		$this->_sThisTemplate = 'sccp_restart_order_showInfo.tpl';
	}

	public function replaceUrl( $sContent ) {
		/** @var \OxidEsales\Eshop\Core\UtilsUrl $oUtilsURL */
		$oUtilsURL = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsUrl::class);
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sDON = $oRequest->getRequestParameter('don');
		$sOXID = $oRequest->getRequestParameter('oxid');
		$sTargetURL = $oUtilsURL->processUrl('index.php');
		$sRetryURL = $oUtilsURL->processUrl('index.php');
		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = $this->getOrder();
		if ( $oOrder ) {
			$sTargetURL = $oUtilsURL->processUrl('index.php?cl=sinkacom_creditplusmodule_restartorder&don='.$sDON.'&oxid='.$sOXID);
			$sRetryURL = $oUtilsURL->processUrl('index.php?cl=sinkacom_creditplusmodule_trigger&fnc=showSccpPopup&don='.$sDON.'&oxid='.$sOXID);
		}
		$sContent = str_replace('http://replace.me/',$sTargetURL,$sContent);
		$sContent = str_replace('http://retry.me/',$sRetryURL,$sContent);
		return $sContent;
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder
	 *
	 * @return bool True, if Order can be restarted (declined), else false
	 */
	public function isInStateForRerun( $oOrder ) {
		$aCancelStates = array( '92', '93' );
		// Cancelled => No reorder possible
		if ( $oOrder->oxorder__oxstorno->value === 1 ) {
			return false;
		}
		// Only accept our payment types for restarts
		if ( substr($oOrder->oxorder__oxpaymenttype->value,0,5) !== 'sccp_' ) {
			return false;
		}
		// State = Not paid => User did not try to go through the payment => No reorder possible
		if ( $oOrder->oxorder__oxtransstatus->value === 'PAYMENT_PENDING' ) {
			return false;
		}
		// State = Payment declined => User did go through the payment and needs other payment type
		if ( $oOrder->oxorder__oxtransstatus->value === 'PAYMENT_DECLINED' ) {
			return true;
		}
		// State = Payment cancelled => User cancelled the payment and wants other payment type
		if ( $oOrder->oxorder__oxtransstatus->value === 'PAYMENT_CANCELLED' ) {
			return true;
		}
		$oContractData = $oOrder->getContractData();
		if ( in_array( $oContractData->state, $aCancelStates ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder
	 * @return bool
	 */
	public function isFinished( $oOrder ) {
		if ( substr($oOrder->oxorder__oxpaymenttype->value,0,5) !== 'sccp_' ) {
			return true;
		}

		if ( $oOrder->oxorder__oxtransstatus->value === 'OK' ) {
			return true;
		}

		$oContractData = $oOrder->getContractData();
		// Not set or drew a blank
		if ( !$oContractData || ($oContractData->id == 0) ) {
			return false;
		}
		return true;
	}

	/**
	 * Returns wrapping options availability state (TRUE/FALSE)
	 * Copied from Basket View
	 * Required for basketcontents.tpl
	 *
	 * @return bool
	 * @see Basket::isWrapping()
	 */
	public function isWrapping()
	{
		if (!$this->getViewConfig()->getShowGiftWrapping()) {
			return false;
		}

		if ($this->_iWrapCnt === null) {
			$this->_iWrapCnt = 0;

			$oWrap = oxNew(\OxidEsales\Eshop\Application\Model\Wrapping::class);
			$this->_iWrapCnt += $oWrap->getWrappingCount('WRAP');
			$this->_iWrapCnt += $oWrap->getWrappingCount('CARD');
		}

		return (bool) $this->_iWrapCnt;
	}

	/**
	 * Copied from Basket::getBasketArticles()
	 * Required for basketcontents.tpl
	 *
	 * @return \Sinkacom\CreditPlusModule\Model\Article[]
	 * @see Basket::getBasketArticles()
	 */
	public function getBasketArticles() {
		if ($this->_aBasketArticles === null) {
			$this->_aBasketArticles = array();
			// passing basket articles
			if ($oBasket = $this->getPreparedBasket()) {
				/** @var oxBasketItem[] $oBasketItems */
				$oBasketItems = $oBasket->getContents();
				$aBasketArticles = array();
				foreach ( $oBasketItems as $sKey => $oBasketItem ) {
					$aBasketArticles[$sKey] = $oBasketItem->getArticle();
				}
				$this->_aBasketArticles = $aBasketArticles;

			}
		}
		return $this->_aBasketArticles;
	}

	public function getPreparedBasket() {
		if ( $this->_oPreparedBasket === null ) {
			$oOrder = $this->getOrder();
			/** @var \Sinkacom\CreditPlusModule\Model\Basket $oBasket */
			$oBasket = oxNew(\OxidEsales\Eshop\Application\Model\Basket::class);
			if ( $oOrder ) {
				/** @var \Sinkacom\CreditPlusModule\Model\OrderArticle[] $oOrderItems */
				$oOrderItems = $oOrder->getOrderArticles();
				if ( $oCard = $oOrder->getGiftCard() ) {
					$oBasket->setCardId($oCard->getId());
				}
				foreach ( $oOrderItems as $oOrderItem ) {
					try {
						/** @var oxBasketItem $oBasketItem */
						$oBasketItem = $oBasket->addToBasket($oOrderItem->oxorderarticles__oxartid->value,$oOrderItem->oxorderarticles__oxamount->value,$oOrderItem->getSelectLists(),$oOrderItem->getPersParams(),true,$oOrderItem->oxorderarticles__oxisbundle->value);
						$oBasketItem->setWrapping($oOrderItem->oxorderarticles__oxwrapid->value);
					} catch (Exception $e) {
						// Product lost :(
						// Never mind, continue anyways
					}
				}
				$oBasket->calculateBasket();
			}
			$this->_oPreparedBasket = $oBasket;
		}
		return $this->_oPreparedBasket;
	}

	/**
	 * Method returns object with explanation marks for articles in basket.
	 * Copied from Basket View
	 * Required for basketcontents.tpl
	 *
	 * @return oxBasketContentMarkGenerator
	 * @see Basket::getBasketContentMarkGenerator()
	 */
	public function getBasketContentMarkGenerator() {
		/** @var oxBasketContentMarkGenerator $oBasketContentMarkGenerator */
		$oBasketContentMarkGenerator = oxNew(\OxidEsales\Eshop\Application\Model\BasketContentMarkGenerator::class, $this->getSession()->getBasket());
		return $oBasketContentMarkGenerator;
	}

	/**
	 * Loads sccp_oxorder from database if values match. Returns null if mismatch happens.
	 * Returns null if Dealer Order Number (don) or Oxid (oxid) are not given in the request
	 * @return \Sinkacom\CreditPlusModule\Model\Order Can be null if no Order can be loaded or Dealer Order Number does not match the one recorded for Oxid
	 */
	public function getOrder() {
		if ( $this->_oOrder === false ) {
			$oConfig = $this->getConfig();
			$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
			$sDON = $oRequest->getRequestParameter('don');
			$sOXID = $oRequest->getRequestParameter('oxid');
			if ( !$sDON || !$sOXID ) {
				$this->_oOrder = null;
				return null;
			}
			/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
			$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
			if ( $oOrder->load($sOXID) ) {
				if ( $oOrder->oxorder__oxtransid->value == $sDON ) {
					$this->_oOrder = $oOrder;
					return $oOrder;
				}
			}
			$this->_oOrder = null;
		}
		return $this->_oOrder;
	}


}

class_alias(\Sinkacom\CreditPlusModule\Controller\RestartOrder::class,'sccp_restart_order');
