<?php

namespace Sinkacom\CreditPlusModule\Controller;

class Order extends Order_parent {
	protected $_sFinishAction = 'cl=order&fnc=execute';
	protected $_sDemoFinishAction = 'cl=order&fnc=showSccpFinished';

	/**
	 * Wenn Bezahlung per sccp_financing kommt, Post Daten prüfen.
	 * @return String Template Filename
	 */
	public function render() {
		$oPayment = $this->getPayment();
		if ( $oPayment && ($oPayment->oxpayments__oxid->value == 'sccp_financing') ) {
			/** @var $oConfig \OxidEsales\Eshop\Core\Config */
			$oConfig = $this->getConfig();
			$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
			$bGotoNewAction = false;
			$aParams = array();
			if ( $oRequest->getRequestParameter('data') && $oRequest->getRequestParameter('signature') ) {
				/** @see \Sinkacom\CreditPlusModule\Model\Paymentgateway::getTargetURL for reverse order of functions applied to $_GET['data'] */
				$_POST['params'] = unserialize(gzinflate(base64_decode($oRequest->getRequestParameter('data',true))));
				$aParams = $_POST['params'];

			}
			// Wenn aParams nicht leer ist, durchgehen und neue Action aufrufen
			if ( $aParams ) {
				foreach ( $aParams as $sKey => $sValue ) {
					$_POST[$sKey] = $sValue;
				}
				$bGotoNewAction = true;
			}
			if ( $bGotoNewAction ) {
				$sNewAction = $this->execute();
				if ( $sNewAction ) {
					$this->_executeNewAction( $sNewAction );
				}
			}
		}
		return parent::render();
	}


	public function restartSccpOrder() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sTransID = $oRequest->getRequestParameter('don');
		$sOxid = $oRequest->getRequestParameter('oxid');
		if ( $sOxid && $sTransID ) {
			/** @var \OxidEsales\Eshop\Application\Model\Order $oOrder */
			$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);

