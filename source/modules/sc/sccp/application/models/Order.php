<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 22.01.16
 * Time: 10:29
 */

namespace Sinkacom\CreditPlusModule\Model;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopContract;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopFinanceArticle;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopVoucher;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopCurrency;
use Sinkacom\CreditPlusModule\Lib\CreditPlusObjects\WebshopRateTableMonthRow;

class Order extends Order_parent {

	/** @var WebshopContract $_oContractData */
	protected $_oContractData = null;

	/** @var array $_aFinancingMonths */
	protected $_aFinancingMonths = null;

	/** @var string $_sFinancingMode */
	protected $_sFinancingMode = null;

	/** @var WebshopFinanceArticle $_oFinancingArticleReference  */
	protected $_oFinancingArticleReference = null;

	/** @var \OxidEsales\Eshop\Core\Model\BaseModel $_oFinanceData */
	protected $_oFinanceData = null;

	/** @var array $_aCreditCancelStates All Credit States in which a cancel action is possible */
	protected $_aCreditCancelStates = array(
		24,
		20,
		21,
		22,
		23,
		25,
	);

	protected $_aCreditReturnStates = array(
		24,
		99,
		32
	);

	/** @var string $sDeliveryAddressHash Transient store for Hash transmitted to "execute" page. */
	public $sDeliveryAddressHash = '';

	/**
	 * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket Basket, which is being converted to order
	 * @param \OxidEsales\Eshop\Application\Model\User $oUser User which has ordered this basket
	 * @param bool $blRecalculatingOrder If the order is being recalculated; defaults to false
	 * @return int Success
	 */
	public function finalizeOrder(\OxidEsales\Eshop\Application\Model\Basket $oBasket, $oUser, $blRecalculatingOrder = false) {
		/** @var \OxidEsales\Eshop\Core\Config $oConfig */
		$oConfig = $this->getConfig();
		/** @var \OxidEsales\Eshop\Application\Model\Shop $oShop */
		$oShop = $oConfig->getActiveShop();
		$sTransactionMode = $oConfig->getShopConfVar('sTransactionMode',$oShop->oxshops__oxid->value,'module:sccp');
		$sPaymentStart = substr($oBasket->getPaymentId(),0,5);
		if ( $sPaymentStart === 'sccp_' ) {
			if ( ( $sTransactionMode == 'inorder' ) && ( $this->getContractData() && $this->getContractData()->dealerOrderNumber ) ) {
				$this->_setNumber();
			}
		}
		$bReturn = parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
		if ( $sPaymentStart === 'sccp_' ) {
			if ( $sTransactionMode == 'postorder' ) {
				$this->_setOrderStatus('PAYMENT_PENDING');
			}
		}
		return $bReturn;
	}

