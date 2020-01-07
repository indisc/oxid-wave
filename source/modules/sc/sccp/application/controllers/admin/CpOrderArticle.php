<?php
/**
 * Created by PhpStorm.
 * User: mihovil.bubnjar
 * Date: 22.01.16
 * Time: 16:10
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopVoucher;

/**
 * Admin order article manager.
 * Collects order articles information, updates it on user submit, etc.
 * Admin Menu: Orders -> CreditPlus Orders -> Articles.
 */
//class sccp_cporder_article extends oxAdminDetails
class CpOrderArticle extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{

	/**
	 * Active order object
	 *
	 * @var oxorder
	 */
	protected $_oEditObject = null;

	/**
	 * Executes parent method parent::render(), creates oxorder and oxvoucherlist
	 * objects, appends voucherlist information to order object and passes data
	 * to Smarty engine, returns name of template file "order_article.tpl".
	 *
	 * @return string
	 */
	public function render()
	{
		parent::render();

		if ($oOrder = $this->getEditObject()) {
			$this->_aViewData["edit"] = $oOrder;
			$this->_aViewData["aProductVats"] = $oOrder->getProductVats(true);
		}

		return "sccp_cporder_article.tpl";
	}

	/**
	 * Returns editable order object
	 *
	 * @return oxorder
	 */
	public function getEditObject()
	{
		$soxId = $this->getEditObjectId();
		if ($this->_oEditObject === null && isset($soxId) && $soxId != "-1") {
			$this->_oEditObject = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
			$this->_oEditObject->load($soxId);
		}

		return $this->_oEditObject;
	}

	/**
	 * Removes article from order list.
	 */
	public function deleteThisArticle()
	{
		// get article id
		$sOrderArtId = \OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('sArtID');
		$sOrderId = $this->getEditObjectId();

		/** @var \Sinkacom\CreditPlusModule\Model\OrderArticle $oOrderArticle */
		$oOrderArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
		$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);

