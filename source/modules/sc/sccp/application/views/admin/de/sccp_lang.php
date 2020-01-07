<?php
/**
 * Backend Konstanten für die Sprachlabels
 */

$sLangName = "Deutsch";

$aLang = array(
	"charset" => "ISO-8859-15", // Supports only chars like: ê, í, ß, etc.

	// Module Settings
	'SHOP_MODULE_GROUP_features' => 'Features',
	'SHOP_MODULE_GROUP_mails' => 'E-Mails',
	'SHOP_MODULE_bTestMode' => 'Testmodus aktiviert',
	'SHOP_MODULE_sCPDealer' => 'CreditPlus Händlernummer',
	'SHOP_MODULE_sDealerName' => 'Händler Name',
	'SHOP_MODULE_sSoapUser' => 'API Benutzer',
	'SHOP_MODULE_sSoapPass' => 'API Passwort',
	'SHOP_MODULE_sSoapType' => 'API Passwort Art',
	'SHOP_MODULE_sSoapType_PasswordDigest' => 'PasswordDigest',
	'SHOP_MODULE_sSoapType_Plain' => 'Klartext',
	'SHOP_MODULE_sPartnerName' => 'Partner Name',
	'SHOP_MODULE_sWSDL' => 'WSDL URL',
	'SHOP_MODULE_sCPShownAs' => 'Anzeigeart',
	'SHOP_MODULE_sCPShownAs_iframe' => 'Iframe',
	'SHOP_MODULE_sCPShownAs_popup' => 'Popup',
	'SHOP_MODULE_sRedirectURL' => 'Ziel URL für CreditPlus System',
	'SHOP_MODULE_sMinRate' => 'Mindestrate',
	'SHOP_MODULE_sTransactionMode' => 'Zahlungszeitpunkt',
	'SHOP_MODULE_sTransactionMode_postorder' => 'Nach Bestellabschluss',
	'SHOP_MODULE_sTransactionMode_inorder' => 'Vor Bestellabschluss',
	'SHOP_MODULE_sSignatureSalt' => 'Shared Secret (Gemeinsames "Passwort" mit CreditPlus zum Signieren)',
	'SHOP_MODULE_sBasketFinancingMode' => 'Warenkorbfinanzierung',
	'SHOP_MODULE_sBasketFinancingMode_most-expensive' => 'Teuerster Zinssatz',
	'SHOP_MODULE_sBasketFinancingMode_weighted-majority' => 'Umsatzstärkster Zinssatz',
	'SHOP_MODULE_sBasketFinancingMode_number-majority' => 'Anzahlstärkster Zinssatz',
	'SHOP_MODULE_sBasketFinancingMode_cheapest' => 'Günstigster Zinssatz',
	'SHOP_MODULE_bShowDetails' => 'Ratentabelle auf Detailseite anzeigen',
	'SHOP_MODULE_bShowBasket' => 'Ratentabelle auf Warenkorb anzeigen',
	'SHOP_MODULE_bShowPayment' => 'Ratentabelle auf Zahlungsseite anzeigen',
	'SHOP_MODULE_sStateMail_Recipient_0fa7b9d' => 'E-Mail Adresse für Status Übergeben',
	'SHOP_MODULE_sStateMail_Recipient_d3405ba' => 'E-Mail Adresse für Status Angenommen',
	'SHOP_MODULE_sStateMail_Recipient_4d09724' => 'E-Mail Adresse für Status Genehmigung und Versendet',
	'SHOP_MODULE_sStateMail_Recipient_3a863ad' => 'E-Mail Adresse für Status Genehmigung',
	'SHOP_MODULE_sStateMail_Recipient_6a4543d' => 'E-Mail Adresse für Status Dokumente erhalten',
	'SHOP_MODULE_sStateMail_Recipient_5b6a24f' => 'E-Mail Adresse für Status Zahlung wird bearbeitet',
	'SHOP_MODULE_sStateMail_Recipient_1b138f7' => 'E-Mail Adresse für Status Abgelehnt (Weich)',
	'SHOP_MODULE_sStateMail_Recipient_b91d4ad' => 'E-Mail Adresse für Status Abgelehnt (Hart)',
	'SHOP_MODULE_sStateMail_Recipient_a4327c3' => 'E-Mail Adresse für Status Bezahlt',
	'SHOP_MODULE_sStateMail_Recipient_d7e38be' => 'E-Mail Adresse für Status Storniert',
	'SHOP_MODULE_sStateMail_Recipient_ae1012d' => 'E-Mail Adresse für Status Fehler',
	'SHOP_MODULE_sStateMail_RecipientName_0fa7b9d' => 'Name für Status Übergeben',
	'SHOP_MODULE_sStateMail_RecipientName_d3405ba' => 'Name für Status Angenommen',
	'SHOP_MODULE_sStateMail_RecipientName_4d09724' => 'Name für Status Genehmigung und Versendet',
	'SHOP_MODULE_sStateMail_RecipientName_3a863ad' => 'Name für Status Genehmigung',
	'SHOP_MODULE_sStateMail_RecipientName_6a4543d' => 'Name für Status Dokumente erhalten',
	'SHOP_MODULE_sStateMail_RecipientName_5b6a24f' => 'Name für Status Zahlung wird bearbeitet',
	'SHOP_MODULE_sStateMail_RecipientName_1b138f7' => 'Name für Status Abgelehnt (Weich)',
	'SHOP_MODULE_sStateMail_RecipientName_b91d4ad' => 'Name für Status Abgelehnt (Hart)',
	'SHOP_MODULE_sStateMail_RecipientName_a4327c3' => 'Name für Status Bezahlt',
	'SHOP_MODULE_sStateMail_RecipientName_d7e38be' => 'Name für Status Storniert',
	'SHOP_MODULE_sStateMail_RecipientName_ae1012d' => 'Name für Status Fehler',

	//Backend Menu
	'mxsccpcporder' => 'CreditPlus Bestellungen',
	'mxsccpcpproductgroup' => 'CP Produktgruppen',
	'mxsccpcpofferedoptions' => 'CP Zinstabelle',
	'mxsccpexcludedarticles' => 'CP Ausschlüsse',
	'tbclsccp_order_details' => 'Details',
	'tbclsccp_order_article' => 'Artikel',
	'tbsccpcpexcludedarticlesdetail' => 'Ausgeschlossene Artikel',

	//Backend List
	'CPORDER_LIST_TRANSID' => 'Antrags ID',
	'CPORDER_LIST_MENUITEM' => 'Finanzierungen verwalten',
	'CPORDER_LIST_MENUSUBITEM' => 'Finanzierungsdetails',
	'CPORDER_DETAIL_NOTARRIVED_AT_BANK' => 'Die Finanzierung ist bisher bei der Bank nicht eingegangen, da der Kunde die Antragsstrecke bisher noch nicht aufrief. Die Daten sind daher noch nicht verfügbar.',
	'CPORDER_DETAIL_CONTRACT_SIGNED_ON' => 'Unterschrift durch Kunden am:',
	'CPORDER_DETAIL_CONTRACT_SIGNED_NOT_YET' => 'Noch nicht geschehen',
	'CPORDER_DETAIL_CONFIRM_DELIVERY' => 'Warenversand bestätigen',

	'SCCP_FINANCING_PRODUCT_GROUPS_SAVED' => 'Produktgruppen gespeichert',
	'SCCP_FINANCING_PRODUCT_GROUPS_NUMBER' => 'Nummer',
	'SCCP_FINANCING_PRODUCT_GROUPS_NAME' => 'Name',
	'SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTTYPEID' => 'Produkt Typ ID',
	'SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTCLASSID' => 'Produkt Klasse ID',
	'SCCP_FINANCING_PRODUCT_GROUPS_DEFAULT' => 'Standard',
	'SCCP_FINANCING_PRODUCT_GROUPS_DELETE' => 'Löschen',
	'SCCP_FINANCING_PRODUCT_GROUPS_ASSIGN_ARTICLES' => 'Artikel zuweisen',
	'SCCP_FINANCING_PRODUCT_GROUPS_ADD' => 'Neue Produktgruppe erstellen',
	'SCCP_FINANCING_PRODUCT_GROUPS_RUN_INSTALL_FIRST' => 'Führen Sie bitte erst die Installation zu Ende aus. Bitte suchen Sie in der Anleitung den <a href="/index.php?cl=sccp_install" target="_blank">Link zum Install Script</a>, wenn <a href="/index.php?cl=sccp_install" target="_blank">dieser hier</a> nicht funktioniert.',



	'SCCP_FINANCING_OFFERED_OPTIONS_MONTHS' => 'Monate',
	'SCCP_FINANCING_OFFERED_OPTIONS_INTEREST' => 'Effektiver Jahreszins',
	'SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR' => 'Ratenfaktor',
	'SCCP_FINANCING_OFFERED_OPTIONS_PRODCODE' => 'Gruppierungsname',
	'SCCP_FINANCING_OFFERED_OPTIONS_ACTIVE' => 'Aktiv',
	'SCCP_FINANCING_OFFERED_OPTIONS_DELETE' => 'Löschen',
	'SCCP_FINANCING_OFFERED_OPTIONS_ASSIGN_PROD_GROUPS' => 'Produktgruppen zuweisen',
	'SCCP_FINANCING_OFFERED_OPTIONS_SAVED' => 'Konditionen gespeichert',
	'SCCP_FINANCING_OFFERED_OPTIONS_ADD' => 'Neue Kondition erstellen',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MINMONTHS' => 'Die Mindestlaufzeit beträgt 6 Monate - Produktcode ###PRODCODE### ###MONTHS### Monate ###INTEREST###% wurde automatisch auf 6 Monate angehoben.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MININTEREST' => 'Der Mindestzins beträgt 0% - Produktcode ###PRODCODE### ###MONTHS### Monate ###INTEREST###% wurde automatisch auf 0% angehoben.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MINRATEFACTOR' => 'Der Ratenfaktor muss über 0 und unter 1 liegen - Produktcode ###PRODCODE### ###MONTHS### Monate ###INTEREST###% wurde daher auf automatische Berechnung gestellt.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MAXRATEFACTOR' => 'Der Ratenfaktor muss über 0 und unter 1 liegen - Produktcode ###PRODCODE### ###MONTHS### Monate ###INTEREST###% wurde daher auf automatische Berechnung gestellt.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MAXINTEREST' => 'Der Höchstzins beträgt 17,99% - Produktcode ###PRODCODE### ###MONTHS### Monate ###INTEREST###% wurde automatisch auf 17,99% gesenkt. Der Zinssatz sollte höchstens doppelt so hoch wie der Marktzins, höchstens jedoch 12% p.a. über jenem liegen. vgl. BGH Urteil vom 11.01.1995, Aktenzeichen: VIII ZR 82/94',
	'SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR_INFO' => 'Der Ratenfaktor liegt zwischen 0 und 1, leer lassen lässt diesen mathematisch automatisch berechnen.',

	'GENERAL_AJAX_SORT_SCCP_NAME' => 'Name',
	'SCCP_CPOFFERED_OPTIONS_ALLPRODGROUPS' => 'Nicht zugewiesene Produktgruppen',
	'SCCP_CPOFFERED_OPTIONS_ASSIGNEDPRODGROUPS' => 'Zugewiesene Produktgruppen',

	'SCCP_CPPRODUCT_GROUP_FILTER_CATEGORIES' => 'Nach Kategorie filtern',
	'SCCP_CPPRODUCT_GROUP_DESCRIPTION' => 'Hier können Sie definieren, welche Produktgruppen es gibt und diesen auch Artikel zuweisen. Diese Produktgruppen können dann Finanzierungsoptionen zugeordnet werden. Zu beachten ist hierbei lediglich folgendes: Wenn eine Produktgruppe <strong>keine</strong> Artikel hat, gilt sie als Standard und wird für alle Artikel eingesetzt. Die Produktgruppen werden erst durch Klick auf den Button "Speichern" gespeichert. Sie können jedoch schon vorher die Produkte zu den neu hinzugefügten Gruppen hinzufügen.',
	'SCCP_CPOFFERED_OPTIONS_DESCRIPTION' => 'Hier können Sie definieren, welche Finanzierungsoptionen Sie anbieten und diese Produktgruppen zuweisen. Wenn für einen Artikel mehrere Optionen zur gleichen Monatszahl existieren, wird die mit dem <strong>niedrigsten</strong> Zinssatz genommen. Die Optionen werden erst durch Klick auf den Button "Speichern" gespeichert. Sie können jedoch schon vorher die Produktgruppen zu den neu hinzugefügten Optionen hinzufügen. Wenn Sie eine Option nur vordefinieren wollen, dann können Sie das Aktiv Häkchen weglassen. Die Option wird dann für Benutzer des Shops nicht angezeigt. Wenn die Option einer Produktgruppe zugewiesen ist, welche für alle Produkte gilt, dann taucht diese bei allen Artikeln auf.',

	'SCCP_CPEXCLUDED_ARTICLES_DETAIL_TITLE' => 'Ausgeschlossene Produkte',
	'SCCP_CPEXCLUDED_ARTICLES_DETAIL_DESCRIPTION' => 'Ausgeschlossene Produkte',
	'SCCP_CPEXCLUDED_ARTICLES' => 'Ausgeschlossene Produkte',
	'SCCP_CPEXCLUDED_ARTICLES_DETAIL' => 'Ausgeschlossene Produkte',
	'SCCP_CPEXCLUDED_ARTICLES_SHOW_UNASSIGNED' => 'Liste der nicht zugewiesenen Artikel anzeigen',
	'SCCP_CPEXCLUDED_ARTICLES_SHOW_OPTIONS_FOR' => 'Liste der zugewiesenen Finanzierungs Optionen zu',
	'SCCP_CPEXCLUDED_ARTICLES_ARTICLENUMBER' => 'Artikelnummer',
	'SCCP_CPEXCLUDED_ARTICLES_SUBMIT' => 'Anzeigen',

	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS' => 'Folgende Finanzierungsoptionen existieren in diesem Shop für die angegeben Artikelnummer.',
	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_ARTICLE_FOUND' => 'Keine Artikel mit dieser Artikelnummer gefunden.',
	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_ARTICLE_REQUESTED' => 'Keine Artikelnummer angefragt.',
	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_OPTIONS' => 'Für diesen Artikel wurden keine Finanzierungsoptionen gefunden.',
	'SCCP_CPEXCLUDED_ARTICLES_TD_MONTHS' => 'Monate',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_GROUP_NAME' => 'Produktgruppe',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_TYPE_ID' => 'Produkt Typ ID',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_CLASS_ID' => 'Produkt Klasse ID',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_CODE' => 'Finanzierungsname',
	'SCCP_CPEXCLUDED_ARTICLES_TD_EFFECTIVE_INTEREST' => 'Effektiver Jahreszins',
	'SCCP_CPEXCLUDED_ARTICLES_TD_MONTHLY_RATE' => 'Monatsrate (ca.)',

	'SCCP_CPEXCLUDED_ARTICLES_DEFAULT_GROUP_ACTIVE' => 'Standardgruppen sind aktiv. Solange die folgenden Grupen aktiv sind, wird kein Artikel ausgeschlossen.',
	'SCCP_CPEXCLUDED_ARTICLES_FOLLOWING_ARTICLES_MISSING' => 'Folgende Artikel haben keine Finanzierungsoptionen.',
	'SCCP_CPEXCLUDED_ARTICLES_TD_ROWNUMBER' => 'Nr.',
	'SCCP_CPEXCLUDED_ARTICLES_TD_ARTICLENUMBER' => 'Artikelnummer',
	'SCCP_CPEXCLUDED_ARTICLES_TD_ARTICLETITLE' => 'Titel',

	'SCCP_CPORDER_ARTICLE_ERROR_ALREADY_CANCELLED' => 'Der Artikel ist bereits storniert.',
	'SCCP_CPORDER_ARTICLE_ERROR_NO_INCREASE_POSSIBLE' => 'Die Finanzierungssumme kann nachträglich nicht erhöht werden.',
	'SCCP_CPORDER_ARTICLE_MESSAGE_REDUCED_BY_PRODUCT_PRICE' => 'Die Finanzierungssumme wurde um den Wert des stornierten Artikels reduziert.',
	'SCCP_CPORDER_ARTICLE_MESSAGE_PARTIALLY_RETURNED' => 'Teilretoure durchgeführt.',
	'SCCP_CPORDER_DETAILS_MESSAGE_TOTALLY_RETURNED' => 'Vollständige Retoure durchgeführt.',
	'SCCP_CPORDER_DETAILS_ERROR_CANCEL_NOT_AVAILABLE_CREDITPLUS' => 'Die Bestellung ist aufgrund des Status bei CreditPlus nicht bei CreditPlus stornierbar.<br />Die Bestellung im Shop wurde jedoch auf den Status storniert gesetzt.',
	'SCCP_CPORDER_DETAILS_ERROR_ALREADY_CANCELLED_ORDER' => 'Die Bestellung ist bereits storniert.',
	
	'SCCP_ADMIN_CHANGE_IN_CUSTOM_MENU' => 'Bearbeitung teilweise deaktiviert!<br /><br />Bitte nutzen Sie zur Änderung der ausgegrauten Elemente und Funktionen<br />
den dafür vorgesehenen Menüpunkt unter<br />
Bestellungen verwalten -&gt; CreditPlus Bestellungen.',
	'SCCP_ADMIN_TOTAL_CHANGE_IN_CUSTOM_MENU' => 'Bearbeitung deaktiviert!<br /><br />Bitte nutzen Sie zur Änderung dieses Punktes<br />
den dafür vorgesehenen Menüpunkt unter<br />
Bestellungen verwalten -&gt; CreditPlus Bestellungen.',
	'SCCP_ADMIN_KEEP_ADDRESSES_SYNCHRONIZED' => 'Achtung!<br /><br />
Wenn Sie an dieser Stelle Anpassungen vornehmen, geben Sie bitte 
die Aktualisierungen auch an CreditPlus weiter um die Daten synchron zu halten.',
);