	/**
	 * Performs order cancel process
	 * return void|string[]
	 */
	public function cancelOrder()
	{
		// Not my payment? Not my problem! :)
		if ( substr($this->oxorder__oxpaymenttype->value,0,5) !== 'sccp_' ) { parent::cancelOrder(); return null; }
		$aError = null;
		$oLang = $this->getLang();
		// Only orders, which are not yet cancelled are eligible for API Cancel.
		// So we are going through the uncancelled items and refund them as one call.
		if ( $this->oxorder__oxstorno->value == 0 ) {
			if ( isAdmin() ) {
				$sUserName = substr($this->getSession()->getUser()->oxuser__oxusername->value,0,27);
			} else {
				$sUserName = 'User requested';
			}
			$oConfig = $this->getConfig();
			$aSoapParams = array(
				'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
				'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
				'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
				'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
			);
			$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
			$oWSApi->setDebugState(true,false);

			$sDealerOrderNumber = $this->oxorder__oxtransid->value;

			$oContractData = $this->getContractData($sDealerOrderNumber);

			// If delivery is not yet done, and the order is in state "pending", cancel is possible
			if ( !$oContractData->deliveryDone && (in_array($oContractData->state,$this->_aCreditCancelStates)) ) {

				$aCancelData = array(
					'dealerOrderNumber' => $oContractData->dealerOrderNumber,
					'cancelationFrom' => $sUserName,
					'dealerNumber' => $oConfig->getShopConfVar('sCPDealer',null,'module:sccp')
				);

				$oResponse = $oWSApi->cancelOrderCPWebshop($aCancelData);

				if ( is_object($oResponse) && ( property_exists($oResponse,'confirmation') )  && ( property_exists($oResponse->confirmation,'confirmationItems') )  && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
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
					} elseif ( $iErrorCode == 6 ) {
						// Storno im Status Bezahlt kann nicht stattfinden
					} elseif ( $iErrorCode == 14 ) {
						// Irgendein Pflichtfeld sprengt sein Format/Länge
						// dealerOrderNumber > 40
						// cancelationFrom > 27
					} elseif ( $iErrorCode == 15 ) {
						// Auftragsstatus = Unbekannt
					} elseif ( $iErrorCode == 16 ) {
						// Auftragsstatus = Abgelehnt
					} elseif ( $iErrorCode == 17 ) {
						// Auftragsstatus = Storniert
					} elseif ( $iErrorCode == 18 ) {
						// Auftragsstatus = Wird bezahlt
					} else {
						// Keine Ahnung was passiert ist
					}
				}
			// If delivery has happened, then only refunds are available. Try cancel first though, then use refunds.
			} elseif ( $oContractData->deliveryDone && (in_array($oContractData->state,$this->_aCreditReturnStates)) ) {


				$aCancelData = array(
					'dealerOrderNumber' => $oContractData->dealerOrderNumber,
					'cancelationFrom' => $sUserName,
					'dealerNumber' => $oConfig->getShopConfVar('sCPDealer',null,'module:sccp')
				);

				$oResponse = $oWSApi->cancelOrderCPWebshop($aCancelData);

				if ( is_object($oResponse) && ( property_exists($oResponse,'confirmation') )  && ( property_exists($oResponse->confirmation,'confirmationItems') )  && ( property_exists($oResponse->confirmation->confirmationItems,'errorCode') ) ) {
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
					} elseif ( $iErrorCode == 6 ) {
						// Storno im Status Bezahlt kann nicht stattfinden

						// Collect returnable amount (= price - already returned items)
						$dReturnAmount = floatval($oContractData->price);
						$sDate = date('c');
						foreach ( $oContractData->voucher as $oVoucher ) {
							$dReturnAmount -= floatval($oVoucher->value);
						}
						$oVoucher = new WebshopVoucher($dReturnAmount,'Restbetrag',$sDate,$sUserName,false,$oContractData->id,$sDealerOrderNumber,$oContractData->dealerNumber);
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

							if ( $iErrorCode == 1 ) {
								// Kein Auftrag gefunden
							} elseif ( $iErrorCode == 2 ) {
								// Pflichtfelder fehlen, kann durch Code heraus nicht passieren
							} elseif ( $iErrorCode == 7 ) {
								// Retoure im Status "Nicht bezahlt" kann nicht stattfinden
								// Nicht möglich, da dieser Aufruf nur im Status Bezahlt gemacht wird
							} elseif ( $iErrorCode == 12 ) {
								// Summe der Retouren übersteigt Restwert des Kreditvertrags
							} elseif ( $iErrorCode == 14 ) {
								// Irgendein Pflichtfeld sprengt sein Format/Länge
								// dealerOrderNumber > 40
								// cancelationFrom > 27
							} else {
								// Keine Ahnung was passiert ist
							}
						} else {
							$aError = null;
						}

					} elseif ( $iErrorCode == 14 ) {
						// Irgendein Pflichtfeld sprengt sein Format/Länge
						// dealerOrderNumber > 40
						// cancelationFrom > 27
					} elseif ( $iErrorCode == 15 ) {
						// Auftragsstatus = Unbekannt
					} elseif ( $iErrorCode == 16 ) {
						// Auftragsstatus = Abgelehnt
					} elseif ( $iErrorCode == 17 ) {
						// Auftragsstatus = Storniert
					} elseif ( $iErrorCode == 18 ) {
						// Auftragsstatus = Wird bezahlt
					} else {
						// Keine Ahnung was passiert ist
					}
				}
			} elseif ( ($oContractData->state === '') || ($oContractData->state === 92) || ($oContractData->state === 93) || ($oContractData->state === 95) ) {
				// If it isn't finished on CreditPlus, it is ok to cancel anyway.
				// If it is forbidden to pay with CreditPlus, cancel is also ok.
				parent::cancelOrder();
				$aError = array(
					'sError' => (401),
					'sErrorMessage' => $oLang->translateString('SCCP_CPORDER_DETAILS_ERROR_CANCEL_NOT_AVAILABLE_CREDITPLUS')
				);
			} else {
				// Any other state needs no special treatment
			}
		} else {
			$aError = array(
				'sError' => (400),
				'sErrorMessage' => $oLang->translateString('SCCP_CPORDER_DETAILS_ERROR_ALREADY_CANCELLED_ORDER')
			);
		}
		if ( $aError == null ) {
			// Do the parent call
			parent::cancelOrder();
		}
		return $aError;
	}


	/**
	 * @param string $sDealerOrderNumber Dealer Order Number on CreditPlus API, defaults to $this->oxorder__oxtransid->value
	 * @return WebshopContract
	 */
	public function getContractData($sDealerOrderNumber = '') {
		if ( $this->_oContractData !== null ) { return $this->_oContractData; }
		$oConfig = $this->getConfig();
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSApi = new CreditPlusWebshopAPI($aSoapParams);
		$oWSApi->setDebugState(true, false);

		if ( $sDealerOrderNumber == '' ) {
			$sDealerOrderNumber = $this->oxorder__oxtransid->value;
		}

		$oCreditOfferResponse = $oWSApi->getContractsCPWebshop( array('dealerOrderNumber' => array($sDealerOrderNumber), 'dealerNumber' => $oConfig->getShopConfVar('sCPDealer',null,'module:sccp') ) );
		// $oCreditOfferResponse = $oWSApi->getContractsCPWebshop( array() );
		if ( !is_object($oCreditOfferResponse) ) {
			$oCreditOfferResponse = new \stdClass();
			$oCreditOfferResponse->return = array( 0 => false );
		}
		if ( !is_array($oCreditOfferResponse->return) ) {
			$oCreditOfferResponse->return = array($oCreditOfferResponse->return);
		}

		if ( !$oCreditOfferResponse->return[0] ) {
			$this->_oContractData = new WebshopContract();
			// An error has occured
			$this->_oContractData->iError = 13021;
		} else {
			$this->_oContractData = WebshopContract::fromObject($oCreditOfferResponse->return[0]);
		}

		return $this->_oContractData;
	}

	/**
	 * Clears the cache created by any request
	 * Used only once in \Sinkacom\CreditPlusModule\Model\Paymentgateway
	 * @see \Sinkacom\CreditPlusModule\Model\Paymentgateway::executePayment
	 */
	public function clearContractDataCache() {
		$this->_oContractData = null;
	}



	/**
	 * Takes current order and finds out which article provides the financing options.
	 * Returns empty array if no options exist or array of WebshopRateTableMonthRow if options exist.
	 * Most of the calculation is done in \Sinkacom\CreditPlusModule\Model\Article::getSccpFinancingMonths()
	 * @return array|WebshopRateTableMonthRow[]
	 * @see \Sinkacom\CreditPlusModule\Model\Basket::getFinancingMonths()
	 */
	public function getFinancingMonths() {
		$oConfig = $this->getConfig();
		$sBasketStrategy = $oConfig->getShopConfVar('sBasketFinancingMode', null, 'module:sccp');
		// If rules have not changed, return cached months
		if ( ($this->_sFinancingMode === $sBasketStrategy) && ($this->_aFinancingMonths !== null) ) {
			return $this->_aFinancingMonths;
		}
		$aWebshopFinancingMonths = array();
		/** @var \Sinkacom\CreditPlusModule\Model\OrderArticle[] $aOrderItems */
		$aOrderItems = $this->getOrderArticles();

		if ( $aOrderItems ) {
			$aSoapParams = array(
				'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
				'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
				'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
				'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
			);
			$dBasketPrice = $this->oxorder__oxtotalordersum->value;
			$dMinRate = floatval($oConfig->getShopConfVar('sMinRate', null, 'module:sccp'));
			$oWebshopArticle = $this->getFinancingArticleReference($aOrderItems);
			if ( $oWebshopArticle === null ) {
				$this->_aFinancingMonths = array();
				$this->_sFinancingMode = $sBasketStrategy;
				return $this->_aFinancingMonths;
			}
			/** @var \Sinkacom\CreditPlusModule\Model\Article $oOrderArticle */
			$oOrderArticle = $aOrderItems[$oWebshopArticle->id]->getArticle();
			$aWebshopFinancingMonths = $oOrderArticle->getSccpFinancingMonths($dMinRate, $dBasketPrice);
			$this->_sFinancingMode = $sBasketStrategy;
		}
		return $this->_aFinancingMonths = $aWebshopFinancingMonths;
	}

	/**
	 * @param \Sinkacom\CreditPlusModule\Model\OrderArticle[] $aOrderItems
	 * @return WebshopFinanceArticle
	 */
	public function getFinancingArticleReference( $aOrderItems = null ) {
		$oConfig = $this->getConfig();
		$sBasketStrategy = $oConfig->getShopConfVar('sBasketFinancingMode', null, 'module:sccp');
		// If rules have not changed, return cached article
		if ( ($this->_sFinancingMode === $sBasketStrategy) && ($this->_oFinancingArticleReference !== null) ) {
			return $this->_oFinancingArticleReference;
		}

		if ( $aOrderItems === null ) {
			$aOrderItems = $this->getOrderArticles();
		}
		$aSoapParams = array(
			'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
			'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
			'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
			'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
		);
		$oWSAPI = new CreditPlusWebshopAPI($aSoapParams);
		$aWebshopArticles = array();

		$sBasketStrategy = $oConfig->getShopConfVar('sBasketFinancingMode', null, 'module:sccp');
		$oCurrency = $this->getOrderCurrency();
		$oWSCurrency = WebshopCurrency::fromArray(array(
			'decimals' => $oCurrency->decimal,
			'decimalSeparator' => $oCurrency->dec,
			'side' => $oCurrency->side,
			'sign' => $oCurrency->sign,
			'thousandSeparator' => $oCurrency->thousandSeparator,
			'name' => $oCurrency->name,
		));
		$dMinRate = floatval($oConfig->getShopConfVar('sMinRate', null, 'module:sccp'));
		$dOrderPrice = $this->oxorder__oxtotalordersum->value;
		/** @var \Sinkacom\CreditPlusModule\Model\Article[] $aOrderArticles */
		$aOrderArticles = array();
		foreach ( $aOrderItems as $sItemKey => $oOrderItem ) {
			/** @var \Sinkacom\CreditPlusModule\Model\Article $oOrderArticle */
			$oOrderArticle = $oOrderItem->getArticle();
			$aOrderArticles[$sItemKey] = $oOrderArticle;
			$aWebshopArticle = array(
				'id' => $sItemKey,
				'amount' => $oOrderItem->oxorderarticles__oxamount->value,
				'unitprice' => $oOrderItem->getBasePrice()->getBruttoPrice()
			);
			$aMonths = $oOrderArticle->getSccpFinancingMonths($dMinRate, $dOrderPrice);
			if ( !$aMonths ) {
				// One of the articles has no financing options, so this will not be offered for the order
				// Return null and handle it in the calling function
				return null;
			}
			foreach ( $aMonths as $oMonthRow ) {
				$dInterestRate = $oWSAPI->retrieveFloatFromFormattedInterestRate($oMonthRow->interestRate, $oWSCurrency);
				if ( !isset($aWebshopArticle['cheapestInterestRate']) || ($dInterestRate < $aWebshopArticle['cheapestInterestRate']) ) {
					$aWebshopArticle['cheapestInterestRate'] = $dInterestRate;
					$aWebshopArticle['productTypeID'] = $oMonthRow->productTypeID;
					$aWebshopArticle['productClassID'] = $oMonthRow->productClassID;
				}
				if ( !isset($aWebshopArticle['mostExpensiveInterestRate']) || ($dInterestRate > $aWebshopArticle['mostExpensiveInterestRate']) ) {
					$aWebshopArticle['mostExpensiveInterestRate'] = $dInterestRate;
				}
			}
			$oWebshopArticle = new WebshopFinanceArticle($aWebshopArticle);
			$aWebshopArticles[$sItemKey] = $oWebshopArticle;
		}
		$oWebshopArticle = $oWSAPI->getFinancingArticleReference($aWebshopArticles, $sBasketStrategy);
		$this->_oFinancingArticleReference = $oWebshopArticle;
		return $this->_oFinancingArticleReference;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get( $sName ) {
		switch ($sName) {
			case 'sccp_oxorder_finance__oxid':
				return $this->getSccpOxorderFinance()->sccp_oxorder_finance__oxid;
				break;
			case 'sccp_oxorder_finance__sccp_order_link':
				return $this->getSccpOxorderFinance()->sccp_oxorder_finance__sccp_order_link;
				break;
			case 'sccp_oxorder_finance__sccp_linkgen_timestamp':
				return $this->getSccpOxorderFinance()->sccp_oxorder_finance__sccp_linkgen_timestamp;
				break;
		}
		return parent::__get($sName);
	}

	/**
	 * @return oxBase
	 */
	public function getSccpOxorderFinance() {
		if ( $this->_oFinanceData !== null ) {
			return $this->_oFinanceData;
		}
		/** @var \OxidEsales\Eshop\Core\Model\BaseModel $oSccpOxorderFinance */
		$oSccpOxorderFinance = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
		$oSccpOxorderFinance->init('sccp_oxorder_finance');
		if ( !$oSccpOxorderFinance->load($this->oxorder__oxid->value) ) {
			$oSccpOxorderFinance = $this->setSccpOxorderFinance('',null);
		}
		return $this->_oFinanceData = $oSccpOxorderFinance;
	}

	/**
	 * @param string $sURL
	 * @param int $iTimestamp
	 * @return \OxidEsales\Eshop\Core\Model\BaseModel
	 */
	public function setSccpOxorderFinance($sURL = '', $iTimestamp = null) {
		if ( $this->_oFinanceData ) {
			$oFinanceData = $this->_oFinanceData;
		} else {
			/** @var \OxidEsales\Eshop\Core\Model\BaseModel $oFinanceData */
			$oFinanceData = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
			$oFinanceData->init('sccp_oxorder_finance');
			$oFinanceData->setId($this->oxorder__oxid->value);
		}
		$oFinanceData->sccp_oxorder_finance__sccp_order_link = new \OxidEsales\Eshop\Core\Field($sURL, \OxidEsales\Eshop\Core\Field::T_RAW);
		$oFinanceData->sccp_oxorder_finance__sccp_linkgen_timestamp = new \OxidEsales\Eshop\Core\Field($iTimestamp, \OxidEsales\Eshop\Core\Field::T_RAW);
		$oFinanceData->save();
		$this->_oFinanceData = $oFinanceData;
		return $this->_oFinanceData;
	}

	/**
	 * @return oxLang Language Object
	 */
	protected function getLang() {
		/** @var \OxidEsales\Eshop\Core\Language $oLang */
		$oLang = null;

		if ( class_exists('OxidEsales\\Eshop\\Core\\Registry') ) {
			$oLang = \OxidEsales\Eshop\Core\Registry::getLang();
		} elseif ( method_exists('OxidEsales\\Eshop\\Core\\Language', 'getInstance') ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$oLang = \OxidEsales\Eshop\Core\Language::getInstance();
		}

		return $oLang;
	}
}

//class_alias(\Sinkacom\CreditPlusModule\Model\Order::class,'sccp_oxorder');

// IDE Helper
if ( false ) {
	/*
	class Order_parent extends \OxidEsales\Eshop\Application\Model\Order {

	}
	*/
}
