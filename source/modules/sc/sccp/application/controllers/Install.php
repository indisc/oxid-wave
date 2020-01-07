<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 17.12.15
 * Time: 16:06
 */

namespace Sinkacom\CreditPlusModule\Controller;

//class sccp_install extends oxUBase {
use OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface;

class Install extends \OxidEsales\Eshop\Application\Controller\FrontendController {

	protected $_aTextFields = array(
		// Fields for Content
		'OXCONTENT',
		'OXCONTENT_1',
		'OXCONTENT_2',
		'OXCONTENT_3',
		'OXTITLE',
		'OXTITLE_1',
		'OXTITLE_2',
		'OXTITLE_3',
		// Fields for Payment
		'OXLONGDESC',
		'OXLONGDESC_1',
		'OXLONGDESC_2',
		'OXLONGDESC_3',
		'OXDESC',
		'OXDESC_1',
		'OXDESC_2',
		'OXDESC_3',
		'OXVALDESC',
		'OXVALDESC_1',
		'OXVALDESC_2',
		'OXVALDESC_3',
	);
	
	protected $_sThisTemplate = 'sccp_install.tpl';

	public function render() {
		$sReturn = parent::render();
		$this->_aViewData['bSuccess'] = true;
		$this->_aViewData['sSuccess'] = false;
		$this->_aViewData['sError'] = '';

		$oConf = $this->getConfig();

		$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

		$this->createRateTable($oConf,$oDB);
		$this->createOrUpdateSccpOfferedOption($oDB);
		$this->createOrUpdateSccpOfferedOptionProdgroup($oDB);
		$this->createOrUpdateSccpProdgroup($oDB);
		$this->createOrUpdateSccpProdgroupArticle($oDB);
		$this->createOrUpdateSccpOrderFinance($oDB);

		$aFinancingPayment = array(
			'OXID' => 'sccp_financing',
			'OXACTIVE' => '0',
			'OXDESC' => 'CreditPlus Finanzierung',
			'OXADDSUM' => 0,
			'OXADDSUMTYPE' => 'ABS',
			'OXADDSUMRULES' => 0,
			'OXFROMBONI' => '624',
			'OXFROMAMOUNT' => 150.00,
			'OXTOAMOUNT' => 40000.00,
			'OXVALDESC' => '',
			'OXCHECKED' => 0,
			'OXDESC_1' => 'CreditPlus Financing',
			'OXVALDESC_1' => '',
			'OXDESC_2' => 'CreditPlus Financement',
			'OXVALDESC_2' => '',
			'OXDESC_3' => '',
			'OXVALDESC_3' => '',
			'OXLONGDESC' => 'Bezahlen Sie Ihren Einkauf schnell und bequem über monatlich niedrige Raten mit der CreditPlus Finanzierung.',
			'OXLONGDESC_1' => 'Pay for your basket quick and easy with low monthly rates through CreditPlus financing.',
			'OXLONGDESC_2' => 'Financiez votre panier rapidement et simple par versements mensuels bas avec le CreditPlus financement.',
			'OXLONGDESC_3' => '',
			'OXSORT' => 0,
			'OXTSPAYMENTID' => '',
			'OXTIMESTAMP' => '2017-12-13 14:28:25'
		);
		$this->makePaymentElement('sccp_financing', $aFinancingPayment, 'Zahlungsart existiert bereits!', 'Zahlungsart erfolgreich installiert!', 'Zahlungsart ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccppangvtext',
			'OXLOADID' => 'sccppangvtext',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Text nach § 6a PAngV',
			'OXTITLE_1' => 'Text according to § 6a PAngV',
			'OXTITLE_2' => 'Texte par § 6a PAngV',
			'OXTITLE_3' => '',
			'OXCONTENT' => 'Kaufpreis entspricht Nettodarlehensbetrag. Diese Angaben stellen zugleich das 2/3-Beispiel gemäß § 6a Abs. 4 PAngV dar. Kreditvermittlung erfolgt alleine für die CreditPlus Bank AG, Augustenstraße 7, 70178 Stuttgart. Bonität vorausgesetzt.<br /><br />Gilt nur für ausgewählte Produkte.',
			'OXCONTENT_1' => 'Buying price equals the netto loan. These information equal the 2/3-example in the sense of § 6a para. 4 PAngV. All contracts are solely transferred to CreditPlus Bank AG, Augustenstraße 7, 70178 Stuttgart.<br /><br />Only available for certain products.',
			'OXCONTENT_2' => 'Prix d\'achête est le montant net du prêt. Cette information et aussi l\'exemple 2/3 au sens de § 6a par. 4 PAngV. Médiation seulement pour la CreditPlus Bank AG, Augustenstraße 7, 70178 Stuttgart.<br /><br />Seulement disponible pour produîts specifiés.',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 12:39:39'
		);
		$this->makeContentElement('sccppangvtext',$aContent,'PAngV Text existiert bereits!','PAngV Text wurde angelegt!','PAngV Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpbaskettop',
			'OXLOADID' => 'sccpbaskettop',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Finanzierung Ihres Warenkorbs',
			'OXTITLE_1' => 'Financing your basket',
			'OXTITLE_2' => 'Financier votre panier',
			'OXTITLE_3' => '',
			'OXCONTENT' => 'Bezahlen Sie Ihren Einkauf schnell und bequem über monatlich niedrige Raten mit der CreditPlus Finanzierung. Einfach CreditPlus Finanzierung als Zahlungsart bei der Bestellung auswählen.',
			'OXCONTENT_1' => 'Pay for your basket quick and easy with low monthly rates through CreditPlus financing. Simply choose CreditPlus financing as your payment method.',
			'OXCONTENT_2' => 'Financiez votre panier rapidement et simple par versements mensuels bas avec le CreditPlus financement. Seulement choisez CreditPlus financement pour votre mode de paiement.',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 12:39:51'
		);
		$this->makeContentElement('sccpbaskettop', $aContent, 'Warenkorb Finanzierungstext existiert bereits!', 'Warenkob Finanzierungstext wurde angelegt!', 'Warenkorb Finanzierungstext ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpabortinfo',
			'OXLOADID' => 'sccpabortinfo',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Finanzierung abgebrochen',
			'OXTITLE_1' => 'Financing cancelled',
			'OXTITLE_2' => 'Financement annulé',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Der Finanzierungsantrag wurde von Ihnen abgebrochen. Sie können die Finanzierung jederzeit <a href="http://retry.me/" style="text-decoration: underline;">hier</a> abschließen oder <a href="http://replace.me/" style="text-decoration: underline;">die Zahlungsart ändern</a>.</p>',
			'OXCONTENT_1' => '<p>You have cancelled the financing process. You can of course finish this later by using the <a href="http://retry.me/" style="text-decoration: underline;">link to the payment page</a> or you can cancel the order and <a href="http://replace.me/" style="text-decoration: underline;">reorder using another payment method</a>.</p>',
			'OXCONTENT_2' => '<p>Vous avez choisi de ne pas compléter le financement. Vous pouvez le compléter plus tard par cliquer sur la <a href="http://retry.me/" style="text-decoration: underline;">page de paiement</a> ou vous pouvez anuller votre achat et <a href="http://replace.me/" style="text-decoration: underline;">choisir une autre mode de paiement</a>.',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 12:39:58'
		);
		$this->makeContentElement('sccpabortinfo', $aContent, '"Finanizierung abgebrochen" Text existiert bereits!', '"Finanzierung abgebrochen" Text wurde angelegt!', '"Finanzierung abgebrochen" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpabortpaid',
			'OXLOADID' => 'sccpabortpaid',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Zahlungsart ändern nicht möglich',
			'OXTITLE_1' => 'Changing payment method not possible',
			'OXTITLE_2' => 'Changer le mode de paiement n\'est pas possible',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Eine Änderung der Zahlungsgsart ist aktuell nicht mehr möglich. Bitte setzen Sie sich mit uns in Verbindung.</p>',
			'OXCONTENT_1' => '<p>It is not possible to change the payment method by yourself any more. Please contact us if you wish to change your payment method.</p>',
			'OXCONTENT_2' => '<p>Il n\'est plus possible de changer le mode de paiement. Contactez-nous si vous le voulez changer.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 13:13:21'
		);
		$this->makeContentElement('sccpabortpaid', $aContent, '"Zahlungsart ändern nicht möglich" Text existiert bereits!', '"Zahlungsart ändern nicht möglich" Text wurde angelegt!', '"Zahlungsart ändern nicht möglich" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpabortmismatch',
			'OXLOADID' => 'sccpabortmismatch',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Bestellung nicht gefunden',
			'OXTITLE_1' => 'Order not found',
			'OXTITLE_2' => 'Commande non trouvé',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Ihre Bestellung konnte leider nicht gefunden werden.<br /><br />Bitte setzen Sie sich mit uns in Verbindung um den Vorgang fortzusetzen.</p>',
			'OXCONTENT_1' => '<p>Your order could not be found.<br /><br />Please contact us to continue with the process.</p>',
			'OXCONTENT_2' => '<p>Votre commande ne peut pas être trouvée.<br /><br />Contactez-nous pour avancer avec la commande.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 13:12:15'
		);
		$this->makeContentElement('sccpabortmismatch', $aContent, '"Bestellung nicht gefunden" Text existiert bereits!', '"Bestellung nicht gefunden" Text wurde angelegt!', '"Bestellung nicht gefunden" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccporderrestart',
			'OXLOADID' => 'sccporderrestart',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Stornieren und neu bestellen',
			'OXTITLE_1' => 'Cancel and reorder',
			'OXTITLE_2' => 'Recommencer la commande',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Sie möchten eine andere Zahlungsart wählen? Wir werden Ihre Bestellung mit der gewählten Zahlungsart Finanzierung stornieren und eine neue Bestellung durchführen. Folgende Artikel sind in Ihrem Warenkorb. Möchten Sie die Zahlart ändern, klicken Sie bitte auf den nachfolgend aufgeführten Button.</p>',
			'OXCONTENT_1' => '<p>Did you decide to switch to another payment method. We will cancel your order with te selected payment method "financing" and start a new order. The following articles are in your basket. If you want to switch to another payment method, please click on the following button.</p>',
			'OXCONTENT_2' => '<p>Avez-vous choisi de changer le mode de paiement? Nous annulerons votre commande avec le mode "financier" et vous pouvez placer un nouvelle commande avec les mêmes articles. Les articles suivantes sont dans votre panier. Si vous êtes sûr, vouz pouvez cliquer le bouton au bas de la page.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 13:12:29'
		);
		$this->makeContentElement('sccporderrestart', $aContent, '"Stornieren und neu bestellen" Text existiert bereits!', '"Stornieren und neu bestellen" Text wurde angelegt!', '"Stornieren und neu bestellen" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpeverythingfinished',
			'OXLOADID' => 'sccpeverythingfinished',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Finanzierung abgeschlossen',
			'OXTITLE_1' => 'Financing finished',
			'OXTITLE_2' => 'Financement fini',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Vielen Dank für Ihre Finanzierungsanfrage! Damit Sie Ihre Ware schnellstmöglich erhalten, folgen Sie bitte den Anweisungen der CreditPlus Bank.<br />Bitte beachten Sie, dass der Warenversand erst nach Eingang aller erforderlichen Unterlagen bei der CreditPlus Bank erfolgen kann.</p>',
			'OXCONTENT_1' => '<p>Thank you for your decision to finance your basket! To get your articles in the fastest way possible, please follow the instructions given by the CreditPlus Bank.<br />Keep in mind that the items will only be shipped after all necessary documents have been received by the CreditPlus Bank.</p>',
			'OXCONTENT_2' => '<p>Merci pour choisir de financier votre panier! Pour reçevoir votre produîts le plus rapide, suivez les diréctions de la CreditPlus Bank.<br />Gardez à l\'ésprit que la livraison de produîts peut seulement commencer quand les documents necessaires arrivent à la CreditPlus Bank.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-03-30 17:27:18'
		);
		$this->makeContentElement('sccpeverythingfinished', $aContent, '"Finanzierung abgeschlossen" Text existiert bereits!', '"Finanzierung abgeschlossen" Text wurde angelegt!', '"Finanzierung abgeschlossen" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccptechnicalerror',
			'OXLOADID' => 'sccptechnicalerror',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Technischer Fehler',
			'OXTITLE_1' => 'Technical error',
			'OXTITLE_2' => 'Érreur technique',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Bei der Übertragung der Finanzierung an die CreditPlus Bank ist leider ein technischer Fehler aufgetreten.<br /><br />Bitte kontaktieren Sie uns, um den Vorgang abzuschließen.</p>',
			'OXCONTENT_1' => '<p>We are sorry but the transmission of your request to the CreditPlus Bank has resulted in an error.<br /><br />Please contact us by using our contact form or calling us.</p>',
			'OXCONTENT_2' => '<p>Nous sommes désolés mais votre financement ne peut pas être transféré à CreditPlus.<br /><br />S\'il vous plaît contactez-nous par la formulaire du contact ou par notre numéro de téléphone.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 13:12:43'
		);
		$this->makeContentElement('sccptechnicalerror', $aContent, '"Technischer Fehler" Text existiert bereits!', '"Technischer Fehler" Text wurde angelegt!', '"Technischer Fehler" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpalreadyfinished',
			'OXLOADID' => 'sccpalreadyfinished',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Bereits abgeschlossen',
			'OXTITLE_1' => 'Already finished',
			'OXTITLE_2' => 'Déjà fini',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Sie haben die Finanzierung bereits abgeschlossen. Wenn Sie eine erneute Zusendung der Finanzierungsunterlagen wünschen kontaktieren Sie bitte die Servicehotline der CreditPlus Bank unter 069 / 17 087 - 407.</p>',
			'OXCONTENT_1' => '<p>You have already gone through the financing process. If you would like to receive another copy of the documents, please contact the CreditPlus Bank service desk by +49 69 17 087 - 407.</p>',
			'OXCONTENT_2' => '<p>Vous avez déjà fini avec votre financement. Si vous voulez recevoir les documents encore une fois, contactez le sérvice de CreditPlus par +49 69 17 087 - 407.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 13:12:50'
		);
		$this->makeContentElement('sccpalreadyfinished', $aContent, '"Bereits abgeschlossen" Text existiert bereits!', '"Bereits abgeschlossen" Text wurde angelegt!', '"Bereits abgeschlossen" Text ließ sich nicht anlegen!');

		$aContent = array(
			'OXID' => 'sccpnoarticlesleft',
			'OXLOADID' => 'sccpnoarticlesleft',
			'OXSHOPID' => $oConf->getActiveShop()->getId(),
			'OXSNIPPET' => '1',
			'OXTYPE' => '0',
			'OXACTIVE' => '1',
			'OXACTIVE_1' => '1',
			'OXACTIVE_2' => '1',
			'OXACTIVE_3' => '',
			'OXTITLE' => 'Bereits storniert',
			'OXTITLE_1' => 'Already cancelled',
			'OXTITLE_2' => 'Déjà annulé',
			'OXTITLE_3' => '',
			'OXCONTENT' => '<p>Ihre Bestellung wurde bereits storniert. Sie können jederzeit eine Neubestellung durchführen. </p>',
			'OXCONTENT_1' => '<p>You have already cancelled your order and cannot restart it with this feature.<br /><br />If you have not gone through with the "Reorder" process, you can of course buy the same products again in another order provided that they are still available.<br /><br />The cancelled order will not be processed any further.</p>',
			'OXCONTENT_2' => '<p>Vous avez déjà annulé votre commande et ne pouvez plus recommencer-la par cette function.<br /><br />Si vous n\'avez pas fini la procedure de "Recommander", vous pouvez donner une autre commande avec les mêmes produîts s\'ils sont disponible.<br /><br />La commande annulé ne sera plus executée.</p>',
			'OXCONTENT_3' => '',
			'OXFOLDER' => 'CMSFOLDER_USERINFO',
			'OXCATID' => '30e44ab83fdee7564.23264141',
			'OXTIMESTAMP' => '2016-07-25 13:12:54'
		);
		$this->makeContentElement('sccpnoarticlesleft', $aContent, '"Bereits storniert" Text existiert bereits!', '"Bereits storniert" Text wurde angelegt!', '"Bereits storniert" Text ließ sich nicht anlegen!');

		// #11894
		/** @var ResultSetInterface $oRes */
		$sGroupOxid = 'sccponlyone';
		$oRes = $oDB->select("SELECT oxid FROM sccp_prodgroup LIMIT 2");
		// if ( $oRes && $oRes->RecordCount() ) {
		if ( $oRes && $oRes->count() ) {
			// Groups exist
			$sGroupOxid = $oRes->fields['oxid'];
		} else {
			$oDB->execute("INSERT INTO sccp_prodgroup (oxid, sccp_name, sccp_producttypeid, sccp_productclassid) VALUES ('sccponlyone', 'Zugewiesene Artikel', '56', '11');");
		}
		
		$oRes = $oDB->select("SELECT oxid FROM sccp_offered_option LIMIT 2");
		if ( $oRes && $oRes->count() ) {
			// Options exist
		} else {
			$oDB->execute("INSERT INTO sccp_offered_option 
				(
					oxid, sccp_months, sccp_interest, 
					sccp_prodcode, sccp_active, sccp_ratefactor
				) VALUES (
					'sccponlyone1', '6', '4.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone2', '8', '4.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone3', '10', '4.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone4', '12', '4.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone5', '18', '4.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone6', '20', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone7', '24', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone8', '30', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone9', '36', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone10', '42', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone11', '48', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone12', '54', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone13', '60', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone14', '66', '5.99',
					'Standard', '1', '-1'
				),(
					'sccponlyone15', '72', '5.99',
					'Standard', '1', '-1'
				);
			");
			
			$oDB->execute("INSERT INTO sccp_offered_option_prodgroup 
 				(oxid, sccp_offered_option_id, sccp_prodgroup_id)
 				VALUES 
 				('sccp1to1', 'sccponlyone1', '$sGroupOxid'),
 				('sccp2to1', 'sccponlyone2', '$sGroupOxid'),
 				('sccp3to1', 'sccponlyone3', '$sGroupOxid'),
 				('sccp4to1', 'sccponlyone4', '$sGroupOxid'),
 				('sccp5to1', 'sccponlyone5', '$sGroupOxid'),
 				('sccp6to1', 'sccponlyone6', '$sGroupOxid'),
 				('sccp7to1', 'sccponlyone7', '$sGroupOxid'),
 				('sccp8to1', 'sccponlyone8', '$sGroupOxid'),
 				('sccp9to1', 'sccponlyone9', '$sGroupOxid'),
 				('sccp10to1', 'sccponlyone10', '$sGroupOxid'),
 				('sccp11to1', 'sccponlyone11', '$sGroupOxid'),
 				('sccp12to1', 'sccponlyone12', '$sGroupOxid'),
 				('sccp13to1', 'sccponlyone13', '$sGroupOxid'),
 				('sccp14to1', 'sccponlyone14', '$sGroupOxid'),
 				('sccp15to1', 'sccponlyone15', '$sGroupOxid');
			");
		}

		return $sReturn;
	}

	/**
	 * Checks for existance of an oxContent with oxid $sOxid or oxloadid $sOxid and creates one with parameters in $aData if necessary
	 * @param string $sOxid
	 * @param array $aData Array of field => value assignments (goes through oxContent::assign())
	 * @param string $sExists If content exists, this message is added to the view
	 * @param string $sSuccess If content is created, this message is added to the view
	 * @param string $sError If content did not exist and could not be created, this message is added to the view
	 * @see oxContent::assign()
	 */
	protected function makeContentElement( $sOxid, $aData, $sExists, $sSuccess, $sError ) {
		/** @var \OxidEsales\Eshop\Application\Model\Content $oContent */
		$oContent = oxNew(\OxidEsales\Eshop\Application\Model\Content::class);
		if ( $oContent->load($sOxid) || $oContent->loadByIdent($sOxid) ) {
			$this->appendSuccess($sExists);
		} else {
			$this->createI18NElement($oContent,$aData,$sSuccess,$sError);
		}
	}

	/**
	 * Checks for existance of an oxPayment with oxid $sOxid and creates one with parameters in $aData if necessary
	 * @param string $sOxid
	 * @param array $aData Array of field => value assignments (goes through oxPayment::assign())
	 * @param string $sExists If payment exists, this message is added to the view
	 * @param string $sSuccess If payment is created, this message is added to the view
	 * @param string $sError If payment did not exist and could not be created, this message is added to the view
	 * @see oxPayment::assign()
	 */
	protected function makePaymentElement( $sOxid, $aData, $sExists, $sSuccess, $sError ) {
		/** @var \OxidEsales\Eshop\Application\Model\Payment $oPayment */
		$oPayment = oxNew(\OxidEsales\Eshop\Application\Model\Payment::class);
		if ( $oPayment->load($sOxid) ) {
			$this->appendSuccess($sExists);
			$bChanged = false;
			if ( $oPayment->oxpayments__oxfromamount->value === '100' ) {
				$oPayment->oxpayments__oxfromamount->setValue(150.00);
				$bChanged = true;
			}
			if ( $oPayment->oxpayments__oxtoamount->value === '25000' ) {
				$oPayment->oxpayments__oxtoamount->setValue(40000.00);
				$bChanged = true;
			}
			if ( $bChanged ) {
				$oPayment->oxpayments__oxtimestamp->setValue('2017-12-13 14:28:25');
				$oPayment->save();
			}
		} else {
			$this->createI18NElement($oPayment, $aData, $sSuccess, $sError);
		}
	}

	/**
	 * Creates any oxI18N Element. These are translatable elements, which is almost any object in this system.
	 * The passed object can be of any derived class such as oxPayment or oxContent
	 * as long as it extends oxI18N on its way to the top
	 * @param \OxidEsales\Eshop\Core\Model\MultiLanguageModel $oObject Any object that has oxI18N in its rootline of classes
	 * @param array $aData Data to save and assign
	 * @param string $sSuccess Text if creation succeeds
	 * @param string $sError Text if creation fails
	 */
	protected function createI18NElement($oObject, $aData, $sSuccess, $sError) {
		$oObject->setEnableMultilang(false);
		$oConfig = $this->getConfig();

		$oObject->assign($aData);
		if ( $oObject->save() ) {
			$this->appendSuccess($sSuccess);
		} else {
			$this->_aViewData['bSuccess'] = false;
			if ( $this->_aViewData['sError'] ) {
				$this->_aViewData['sError'] .= "<br />\r\n";
			}
			$this->_aViewData['sError'] .= $sError;
		}
	}

	/**
	 * @param string $sSuccess Text on success
	 */
	protected function appendSuccess( $sSuccess ) {
		if ( $this->_aViewData['sSuccess'] ) {
			$this->_aViewData['sSuccess'] .= "<br />\r\n";
		}
		$oConfig = $this->getConfig();
		$this->_aViewData['sSuccess'] .= $sSuccess;
	}

	/**
	 * @param \OxidEsales\Eshop\Core\Config $oConf
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface $oDB
	 */
	protected function createRateTable($oConf, $oDB) {
		try {
			/** @var ResultSetInterface $oRes */
			//$oRes = $oDB->execute("SELECT COUNT(*) amount FROM sccp_rates");
			$oRes = $oDB->select("SELECT COUNT(*) amount FROM sccp_rates");
		} catch ( \OxidEsales\Eshop\Core\Exception\DatabaseErrorException $e ) {
			$oRes = false;
		}
		//if ( !$oRes || ($oRes->RecordCount() == 0) || ($oRes->fields['amount'] == 0) ) {
		if ( !$oRes || ($oRes->fields['amount'] == 0) ) {
			$oDB->execute('CREATE TABLE IF NOT EXISTS `sccp_rates` (
							  `oxid` CHAR(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
							  `sceffzins` VARCHAR(5) CHARACTER SET utf8 NOT NULL,
							  `scmonths` VARCHAR(5) CHARACTER SET utf8 NOT NULL,
							  `scfactor` VARCHAR(10) CHARACTER SET utf8 NOT NULL,
							  PRIMARY KEY (`oxid`)
							) ENGINE=MyISAM DEFAULT CHARSET=latin1;');
			$aMeta = $oDB->metaColumns('sccp_rates');
			if ( $aMeta ) {
				$aFields = array();
				foreach ( $aMeta as $sKey => $oValue ) {
					$aFields[strtolower($sKey)] = $oValue;
				}
				if ( !array_key_exists('sceffzins',$aFields) ) {
					$oDB->execute('ALTER TABLE sccp_rates ADD `sceffzins` VARCHAR(5) CHARACTER SET utf8 NOT NULL');
				}
				if ( !array_key_exists('scmonths',$aFields) ) {
					$oDB->execute('ALTER TABLE sccp_rates ADD `scmonths` VARCHAR(5) CHARACTER SET utf8 NOT NULL');
				}
				if ( !array_key_exists('scfactor',$aFields) ) {
					$oDB->execute('ALTER TABLE sccp_rates ADD `scfactor` VARCHAR(10) CHARACTER SET utf8 NOT NULL');
				}

				// Add Key
				if ( !$aFields['oxid']->primary_key ) {
					$oDB->execute('ALTER TABLE sccp_rates ADD PRIMARY KEY (oxid);');
				}
			}
			$oFH = fopen($oConf->getConfigParam('sShopDir').'/modules/sc/sccp/Ratentabelle.csv', 'r');
			if ( $oFH ) {
				fgetcsv($oFH, 4096, ';', '"', '\\');
				while ( $aRow = fgetcsv($oFH, 4096, ';', '"', '\\') ) {
					#var_dump($aRow);
					$sInsert = "INSERT INTO sccp_rates (oxid, sceffzins, scmonths, scfactor) VALUES ('".$aRow[0]."','".$aRow[2]."','".$aRow[3]."','".$aRow[4]."');";
					$oDB->execute($sInsert);
					// echo $sInsert."\r\n";
				}
				fclose($oFH);
				$this->appendSuccess('Zinstabelle erfolgreich installiert!');
			} else {
				$this->_aViewData['bSuccess'] = false;
				if ( $this->_aViewData['sError'] ) {
					$this->_aViewData['sError'] .= "<br />\r\n";
				}
				$this->_aViewData['sError'] .= 'Zinstabelle ist nicht lesbar!';
			}
		} else {
			$this->appendSuccess('Zinstabelle existiert bereits!');
		}
	}

	/**
	 * Creates the database table or updates missing fields
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface $oDB
	 */
	protected function createOrUpdateSccpOfferedOption($oDB) {
		//Tabellenstruktur für Tabelle `sccp_offered_option`
		$oDB->execute('CREATE TABLE IF NOT EXISTS `sccp_offered_option` (
						  `oxid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `sccp_months` int(11) NOT NULL,
						  `sccp_interest` double NOT NULL,
						  `sccp_prodcode` varchar(255) NOT NULL,
						  `sccp_active` int(11) NOT NULL DEFAULT \'0\',
						  `sccp_ratefactor` double NOT NULL COMMENT \'Calculated Value based on months, updated based upon the value changed there.\'
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
		$aMeta = $oDB->metaColumns('sccp_offered_option');
		if ( $aMeta ) {
			$aFields = array();
			foreach ( $aMeta as $sKey => $oValue ) {
				$aFields[strtolower($sKey)] = $oValue;
			}
			if ( !array_key_exists('sccp_months',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option ADD `sccp_months` int(11) NOT NULL');
			}
			if ( !array_key_exists('sccp_interest',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option ADD `sccp_interest` double NOT NULL');
			}
			if ( !array_key_exists('sccp_prodcode',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option ADD `sccp_prodcode` varchar(255) NOT NULL');
			}
			if ( !array_key_exists('sccp_active',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option ADD `sccp_active` int(11) NOT NULL DEFAULT \'0\'');
			}
			if ( !array_key_exists('sccp_ratefactor',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option ADD `sccp_ratefactor` double NOT NULL COMMENT \'Calculated Value based on months, updated based upon the value changed there.\'');
			}

			// Add Key
			if ( !$aFields['oxid']->primary_key ) {
				$oDB->execute('ALTER TABLE sccp_offered_option ADD PRIMARY KEY (oxid);');
			}
		}
	}

	/**
	 * Creates the database table or updates missing fields
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface $oDB
	 */
	protected function createOrUpdateSccpOfferedOptionProdgroup( $oDB ) {
		//Tabellenstruktur für Tabelle `sccp_offered_option_prodgroup`
		$oDB->execute('CREATE TABLE IF NOT EXISTS `sccp_offered_option_prodgroup` (
						  `oxid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `sccp_offered_option_id` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `sccp_prodgroup_id` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
		$aMeta = $oDB->metaColumns('sccp_offered_option_prodgroup');
		if ( $aMeta ) {
			$aFields = array();
			foreach ( $aMeta as $sKey => $oValue ) {
				$aFields[strtolower($sKey)] = $oValue;
			}
			if ( !array_key_exists('sccp_offered_option_id',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option_prodgroup ADD `sccp_offered_option_id` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL');
			}
			if ( !array_key_exists('sccp_prodgroup_id',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_offered_option_prodgroup ADD `sccp_prodgroup_id` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL');
			}

			// Add Key
			if ( !$aFields['oxid']->primary_key ) {
				$oDB->execute('ALTER TABLE sccp_offered_option_prodgroup ADD PRIMARY KEY (oxid);');
			}
		}

	}

	/**
	 * Creates the database table or updates missing fields
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface $oDB
	 */
	protected function createOrUpdateSccpProdgroup( $oDB ) {
		// Tabellenstruktur für Tabelle `sccp_prodgroup`
		$oDB->execute('CREATE TABLE IF NOT EXISTS `sccp_prodgroup` (
						  `oxid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `sccp_name` varchar(255) NOT NULL,
						  `sccp_producttypeid` varchar(255) CHARACTER SET utf8 NOT NULL,
						  `sccp_productclassid` varchar(255) CHARACTER SET utf8 NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
		$aMeta = $oDB->metaColumns('sccp_prodgroup');
		if ( $aMeta ) {
			$aFields = array();
			foreach ( $aMeta as $sKey => $oValue ) {
				$aFields[strtolower($sKey)] = $oValue;
			}
			if ( !array_key_exists('sccp_name',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup ADD `sccp_name` varchar(255) NOT NULL');
			}
			if ( !array_key_exists('sccp_producttypeid',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup ADD `sccp_producttypeid` varchar(255) CHARACTER SET utf8 NOT NULL');
			}
			if ( !array_key_exists('sccp_productclassid',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup ADD `sccp_productclassid` varchar(255) CHARACTER SET utf8 NOT NULL');
			}

			// Add Key
			if ( !$aFields['oxid']->primary_key ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup ADD PRIMARY KEY (oxid);');
			}
		}
	}

	/**
	 * Creates the database table or updates missing fields
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface $oDB
	 */
	protected function createOrUpdateSccpProdgroupArticle( $oDB ) {
		// Tabellenstruktur für Tabelle `sccp_prodgroup_article`
		$oDB->execute('CREATE TABLE IF NOT EXISTS `sccp_prodgroup_article` (
						  `oxid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `sccp_prodgroup_id` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `oxartid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
		$aMeta = $oDB->metaColumns('sccp_prodgroup_article');
		if ( $aMeta ) {
			$aFields = array();
			foreach ( $aMeta as $sKey => $oValue ) {
				$aFields[strtolower($sKey)] = $oValue;
			}
			if ( !array_key_exists('sccp_prodgroup_id',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup_article ADD `sccp_prodgroup_id` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL');
			}
			if ( !array_key_exists('oxartid',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup_article ADD `oxartid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL');
			}

			// Set Key
			if ( !$aFields['oxid']->primary_key ) {
				$oDB->execute('ALTER TABLE sccp_prodgroup_article ADD PRIMARY KEY (oxid);');
			}
		}
	}

	/**
	 * Creates the database table or updates missing fields
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface $oDB
	 */
	protected function createOrUpdateSccpOrderFinance( $oDB ) {
		// Tabellenstruktur für Tabelle `sccp_oxorder_finance`
		$oDB->execute('CREATE TABLE IF NOT EXISTS `sccp_oxorder_finance` (
						  `oxid` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						  `sccp_order_link` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
						  `sccp_linkgen_timestamp` int(11) NULL
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
		$aMeta = $oDB->metaColumns('sccp_oxorder_finance');
		if ( $aMeta ) {
			$aFields = array();
			foreach ( $aMeta as $sKey => $oValue ) {
				$aFields[strtolower($sKey)] = $oValue;
			}
			if ( !array_key_exists('sccp_order_link',$aFields) ) {
				$oDB->execute('ALTER TABLE sccp_oxorder_finance ADD `sccp_order_link` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
			}

			// Set Key
			if ( !$aFields['oxid']->primary_key ) {
				$oDB->execute('ALTER TABLE sccp_oxorder_finance ADD PRIMARY KEY (oxid);');
			}
		}
	}

	protected function getCharSetForTableFields() {
		$bUtfMode = true;
		if ( $bUtfMode ) {
			return 'CHARACTER SET utf8 COLLATE utf8_general_ci';
		}
		return 'CHARACTER SET latin1 COLLATE latin1_general_ci';
	}

	protected function getCharSetForTable() {

		$bUtfMode = true;
		if ( $bUtfMode ) {
			return 'DEFAULT CHARSET=utf8';
		}
		return 'DEFAULT CHARSET=latin1';
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Install::class,'sccp_install');
