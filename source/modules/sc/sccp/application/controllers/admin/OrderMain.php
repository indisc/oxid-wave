<?php

/**
 * Created by PhpStorm.
 * User: mihovil.bubnjar
 * Date: 22.01.16
 * Time: 09:43
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;
use Sinkacom\CreditPlusModule\Lib\Controller\CreditPlusWebshopAPI;

class OrderMain extends OrderMain_parent {

	/**
	 * Sends order.
	 * Extended by commitDeliveryCPWebshop
	 * @see order_main::sendorder()
	 * @see CreditPlusWebshopAPI::commitDeliveryCPWebshop()
	 */
	public function sendorder()
	{
		$soxId = $this->getEditObjectId();
		/** @var oxOrder $oOrder */
		$oOrder = oxNew(\OxidEsales\Eshop\Application\Model\Order::class);
		if ($oOrder->load($soxId)) {
			parent::sendorder();

			include_once(dirname(dirname(dirname(dirname(__FILE__)))).'/lib/createCreditOffer.php');
			$oConfig = $this->getConfig();
			$aSoapParams = array(
				'soap-user' => $oConfig->getShopConfVar('sSoapUser',null,'module:sccp'),
				'soap-pass' => $oConfig->getShopConfVar('sSoapPass',null,'module:sccp'),
				'soap-type' => $oConfig->getShopConfVar('sSoapType',null,'module:sccp'),
				'wsdl' => $oConfig->getShopConfVar('sWSDL',null,'module:sccp')
			);
			$oWSApi = new CreditPlusWebshopAPI($aSoapParams);

			$aData = array(
				'dealerNumber' => $oConfig->getShopConfVar('sCPDealer',null,'module:sccp'),
				'dealerOrderNumber' => $oOrder->oxorder__oxtransid->value,
				'invoiceNumber' => $oOrder->oxorder__oxordernr->value,
				'invoicePrice' => $oOrder->oxorder__oxordersum->value,
				'deliveryDate' => date('c')
			);

			// $oCreditOfferResponse = $oWSApi->getContractsCPWebshop( array('dealerOrderNumber' => array($oOrder->oxorder__oxtransid->value) ) );
			$oWSApi->commitDeliveryCPWebshop( $aData );

		}
	}
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\OrderMain::class,'sccp_order_main');
if ( false ) {
	class OrderMain_parent extends \OxidEsales\Eshop\Application\Controller\Admin\OrderMain {

	}
}