		// order and order article exits?
		if ($oOrderArticle->load($sOrderArtId) && $oOrder->load($sOrderId)) {

			// deleting record
			$aError = $oOrderArticle->delete();
			if ( $aError ) {
				$this->_aViewData['sError'] = $aError['sError'];
				$this->_aViewData['sErrorMessage'] = $aError['sErrorMessage'];
			}
			// recalculating order
			$oOrder->recalculateOrder();
		}
	}

	/**
	 * Cancels order item
	 */
	public function storno()
	{
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$oLang = $this->getLang();
		$sOrderArtId = $oRequest->getRequestParameter('sArtID');
		/** @var \Sinkacom\CreditPlusModule\Model\OrderArticle $oArticle */
		$oArticle = oxNew(\OxidEsales\Eshop\Application\Model\OrderArticle::class);
		$oArticle->load($sOrderArtId);

		if ($oArticle->oxorderarticles__oxstorno->value == 1) {
			// We cannot reactivate products, as we can only lessen the amount and not increase it
			$this->_aViewData['sError'] = 500;
			$this->_aViewData['sErrorMessage'] = $oLang->translateString('SCCP_CPORDER_ARTICLE_ERROR_ALREADY_CANCELLED');
		} else {
			$oArticle->oxorderarticles__oxstorno->setValue(1);
			$sStockSign = 1;

			// stock information
			if ($oConfig->getConfigParam('blUseStock')) {
				$oArticle->updateArticleStock(floatval($oArticle->oxorderarticles__oxamount->value) * $sStockSign, $oConfig->getConfigParam('blAllowNegativeStock'));
			}

			$aError = $oArticle->sccpReturnProduct();
			$oArticle->setIsNewOrderItem(false);
			$oArticle->save();
			//get article id
			$sArtId = $oArticle->oxorderarticles__oxartid->value;
			if ( $sArtId ) {
				/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
				$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
				if ($oOrder->load($this->getEditObjectId())) {
					$oOrder->recalculateOrder();
					$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
					$sOrderOxid = $oOrder->oxorder__oxid->value;
					/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface $oRes */
					$oRes = $oDB->select("SELECT OXID oxid FROM oxorderarticles oa WHERE oa.OXORDERID = '$sOrderOxid' AND oa.OXSTORNO = 0");
					if ( !$oRes || $oRes->count() == 0 ) {
						// If this was the last article, cancel the order completely
						$oOrder->cancelOrder();
					}
				}
			}
			if ( $aError ) {
				$this->_aViewData['sError'] = $aError['sError'];
				$this->_aViewData['sErrorMessage'] = $aError['sErrorMessage'];
			} else {
				$this->_aViewData['sError'] = 200;
				$this->_aViewData['sErrorMessage'] = $oLang->translateString('SCCP_CPORDER_ARTICLE_MESSAGE_REDUCED_BY_PRODUCT_PRICE');
			}
		}
	}

	/**
	 * Updates order articles stock and recalculates order
	 */
	public function updateOrder()
	{
		$aOrderArticles = OxidEsales\Eshop\Core\Registry::getRequest()->getRequestParameter('aOrderArticles');
		$oLang = $this->getLang();

		/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
		$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
		if (is_array($aOrderArticles) && $oOrder->load($this->getEditObjectId())) {

			$myConfig = $this->getConfig();

			$aOldAmounts = array();
			$dOldSum = floatval($oOrder->oxorder__oxtotalordersum->rawValue);
			$oOrderArticles = $oOrder->getOrderArticles(true);

			$blUseStock = $myConfig->getConfigParam('blUseStock');
			/** @var oxOrderArticle $oOrderArticle */
			foreach ($oOrderArticles as $oOrderArticle) {
				$sItemId = $oOrderArticle->getId();
				if (isset($aOrderArticles[$sItemId])) {
					$aOldAmounts[$sItemId] = $oOrderArticle->oxorderarticles__oxamount->value;
					// update stock
					if ($blUseStock) {
						$oOrderArticle->setNewAmount($aOrderArticles[$sItemId]['oxamount']);
					} else {
						$oOrderArticle->assign($aOrderArticles[$sItemId]);
						$oOrderArticle->save();
					}
				}
			}

			// recalculating order
			$oOrder->recalculateOrder();
			$dNewSum = floatval($oOrder->oxorder__oxtotalordersum->rawValue);

			if ( $dNewSum > $dOldSum ) {
				/** @var oxOrderArticle $oOrderArticle */
				foreach ($oOrderArticles as $oOrderArticle) {
					$sItemId = $oOrderArticle->getId();
					if (isset($aOrderArticles[$sItemId])) {
						// update stock
						if ($blUseStock) {
							$oOrderArticle->setNewAmount($aOldAmounts[$sItemId]);
						} else {
							$oOrderArticle->save();
						}
					}
				}
				$oOrder->recalculateOrder();
				$this->_aViewData['sError'] = 500;
				$this->_aViewData['sErrorMessage'] = $oLang->translateString('SCCP_CPORDER_ARTICLE_ERROR_NO_INCREASE_POSSIBLE');
			} else {
				$dReturnAmount = $dOldSum-$dNewSum;

				$sUserName = 'User requested';
				if ( isAdmin() ) {
					$sUserName = substr($this->getSession()->getUser()->oxuser__oxusername->value,0,27);
				}
				$sDate = date('c');
				$sDealerOrderNumber = $oOrder->oxorder__oxtransid->value;
				$oContractData = $oOrder->getContractData($sDealerOrderNumber);
				$oConfig = $this->getConfig();
				$aSoapParams = array(
					'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
					'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
					'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
					'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
				);
				$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
				$oWSApi->setDebugState(true,false);

				$aOrderData = array(
					'dealerOrderNumber' => $oContractData->dealerOrderNumber,
					'changeDate' => $sDate,
					'changedBy' => $sUserName,
					'dealerNumber' => $oContractData->dealerNumber,
					// Storno = Restwert auf neue Summe setzen
					'loanAmount' => $dNewSum,
					'cpReferenceNumber' => $oContractData->cpReferenceNumber
				);

				$oResponse = $oWSApi->changeOrderCPWebshop($aOrderData);
				// Fehlerhandling
				if ( is_object($oResponse) && ( property_exists($oResponse,'confirmation') ) && ( property_exists($oResponse->confirmation,'confirmationItems') )  && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
					$iErrorCode = $oResponse->confirmation->confirmationItems->errorCode;
					$sErrorMessage = $oResponse->confirmation->confirmationItems->errorMessage;
					$aError = array(
						'sError' => (500+$iErrorCode),
						'sErrorMessage' => $sErrorMessage
					);
					if ( $iErrorCode == 1 ) {
						// Kein Auftrag gefunden
					} elseif ( $iErrorCode == 2 ) {
						// Pflichtfelder fehlen, kann durch Code heraus nicht passieren
					} elseif ( ($iErrorCode == 6) || ($iErrorCode == 18) || ($iErrorCode == 11)  || ($iErrorCode == 15) ) {
						// changeOrder im Status "Bezahlt", "In Bezahlung", "Ausgeliefert" oder "Lieferfreigabe" kann nicht stattfinden
						$oVoucher = new WebshopVoucher($dReturnAmount,'Warenkorbmodifikation',$sDate,$sUserName,false,$oContractData->id,$oContractData->dealerOrderNumber,$oContractData->dealerNumber);
						$oWSApi->addReturnProduct($oVoucher);

						$oResponse = $oWSApi->returnProductCPWebshop();
						// Fehlerhandling
						if ( is_object($oResponse) && ( property_exists($oResponse,'confirmation') ) && ( property_exists($oResponse->confirmation,'confirmationItems') )  && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
							$iErrorCode = $oResponse->confirmation->confirmationItems->errorCode;
							$sErrorMessage = $oResponse->confirmation->confirmationItems->errorMessage;
							$aError = array(
								'sError' => (500+$iErrorCode),
								'sErrorMessage' => $sErrorMessage
							);
						} else {
							$aError = array(
								'sError' => 200,
								'sErrorMessage' => $oLang->translateString('SCCP_CPORDER_ARTICLE_MESSAGE_PARTIALLY_RETURNED')
							);
						}
					} elseif ( $iErrorCode == 12 ) {
						// Summe der Retouren übersteigt Restwert des Kreditvertrags
					} elseif ( $iErrorCode == 14 ) {
						// Irgendein Pflichtfeld sprengt sein Format/Länge
						// dealerOrderNumber > 40
						// cancelationFrom > 27
					} else {
						// Keine Ahnung was passiert ist
					}
					foreach ( $aError as $sKey => $sValue ) {
						$this->_aViewData[$sKey] = $sValue;
					}
				} else {
					$this->_aViewData['sError'] = 200;
					$this->_aViewData['sErrorMessage'] = $oLang->translateString('SCCP_CPORDER_ARTICLE_MESSAGE_PARTIALLY_RETURNED');
				}
			}
		}
	}

	/**
	 * @return OxidEsales\Eshop\Core\Language
	 */
	protected function getLang() {
		/** @var OxidEsales\Eshop\Core\Language $oLang */
		$oLang = null;

		if ( class_exists('OxidEsales\Eshop\Core\Registry') ) {
			$oLang = OxidEsales\Eshop\Core\Registry::getLang();
		} elseif ( method_exists('OxidEsales\Eshop\Core\Language', 'getInstance' ) ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}

		return $oLang;
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpOrderArticle::class,'sccp_cporder_article');