			if ( $oOrder->load($sOxid) ) {
				if ( $oOrder->oxorder__oxtransid->value == $sTransID ) {
					/** @var \OxidEsales\Eshop\Application\Model\Basket $oBasketNew */
					$oBasketNew = oxNew(\OxidEsales\Eshop\Application\Model\Basket::class);
					/** @var \OxidEsales\Eshop\Application\Model\OrderArticle[] $oOrderArticles */
					$oOrderArticles = $oOrder->getOrderArticles();
					foreach ( $oOrderArticles as $oOrderArticle ) {
						$sArtOxid = $oOrderArticle->oxorderarticle__oxartid->value;
						$dAmount = $oOrderArticle->oxorderarticle__oxamount->value;
						$aSels = $oOrderArticle->getOrderArticleSelectList();
						$aPersParams = $oOrderArticle->getPersParams();
						$oBasketNew->addToBasket($sArtOxid,$dAmount,$aSels,$aPersParams);
					}
					$oBasketNew->calculateBasket(true);
					$this->getSession()->setBasket($oBasketNew);
					$oOrder->cancelOrder();
				}
			}
		}
	}

	/**
	 * Wenn der Weg über iFrame gewählt wird, dann einfach nur die URL mit htmlentities encoden und das iframe Tempalte ausgeben.
	 */
	public function showSccpIframe () {
		$oSession = $this->getSession();
		$this->_aViewData['sTargetURL'] = htmlentities($oSession->getVariable('sTargetURL'));
		$this->_sThisTemplate = 'sccp_checkout_iframed.tpl';
	}

	/**
	 * Wenn der Weg über Popup gewählt wird, dann einfach nur die URL mit htmlentities encoden und das popup Tempalte ausgeben.
	 */
	public function showSccpPopup () {
		$oSession = $this->getSession();
		$this->_aViewData['sTargetURL'] = htmlentities($oSession->getVariable('sTargetURL'));
		$this->_sThisTemplate = 'sccp_checkout_popup.tpl';
	}

	/**
	 * Wenn alles abgelaufen ist, Weiterleitungsseite zum internen Ziel anzeigen
	 */
	public function showSccpFinished() {
		$oConfig = $this->getConfig();
		$this->_aViewData['sTargetURL'] = $oConfig->getShopSecureHomeUrl().$this->_sFinishAction;
		$this->_aViewData['sHiddenFields'] = $this->getviewConfig()->getNavFormParams();
		$this->_aViewData['sParams'] = htmlentities(base64_decode($_POST['params']));
		$this->_aViewData['sSignature'] = htmlentities($_POST['signature']);
		$this->_sThisTemplate = 'sccp_checkout_finished.tpl';
	}

	/**
	 * Demo Modus - Wenn im Backend Testmodus aktiviert wird, ohne CreditPlus arbeiten.
	 */
	public function showSccpDemo() {
		$oConfig = $this->getConfig();
		$oRequest = \OxidEsales\Eshop\Core\Registry::getRequest();
		$sParams = base64_decode($oRequest->getRequestParameter('params'));
		$sSignature = base64_decode($oRequest->getRequestParameter('signature'));
		$sKeyFile = $oConfig->getConfigParam('sShopDir').'modules/sc/sccp/ssl/demo_sccp.key';
		$sCertFile = $oConfig->getConfigParam('sShopDir').'modules/sc/sccp/ssl/demo_sccp.crt';

		$bValidRequest = $this->verifyRemoteSignature($sParams,$sSignature,$sCertFile);
		$this->_aViewData['sTargetURL'] = $oConfig->getShopSecureHomeUrl().$this->_sDemoFinishAction;

		if ( $bValidRequest ) {
			$sParamsNew = $sParams;
			$sParamsNew .= '&bankProcessed=1';
			$sParamsEncoded = base64_encode($sParamsNew);
			$sMySignature = $this->createLocalSignature($sParamsNew,$sKeyFile,'sha512');
			$this->_aViewData['sSignature'] = $sMySignature;
			$this->_aViewData['sParams'] = $sParamsEncoded;
		} else {
			$this->_aViewData['bError'] = true;
			$this->_aViewData['sError'] = 'Signaturfehler';
		}
		$this->_sThisTemplate = 'sccp_demo.tpl';
	}


	/**
	 * Only copied for TEST purposes, the real action happens in \Sinkacom\CreditPlusModule\Model\Paymentgateway
	 * @param string $sSignedData Data to sign
	 * @param string $sKeyFile Full path on local machine
	 * @param string $sAlgorithm Hashing Algorithm - usually sha512
	 * @return bool|string False on error, string on success
	 */
	protected function createLocalSignature($sSignedData, $sKeyFile, $sAlgorithm) {
		if (!is_file($sKeyFile)) {
			$this->_sLastError = 'Technischer Fehler (#41)';
			$this->_iLastErrorNo = 41;
			return false;
		}
		$sSignature = '';
		$sKeyData = file_get_contents($sKeyFile);
		/** @noinspection PhpVoidFunctionResultUsedInspection */
		$oKey = openssl_get_privatekey($sKeyData);
		openssl_sign($sSignedData, $sSignature, $oKey, $sAlgorithm);

		return base64_encode($sSignature);
	}

	protected function verifyRemoteSignature($sData, $sSignature, $sKeyFile) {
		if ( !is_file($sKeyFile) ) {
			$this->_sLastError = 'Technischer Fehler (#42)';
			$this->_iLastErrorNo = 42;
			return false;
		}

		$sPubKey = file_get_contents($sKeyFile);
		/** @noinspection PhpVoidFunctionResultUsedInspection */
		$oPubKey = openssl_get_publickey($sPubKey);

		return openssl_verify($sData, base64_decode(rawurldecode($sSignature)), $oPubKey, "sha512");
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Order::class,'sccp_order');

if ( false ) {
	class Order_parent extends order {
		// Dummy for PHPStorm
	}
}
