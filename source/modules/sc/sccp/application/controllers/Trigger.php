<?php


namespace Sinkacom\CreditPlusModule\Controller;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopContract;
/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 10.02.16
 * Time: 13:53
 */
//class sccp_trigger extends oxUBase {
class Trigger extends \OxidEsales\Eshop\Application\Controller\FrontendController {

	protected $_sThisTemplate = 'sccp_trigger.tpl';

	protected $_sFinishedURL = 'cl=sinkacom_creditplusmodule_trigger&fnc=showSccpFinished&don=###DON###&oxid=###OXID###';
	protected $_sRestartURL = 'cl=sinkacom_creditplusmodule_restartorder&don=###DON###&oxid=###OXID###';
	protected $_sRestartDirectURL = 'cl=sinkacom_creditplusmodule_restartorder&fnc=cancelAndReorder&payerror=13021&don=###DON###&oxid=###OXID###';
	protected $_sRestartInfoURL = 'cl=sinkacom_creditplusmodule_restartorder&fnc=showInfo&don=###DON###&oxid=###OXID###';

	public function keepSession() {
		$this->_aViewData['iStatusCode'] = 200;
		$this->_aViewData['sStatusText'] = 'The session has been updated.';
	}

	public function finishContract() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sDealerOrderNumber = $oRequest->getRequestParameter('don');
		$iStatusCode = intval($oRequest->getRequestParameter('statusCode'));
		$this->updateContractWorker($sDealerOrderNumber, $iStatusCode);
	}

	/**
	 * @param string $sURL
	 * @param string $sDON
	 * @param string $sOXID
	 * @return string
	 */
	protected function getReplacedURL( $sURL, $sDON = '', $sOXID = '' ) {
		return str_replace(array(
			'###DON###',
			'###OXID###'
		), array(
			$sDON,
			$sOXID
		), $sURL);
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder
	 * @param int $iStatusCode
	 */
	protected function finishContractWorker( &$oOrder, $iStatusCode ) {
		$aGoodStates = array(
			// Tentative OK or Hard OK, customer needs to sign or has signed contract accepted by the bank
			24,
			// Payment in progress (Bank to Shop)
			32,
			// Contract is already paid (Bank to Shop)
			99
		);
		$aGreyStates = array(
			// Open - Manual decision
			20,
			// Open - Additional info required from customer
			25,
		);
		$aBadStates = array(
			// Unknown Error
			2,
			// Declined - soft
			92,
			// Declined - hard
			93
		);
		if ( class_exists(\OxidEsales\Eshop\Core\Registry::class) ) {
			/** @var \OxidEsales\Eshop\Core\Utils $oUtils */
			$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
			/** @var \OxidEsales\Eshop\Core\UtilsUrl $oUtilsUrl */
			$oUtilsUrl = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsUrl::class);
		} else {
			/** @var \OxidEsales\Eshop\Core\Utils $oUtils */
			/** @noinspection PhpUndefinedMethodInspection */
			$oUtils = \OxidEsales\Eshop\Core\Utils::getInstance();
			/** @var \OxidEsales\Eshop\Core\UtilsUrl $oUtilsUrl */
			/** @noinspection PhpUndefinedMethodInspection */
			$oUtilsUrl = \OxidEsales\Eshop\Core\UtilsUrl::getInstance();
		}
		if ( in_array($iStatusCode, $aGoodStates) ) {
			$oOrder->oxorder__oxtransstatus = new \OxidEsales\Eshop\Core\Field('OK');
			$oOrder->oxorder__oxfolder = new \OxidEsales\Eshop\Core\Field('ORDERFOLDER_NEW');
			$oOrder->save();
			$sRedirectURL = $oUtilsUrl->processUrl('index.php?'.
				$this->getReplacedURL(
					$this->_sFinishedURL,
					$oOrder->oxorder__oxtransid->value,
					$oOrder->oxorder__oxid->value
				)
			);
			$oUtils->redirect($sRedirectURL, false, 301);
			$oUtils->showMessageAndExit('');
		} elseif ( in_array($iStatusCode, $aBadStates) ) {
			$oOrder->oxorder__oxtransstatus = new \OxidEsales\Eshop\Core\Field('PAYMENT_DECLINED');
			$oOrder->oxorder__oxfolder = new \OxidEsales\Eshop\Core\Field('ORDERFOLDER_PROBLEMS');
			$oOrder->save();
			$sRedirectURL = $oUtilsUrl->processUrl('index.php?'.
				$this->getReplacedURL(
					$this->_sRestartDirectURL,
					$oOrder->oxorder__oxtransid->value,
					$oOrder->oxorder__oxid->value
				)
			);
			$oUtils->redirect($sRedirectURL, false, 301);
			$oUtils->showMessageAndExit('');
		} elseif ( in_array($iStatusCode, $aGreyStates) ) {
			$oOrder->oxorder__oxtransstatus = new \OxidEsales\Eshop\Core\Field('OK');
			$oOrder->oxorder__oxfolder = new \OxidEsales\Eshop\Core\Field('ORDERFOLDER_NEW');
			$oOrder->save();
			$sRedirectURL = $oUtilsUrl->processUrl('index.php?'.
				$this->getReplacedURL(
					$this->_sFinishedURL,
					$oOrder->oxorder__oxtransid->value,
					$oOrder->oxorder__oxid->value
				)
			);
			$oUtils->redirect($sRedirectURL, false, 301);
			$oUtils->showMessageAndExit('');
		}

		if ( !isset($this->_aViewData['iStatusCode']) ) {
			$this->_aViewData['iStatusCode'] = 500;
			$this->_aViewData['sStatusText'] = 'Unknown Status code ('.$iStatusCode.').';
		}
	}

	public function updateContract() {
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sDealerOrderNumber = $oRequest->getRequestParameter('don');
		$iStatus = intval($oRequest->getRequestParameter('callback'));
		$this->updateContractWorker($sDealerOrderNumber, $iStatus);
	}

	/**
	 * @param string $sDealerOrderNumber
	 * @param int $iStatus
	 */
	protected function updateContractWorker( $sDealerOrderNumber, $iStatus ) {

		if ( $sDealerOrderNumber ) {
			// Select Order from Database and update fields
			$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_NUM);
			/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface $oRes */
			$oRes = $oDB->select("SELECT OXID FROM oxorder WHERE OXTRANSID = '$sDealerOrderNumber'");
			if ( $oRes && $oRes->count() ) {
				$sOrderID = $oRes->fields[0];
				/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
				$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
				if ( $oOrder->load($sOrderID) ) {
					// This should update the fields respectively
					$oOrder->getContractData();
					if ( $oOrder->oxorder__oxtransstatus->value == 'PAYMENT_PENDING' ) {
						$this->finishContractWorker($oOrder, $iStatus);
					}
					if ( $this->isStateChanged($oOrder) ) {
						$this->sendChangedMail($oOrder);
					}
					if ( !isset($this->_aViewData['iStatusCode']) ) {
						$this->_aViewData['iStatusCode'] = 200;
						$this->_aViewData['sStatusText'] = 'Order was updated.';
					}
				} else {
					$this->_aViewData['iStatusCode'] = 500;
					$this->_aViewData['sStatusText'] = 'Order could not be loaded';
				}
			} else {
				$this->_aViewData['iStatusCode'] = 404;
				$this->_aViewData['sStatusText'] = 'Dealer Order Number (don) was not found on this system';
			}
		} else {
			$this->_aViewData['iStatusCode'] = 400;
			$this->_aViewData['sStatusText'] = 'Dealer Order Number (don) is missing';
		}
	}

	/**
	 * Wenn alles abgelaufen ist, Weiterleitungsseite zum internen Ziel anzeigen
	 */
	public function showSccpFinished() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		if ( $oRequest->getRequestParameter('error') ) {
			
			if ( $oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder' ) {
				$this->_sFinishedURL = 'cl=payment';
			}
			
			$this->_aViewData['sTargetURL'] = $oConfig->getShopSecureHomeUrl().
				$this->getReplacedURL(
					$this->_sFinishedURL,
					$oRequest->getRequestParameter('don'),
					$oRequest->getRequestParameter('oxid')
				);
			$this->_aViewData['sClass'] = 'sccp_trigger';
			$this->_aViewData['sFunction'] = 'showSccpFinished';
			
			if ( $oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder' ) {
				$this->_aViewData['sClass'] = 'payment';
				$this->_aViewData['sFunction'] = '';
			}
			

			if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
				/** @var \OxidEsales\Eshop\Core\Language $oLang */
				$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
			} else {
				/** @var \OxidEsales\Eshop\Core\Language $oLang */
				/** @noinspection PhpUndefinedMethodInspection */
				$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
			}
			$sRestartURL = $oConfig->getShopSecureHomeUrl().
				$this->getReplacedURL(
					$this->_sRestartURL,
					$oRequest->getRequestParameter('don'),
					$oRequest->getRequestParameter('oxid')
				);
			$this->_aViewData['sFinanceError'] = str_replace('###URL###', $sRestartURL, $oLang->translateString('SCCP_FINANCING_FINISHED_ERRORS'));
		} else {
			/** @var OxidEsales\Eshop\Application\Model\Content $oContent */
			$oContent = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
			// Thank You Page with custom text
			if ( $oContent->loadByIdent('sccpeverythingfinished') ) {
				$this->_aViewData['sTargetURL'] = $oContent->getLink();
				$this->_aViewData['sContentOxid'] = $oContent->getId();
				$this->_aViewData['sClass'] = 'content';
				$this->_aViewData['sFunction'] = '';

				if ( $oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder' ) {
					$this->_aViewData['sTargetURL'] = $oConfig->getShopSecureHomeUrl().'cl=order&fnc=execute';
					$this->_aViewData['sClass'] = 'order';
					$this->_aViewData['sFunction'] = 'execute';
				}
				
			} else {
				$this->_aViewData['sTargetURL'] = $oConfig->getShopSecureHomeUrl();
				$this->_aViewData['sClass'] = 'start';
				$this->_aViewData['sFunction'] = '';

				if ( $oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder' ) {
					$this->_aViewData['sTargetURL'] = $oConfig->getShopSecureHomeUrl().'cl=payment';
					$this->_aViewData['sClass'] = 'payment';
					$this->_aViewData['sFunction'] = '';
				}
			}
		}
		$this->_aViewData['sHiddenFields'] = $this->getViewConfig()->getNavFormParams();
		$this->_aViewData['sParams'] = '';
		$this->_aViewData['sSignature'] = '';
		if ( ($this->_aViewData['sClass'] == 'order') && ($this->_aViewData['sFunction'] == 'execute') ) {
			$this->_aViewData['sHiddenFields'] .= '<input type="hidden" name="stoken" value="'.$this->getSession()->getSessionChallengeToken().'" />';
			$this->_aViewData['sHiddenFields'] .= '<input type="hidden" name="ord_agb" value="1" />';
		}
		if ( ($oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder') ) {
			/** @var \Sinkacom\CreditPlusModule\Model\Order $oSavedOrder */
			$oSavedOrder = $this->getSession()->getVariable('oCreatedOrder');
			/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
			$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
			$oOrder->load($oSavedOrder->getId());
			$this->_aViewData['sHiddenFields'] .= '<input type="hidden" name="sDeliveryAddressMD5" value="'.$oSavedOrder->sDeliveryAddressHash.'" />';
			$this->getSession()->setVariable('oCreatedOrder', $oOrder);
			$oOrder->delete();
		}
		$this->_sThisTemplate = 'sccp_checkout_finished.tpl';
	}


	/**
	 * Wenn der Weg über iFrame gewählt wird, dann einfach nur die URL mit htmlentities encoden und das iframe Tempalte ausgeben.
	 */
	public function showSccpIframe() {
		$oSession = $this->getSession();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		if ( !$oSession->hasVariable('sTargetURL') || ($oRequest->getRequestParameter('don') && $oRequest->getRequestParameter('oxid')) ) {
			$sTargetURL = $this->makePaymentFromGetParams();
		} else {
			$sTargetURL = $oSession->getVariable('sTargetURL');
		}
		$this->_aViewData['sTargetURL'] = htmlentities($sTargetURL);
		$this->_sThisTemplate = 'sccp_checkout_iframed.tpl';
	}

	/**
	 * Wenn der Weg über Popup gewählt wird, dann einfach nur die URL mit htmlentities encoden und das popup Tempalte ausgeben.
	 */
	public function showSccpPopup() {
		$oConfig = $this->getConfig();
		$oSession = $this->getSession();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		if ( !$oSession->hasVariable('sTargetURL') || ($oRequest->getRequestParameter('don') && $oRequest->getRequestParameter('oxid')) ) {
			$sTargetURL = $this->makePaymentFromGetParams();
		} else {
			$sTargetURL = $oSession->getVariable('sTargetURL');
		}
		$this->_aViewData['sBackURL'] = $this->getBackURL();
		$this->_aViewData['sTargetURL'] = $sTargetURL;
		$this->_sThisTemplate = 'sccp_checkout_popup.tpl';
	}

	/**
	 * @return string Target URL (Payment page on CreditPlus)
	 */
	protected function makePaymentFromGetParams() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sDON = $oRequest->getRequestParameter('don');
		$sOXID = $oRequest->getRequestParameter('oxid');
		if ( $sDON && $sOXID ) {
			/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
			$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
			if ( $oOrder->load($sOXID) ) {
				if ( $oOrder->oxorder__oxtransid->value == $sDON ) {
					$oOrderData = $oOrder->getContractData();
					if ( !$oOrderData || $oOrderData->state == '' ) {
						/** @var \Sinkacom\CreditPlusModule\Model\Paymentgateway $oPaymentGateway */
						$oPaymentGateway = oxNew(\OxidEsales\Eshop\Application\Model\PaymentGateway::class);
						$sTargetURL = $oPaymentGateway->getTargetURL($oOrder,$oOrder->oxorder__oxtotalordersum->value);
						$oOrder->setSccpOxorderFinance($sTargetURL,time());
						$oOrder->save();
						$oSession = $this->getSession();
						$oSession->setVariable('sTargetURL',$sTargetURL);
						$oSession->setVariable('oCreatedOrder',$oOrder);
					} else {
						/** @var \OxidEsales\Eshop\Application\Model\Content $oContentFinished */
						$oContentFinished = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
						$oContentFinished->loadByIdent('sccpalreadyfinished');
						$sTargetURL = $oContentFinished->getLink();
						/** @var \OxidEsales\Eshop\Core\Utils $oUtils */
						$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
						$oUtils->redirect($sTargetURL,false,301);
						$oUtils->showMessageAndExit('');
					}
					return $sTargetURL;
				}
			}
		}
		/** @var \OxidEsales\Eshop\Application\Model\Content $oContent */
		$oContent = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
		$oContent->loadByIdent('sccptechnicalerror');
		return $oContent->getLink();
	}

	/**
	 * @return string URL to use instead of history.back();
	 */
	protected function getBackURL() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		/** @var \OxidEsales\Eshop\Core\UtilsUrl $oUtilsURL */
		$oUtilsURL = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsUrl::class);
		if ( $oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder' ) {
			$sBackURL = $oUtilsURL->processUrl('index.php?cl=payment');
		} else {
			$sDON = $oRequest->getRequestParameter('don');
			$sOrderOXID = $oRequest->getRequestParameter('oxid');
			/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
			$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
			if ( $sDON && $sOrderOXID && ($oOrder->load($sOrderOXID) && ($oOrder->oxorder__oxtransid->value == $sDON) ) ) {
				$sBackURL = $oUtilsURL->processUrl('index.php?'.
					$this->getReplacedURL(
						$this->_sRestartInfoURL,
						$sDON,
						$sOrderOXID
					)
				);
			} else {
				$sBackURL = $oUtilsURL->processUrl('index.php?');
			}
		}
		return $sBackURL;
	}

	public function testRate() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$dPrice = floatval($oRequest->getRequestParameter('dPrice'));
		$iMonths = intval($oRequest->getRequestParameter('iMonths'));
		$dInterest = floatval($oRequest->getRequestParameter('dInterest'));

		header('Content-type: text/plain; charset=utf-8');
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oAPI = new CreditPlusWebshopAPI($aSoapParams);
		$dEffective = $oAPI->getEffectiveInterestFromNominalInterest($dInterest);
		$dNominal = $oAPI->getNominalInterestFromEffectiveInterest($dInterest);
		var_dump(array(
			'dPrice' => $dPrice,
			'iMonths' => $iMonths,
			'dInterest' => $dInterest,
			'dEffectiveInterest' => $dEffective,
			'dNominal' => $dNominal
		));

		$dReturn = $oAPI->getMonthRateByPriceMonthsAndInterest($dPrice, $iMonths, $dNominal);
		$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
		$oUtils->showMessageAndExit($dReturn.'');
	}

	public function testProductRate() {
		header('Content-type: text/plain; charset=utf-8');
		/** @var \OxidEsales\Eshop\Application\Model\Article $oArticle */
		$oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
		$oArticle->load('b56597806428de2f58b1c6c7d3e0e093');
		$aFinancingMonths1 = $oArticle->getSccpFinancingMonths(0.01);
		$oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
		$oArticle->load('058e613db53d782adfc9f2ccb43c45fe');
		$aFinancingMonths2 = $oArticle->getSccpFinancingMonths(0.01);
		$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
		$oUtils->showMessageAndExit(var_export($aFinancingMonths1, true)."\r\n".var_export($aFinancingMonths2, true));
	}

	public function testSignature() {
		$_SERVER['REQUEST_URI'] = str_replace('&fnc=testSignature','&fnc=dispatch',$_SERVER['REQUEST_URI']);
		$bSignOK = $this->checkSignature();
		$oUtils = \OxidEsales\Eshop\Core\Registry::getUtils();
		$oUtils->showMessageAndExit(var_export($bSignOK, true).'');
	}

	/**
	 * Checks if the signature is correct. If bTestMode is active, any signature is accepted.
	 * If bTestMode is not set, only the two possible calculations are accepted.
	 * @param string $sSignature Signature given by request - Signing Parameter callback and the Base URL before that
	 * @return bool True if $sSignature is OK, false if not
	 */
	protected function checkSignature( $sSignature = '' ) {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$bTestMode = $oConfig->getShopConfVar('bTestMode', null, 'module:sccp');
		if ( $bTestMode /* && ($sSignature == 'bogus') */ ) {
			return true;
		} else {

			// Do a real check in Live Mode and even Test Mode (if not 'bogus')
			$sSaltValue = $oConfig->getShopConfVar('sSignatureSalt', null, 'module:sccp');
			$sSignature = $sSignature?$sSignature:$oRequest->getRequestParameter('signature');
			$sDON = $oRequest->getRequestParameter('don');
			$sCallback = $oRequest->getRequestParameter('callback');
			$sBaseURL = $oConfig->getConfigParam('sShopURL');
			$sSignedURL = str_replace(
				array(
					'&signature='.$sSignature,
					'&callback='.$sCallback
				),
				array(
					'',
					''
				),
				$sBaseURL.substr($_SERVER['REQUEST_URI'], 1)
			);
			// URL+Salt+Callback
			$bValid = ($sSignature == md5($sSignedURL.$sSaltValue.$sCallback));
			// or DON+Salt+Callback
			$bValid = $bValid || ($sSignature == md5($sDON.$sSaltValue.$sCallback));
			if ( $oRequest->getRequestParameter('fnc') == 'testSignature' ) {
				var_dump(md5($sSignedURL.$sSaltValue.$sCallback));
				var_dump(md5($sDON.$sSaltValue.$sCallback));
			}
			return $bValid;
		}
	}

	/**
	 * This function sends the request for updates, Keep-Alive and finish into the correct worker functions.
	 */
	public function dispatch() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$iStatus = intval($oRequest->getRequestParameter('callback'));
		$sSignature = $oRequest->getRequestParameter('signature');
		if ( $this->checkSignature($sSignature) === false ) {
			$this->_aViewData['iStatusCode'] = 400;
			$this->_aViewData['sStatusText'] = 'Signature and Data have a mismatch';
			return;
		}

		if ( $iStatus === 1 ) {
			$this->keepSession();
			return;
		}
		$sData = $oRequest->getRequestParameter('data');
		$sDON = $oRequest->getRequestParameter('don');
		if ( $sData ) {
			try {
				$aData = unserialize(gzinflate(base64_decode($sData)));
				if ( ($oConfig->getShopConfVar('sTransactionMode',null,'module:sccp') == 'inorder') && (!$this->getOrder($sDON)) ) {
					$this->createOrderFromSession();
				}
				$this->updateContractWorker($sDON, $iStatus);
			} catch ( \Exception $e ) {
				$this->_aViewData['iStatusCode'] = 400;
				if ( !isset($aData) ) {
					$aData = false;
				}
				$this->_aViewData['sStatusText'] = "Data (data) is not formatted correctly. Given: $sData Parsed: ".var_export($aData, true);
			}
		} else {
			$this->_aViewData['iStatusCode'] = 400;
			$this->_aViewData['sStatusText'] = 'Data (data) is missing';
		}
	}

	/**
	 * @param string $sDealerOrderNumber
	 *
	 * @return null|\Sinkacom\CreditPlusModule\Model\Order
	 */
	protected function getOrder($sDealerOrderNumber) {
		if ( $sDealerOrderNumber ) {
			// Select Order from Database and update fields
			$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_NUM);
			/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface $oRes */
			$oRes = $oDB->select("SELECT OXID FROM oxorder WHERE OXTRANSID = '$sDealerOrderNumber'");
			if ( $oRes && $oRes->count() ) {
				$sOrderID = $oRes->fields[0];
				/** @var \Sinkacom\CreditPlusModule\Model\Order $oOrder */
				$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
				if ( $oOrder->load($sOrderID) ) {
					return $oOrder;
				}
			}
		}
		return null;
	}

	/**
	 * Takes the session variable created on redirect from payment and puts it back into the database
	 */
	protected function createOrderFromSession() {
		$oSession = $this->getSession();
		$oOrder = $oSession->getVariable('oCreatedOrder');
		if ( $oOrder ) {
			$oOrder->save();
		}
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\Order $oOrder
	 * @return bool
	 */
	protected function isStateChanged( $oOrder ) {
		$oContractData = $oOrder->getContractData();
		$sContractState = $this->translatePaymentState($oContractData);
		if ( $sContractState !== 'review_necessary' ) {
			if ( $oOrder->oxorder__oxtransstatus->value != $sContractState ) {
				// Contract state changed, send mail
				$oOrder->oxorder__oxtransstatus->setValue($sContractState);
				$oOrder->save();
				return true;
			}
		}
		return false;
	}

	protected function sendChangedMail( $oOrder ) {
		/** @var \OxidEsales\Eshop\Core\Email $oMail */
		$oMail = oxNew(\OxidEsales\Eshop\Core\Email::class);
		/** @var \Smarty $oSmarty */
		$oSmarty = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class)->getSmarty();
		/** @var \OxidEsales\Eshop\Core\Language $oLang */
		$oLang = null;
		if ( method_exists('OxidEsales\Eshop\Core\Language', 'getInstance') ) {
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		} else {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		}
		$sTitle = $oLang->translateString('SCCP_MAIL_TITLE_STATECHANGE_TEMPLATE');
		$sTitle = str_replace('<Bestellnummer>', $oOrder->oxorder__oxordernr->value, $sTitle);
		// OXID treats "template_dir" as array, so we add our module folder to get the templates found.
		if ( is_array($oSmarty->template_dir) ) {
			$oSmarty->template_dir[] = dirname(dirname(__FILE__)).'/views/';
		}
		$oMail->setCharSet('utf-8');
		$oMail->setSubject($sTitle);
		$oSmarty->assign('title', $sTitle);
		$oSmarty->assign('sDON', $oOrder->oxorder__oxtransid->value);
		$oSmarty->assign('sOrderNumber', $oOrder->oxorder__oxordernr->value);
		$oSmarty->assign('sNewState', $this->getCreditPlusPaymentStateForHumans($oOrder->oxorder__oxtransstatus->value));
		$oSmarty->assign('sOrderDate', $oOrder->oxorder__oxorderdate->value);
		$oSmarty->assign('oViewConf', $this->getViewConfig());
		$oSmarty->assign('oEmailView', $oMail);
		$sState = $oOrder->oxorder__oxtransstatus->value;
		$sFileHtml = 'email/html/sc_statechange_'.$sState.'.tpl';
		$sFileText = 'email/plain/sc_statechange_'.$sState.'.tpl';
		$sType = 'html';
		$sHtmlText = '';
		$sTextText = '';
		$oConfig = $this->getConfig();
		$sRecipient = $oConfig->getShopConfVar('sStateMail_Recipient_'.substr(md5($sState),0,7),null, 'module:sccp');
		$sRecipientName = $oConfig->getShopConfVar('sStateMail_RecipientName_'.substr(md5($sState),0,7),null, 'module:sccp');
		$oSmarty->assign('sRecipientName', $sRecipientName);
		$sFrom = $oConfig->getActiveShop()->oxshops__oxorderemail->value;
		$sFromName = $oConfig->getActiveShop()->oxshops__oxname->value;
		try {
			$sHtmlText = $oSmarty->fetch($sFileHtml);
			if ( $sHtmlText == '' ) {
				$sType = 'text';
			}
		} catch (\Exception $oEx) {
			$sType = 'text';
		}
		try {
			$sTextText = $oSmarty->fetch($sFileText);
		} catch (\Exception $oEx) {
			// No Text mode available
		}
		if ( $sType == 'html' ) {
			$oMail->setAltBody($sTextText);
		} else {
			$oMail->setBody($sTextText);
		}
		if ( ($sTextText || $sHtmlText) && $sRecipient && $sRecipientName ) {
			if ( $sType == 'html' ) {
				if ( $sHtmlText ) {
					$oMail->setBody($sHtmlText);
					$oMail->setAltBody($sTextText);
				} else {
					$oMail->setBody($sTextText);
				}
			} else {
				$oMail->setBody($sTextText);
			}
			$oMail->setRecipient($sRecipient, $sRecipientName);
			$oMail->setFrom($sFrom, $sFromName);
			$oMail->send();
		}
	}

	/**
	 * @param WebshopContract $oContractData
	 * @return string
	 */
	protected function translatePaymentState( $oContractData ) {
		$sPaymentState = $this->getCreditPlusPaymentState($oContractData->state, $oContractData->finallyApproved, $oContractData->deliveryDone, $oContractData->informationRequest);
		return $sPaymentState;
	}

	/**
	 * @param int $iStateNumber
	 * @param bool $bFinallyApproved
	 * @param bool $bDeliveryDone
	 * @param bool $bRequestInformation
	 *
	 * @return string
	 */
	protected function getCreditPlusPaymentState( $iStateNumber, $bFinallyApproved = false, $bDeliveryDone = false, $bRequestInformation = false ) {
		$sStatus = 'review_necessary';
		if ( $iStateNumber == 20 ) {
			$sStatus = 'creditplus_referred';
		} elseif ( $iStateNumber == 24 ) {
			$sStatus = 'creditplus_accepted';
			if ( $bFinallyApproved == false ) {
				// Already set above
			} elseif ( ($bFinallyApproved == true) && ($bDeliveryDone == true) ) {
				$sStatus = 'creditplus_approved_and_sent';
			} elseif ( $bFinallyApproved == true ) {
				$sStatus = 'creditplus_approved';
			}
		} elseif ( $iStateNumber == 21 ) {
			$sStatus = 'creditplus_docs_received';
		} elseif ( $iStateNumber == 22 ) {
			$sStatus = 'creditplus_docs_received';
		} elseif ( $iStateNumber == 23 ) {
			$sStatus = 'creditplus_docs_received';
		} elseif ( $iStateNumber == 25 ) {
			$sStatus = 'creditplus_docs_received';
		} elseif ( $iStateNumber == 32 ) {
			$sStatus = 'creditplus_processing_payment';
		} elseif ( $iStateNumber == 92 ) {
			$sStatus = 'creditplus_declined_soft';
		} elseif ( $iStateNumber == 93 ) {
			$sStatus = 'creditplus_declined_hard';
		} elseif ( $iStateNumber == 99 ) {
			$sStatus = 'creditplus_paid';
		} elseif ( $iStateNumber == 95 ) {
			$sStatus = 'creditplus_cancelled';
		} elseif ( $iStateNumber == 94 ) {
			$sStatus = 'creditplus_error';
		}
		return $sStatus;
	}

	/**
	 * @param string $sPaymentState
	 *
	 * @return string
	 */
	protected function getCreditPlusPaymentStateForHumans( $sPaymentState ) {
		/** @var \OxidEsales\Eshop\Core\Language $oLang */
		$oLang = null;
		if ( method_exists('OxidEsales\Eshop\Core\Language', 'getInstance') ) {
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		} else {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		}
		try {
			$sText = $oLang->translateString('SCCP_PAYMENT_STATE_'.$sPaymentState);
		} catch (\Exception $oEx) {
			$sText = "Unübersetzter Status ($sPaymentState)";
		}
		return $sText;
	}
	public function render() {
		$sReturn = parent::render();

		// Only if no function has changed the template
		if ( $sReturn == 'sccp_trigger.tpl' ) {
			if ( !isset($this->_aViewData['iStatusCode']) ) {
				$this->_aViewData['iStatusCode'] = 200;
				$this->_aViewData['sStatusText'] = 'No errors detected.';
			}
			/** @var \OxidEsales\Eshop\Core\Utils $oUtils */
			$oUtils = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Utils::class);
			/** @var \OxidEsales\Eshop\Core\UtilsView $oUtilsView */
			$oUtilsView = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\UtilsView::class);
			$oSmarty = $oUtilsView->getSmarty();
			$oSmarty->assign($this->_aViewData);
			$sText = $oSmarty->fetch($sReturn);

			header('Content-type: text/xml; charset=utf-8');
			$oUtils->showMessageAndExit($sText);
		} else {
			$oConfig = $this->getConfig();
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
		}

		return $sReturn;
	}

	/**
	 * @inheritdoc
	 */
	public function getBreadCrumb() {
		$aPaths = parent::getBreadCrumb();
		if ( !$aPaths ) {
			$aPaths = array();
		}

		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();

		if ( class_exists('OxidEsales\Eshop\Core\Registry') ) {
			/** @var \OxidEsales\Eshop\Core\Language $oLang */
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
			/** @var \OxidEsales\Eshop\Core\SeoEncoder $oSeoEncoder */
			$oSeoEncoder = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\SeoEncoder::class);
		} else {
			/** @var \OxidEsales\Eshop\Core\Language $oLang */
			/** @noinspection PhpUndefinedMethodInspection */
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
			/** @var \OxidEsales\Eshop\Core\SeoEncoder $oSeoEncoder */
			/** @noinspection PhpUndefinedMethodInspection */
			$oSeoEncoder = \OxidEsales\Eshop\Core\SeoEncoder::getInstance();
		}

		$iBaseLanguage = $oLang->getBaseLanguage();
		$sSelfLink = $this->getViewConfig()->getSelfLink();

		$aPath = array();
		$aPath['title'] = $oLang->translateString('PAY', $iBaseLanguage, false);
		$aPath['link'] = $oSeoEncoder->getStaticUrl($sSelfLink.'cl=payment');
		$aPaths[] = $aPath;

		$sFunction = $oRequest->getRequestParameter('fnc');
		$sTransactionMode = $oConfig->getShopConfVar('sTransactionMode', null, 'module:sccp');
		if ( ($sFunction == 'showSccpPopup') || ($sFunction == 'showSccpIframe') ) {
			if ( $sTransactionMode == 'postorder' ) {
				$aPath = array();
				$aPath['title'] = $oLang->translateString('SCCP_FINANCING_BREADCRUMB_PAYPOSTORDER', $iBaseLanguage, false);
				$aPath['link'] = $this->getLink();
				$aPaths[] = $aPath;
			} elseif ( $sTransactionMode == 'inorder' ) {
				$aPath = array();
				$aPath['title'] = $oLang->translateString('SCCP_FINANCING_BREADCRUMB_PAYINORDER', $iBaseLanguage, false);
				$aPath['link'] = $this->getLink();
				$aPaths[] = $aPath;
			}
		} elseif ( $sFunction == 'showSccpFinished' ) {
			$aPath = array();
			$aPath['title'] = $oLang->translateString('SCCP_FINANCING_BREADCRUMB_FINISHED', $iBaseLanguage, false);
			$aPath['link'] = '';
			$aPaths[] = $aPath;
		}


		return $aPaths;
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Trigger::class,'sccp_trigger');
