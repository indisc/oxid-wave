<?php

$aLang = array(
	"charset" => "UTF-8", // Supports chars like: ¤, ß, ä, etc.

//	'SCCP_FINANZIERUNG_DESCRIPTION' => 'Hiermit finanzieren Sie günstig Ihren Einkauf. Gültig ab ###MINDESTBESTELLWERT###; billigster effektiver Jahreszins: ###JAHRESZINS###%',
	'SCCP_FINANZIERUNG_DESCRIPTION' => 'Bezahlen Sie Ihren Einkauf schnell und bequem über monatlich niedrige Raten mit der CreditPlus Finanzierung. Einfach CreditPlus Finanzierung als Zahlungsart bei der Bestellung auswählen.',
	'SCCP_FINANZIERUNG_MONTHS' => 'Monate',
	'SCCP_FINANZIERUNG_DURATION' => 'Laufzeit',
	'SCCP_FINANZIERUNG_INTEREST_RATE' => 'eff. Jahreszins',
	'SCCP_FINANZIERUNG_NOMINAL_INTEREST_RATE' => 'geb. Sollzinssatz p.a.',
	'SCCP_FINANZIERUNG_INTEREST' => 'Zinsen',
	'SCCP_FINANZIERUNG_REQUESTED_AMOUNT' => 'Gesamtbetrag',
	'SCCP_FINANZIERUNG_AMOUNT_OF_PAYMENTS' => 'Anzahl der Raten',
	'SCCP_FINANZIERUNG_MONTHLY_RATE' => 'monatliche Rate',
	'SCCP_FINANZIERUNG_MINIINFO' => 'Schon ab ###PRICE### für ###MONTHS### Monate <a href="JavaScript:void(0);" onclick="jQuery(\'a[href=\\\'#financing\\\']\').click();">finanzieren</a>.',

	'SCCP_DETAILS_FINANCING' => 'Finanzierung',

	// Thankyou Block extension
	'SCCP_FINANCING_REQUESTNUMBER1' => 'Ihre Antragsnummer ist:',
	'SCCP_FINANCING_REQUESTNUMBER2' => 'Bitte beachten Sie, dass die Ware erst verschickt wird, wenn der Finanzierungsantrag per Postident bei der Bank bestätigt wurde.',
	'SCCP_FINANCING_CONTINUE_TO_PAYMENT1' => 'Die Bestellung ist zwar abgeschlossen, aber Sie müssen jetzt noch die Antragsstrecke zur Finanzierung durchgehen. Diese erreichen Sie per Klick auf:<br /><br />',
	'SCCP_FINANCING_CONTINUE_TO_PAYMENT2' => 'Jetzt Finanzieren!',
	'SCCP_FINANCING_CONTINUE_TO_PAYMENT3' => '<br />',
	'SCCP_FINANCING_CONTINUE_TO_PAYMENT4' => ' Sollten Sie einen anderen Zahlungsweg auswählen wollen, dann können Sie die Option "',
	'SCCP_FINANCING_CONTINUE_TO_PAYMENT5' => 'Bestellung stornieren und neu starten',
	'SCCP_FINANCING_CONTINUE_TO_PAYMENT6' => '" wählen. Bitte beachten Sie, dass Sie dabei eine neue Bestellnummer und Bestellbestätigung erhalten.',

	// Order Restart Page
	'SCCP_FINANCING_RESTART_ORDER1' => 'Sind Sie sicher, dass Sie die Bestellung ',
	'SCCP_FINANCING_RESTART_ORDER2' => ' stornieren wollen und die unten aufgelisteten Artikel mit einer anderen Zahlungsart kaufen möchten?<br /><br />Durch einen Wechsel erhalten Sie lediglich eine neue Bestellnummer, die eingegebenen Daten bleiben erhalten.',
	'SCCP_FINANCING_BREADCRUMB_PAYPOSTORDER' => 'Jetzt finanzieren',
	'SCCP_FINANCING_BREADCRUMB_PAYINORDER' => 'Jetzt finanzieren',
	'SCCP_FINANCING_BREADCRUMB_FINISHED' => 'Zahlungsweg abgeschlossen',
	'SCCP_FINANCING_BREADCRUMB_REORDER' => 'Bestellung anders bezahlen',
	'SCCP_FINANCING_RESTART_BUTTON_SUBMIT' => 'Mit anderer Zahlungsart neu bestellen',

	'SCCP_FINANCING_FINISHED_ERRORS' => 'Die Zahlung wurde leider abgelehnt.<br /><br />Sie können jedoch die Bestellung stornieren und mit einem <a class="sccp-restart-order" target="_top" href="###URL###">anderen Zahlungsweg neu starten</a>. Die Bestellung wird dabei storniert und eine neue getätigt. Ihnen entstehen dadurch keine weiteren Kosten, Sie müssen jedoch eine neue Bestellnummer in der Kommunikation angeben.',

	'SCCP_ACCOUNT_ORDER_PAYNOW' => 'Jetzt bezahlen',
	'SCCP_ACCOUNT_ORDER_CANCEL' => 'Jetzt neu bestellen',

	'SCCP_FINANCING_MAIL_LINK_RETRY' => 'Wenn Sie die Finanzierung im Shop abgebrochen haben, können Sie über folgenden Link wieder zur Finanzierung kommen: <br /><a style="border: 1px solid #00723F; border-radius: 3px; background-color: #008C56; display: block; width: 200px; text-align: center; font-size: 22px; margin: 0 auto; text-decoration: none; text-shadow: 0 1px 1px #660100; color: #FFFFFF;" href="http://retry.me/">Jetzt&nbsp;finanzieren</a><br />Wenn Sie die Finanzierung bereits nach dem Kauf durchgeführt haben, müssen Sie diesen Link nicht anklicken.<br /><br />Als registrierter Kunde erreichen Sie dieselbe Zahlungsseite über Ihren Kundenaccount.',
	'SCCP_FINANCING_MAIL_LINK_RETRY_PLAIN' => "Wenn Sie die Finanzierung im Shop abgebrochen haben, können Sie über folgenden Link wieder zur Finanzierung kommen:\nhttp://retry.me/\n\nWenn Sie die Finanzierung bereits nach dem Kauf durchgeführt haben, müssen Sie diesen Link nicht anklicken.\n\nAls registrierter Kunde erreichen Sie dieselbe Zahlungsseite über Ihren Kundenaccount.",
	
	'SCCP_ERROR_FINANCING_NOT_ALLOWED' => 'Leider können wir Ihrer Finanzierungsanfrage nicht entsprechen.<br />
Die Entscheidung über Ihre Anfrage basiert auf einer automatisierten Verarbeitung Ihrer personenbezogenen Daten, die der Bewertung einzelner Persönlichkeitsmerkmale dienen.<br />
Bitte wählen Sie eine andere Zahlungsart aus.',
	'SCCP_MAIL_TITLE_STATECHANGE_TEMPLATE' => 'CreditPlus Statusänderung bei Bestellung <Bestellnummer>',
	'SCCP_PAYMENT_STATE_creditplus_accepted' => 'Angenommen',
	'SCCP_PAYMENT_STATE_creditplus_approved' => 'Genehmigung',
	'SCCP_PAYMENT_STATE_creditplus_approved_and_sent' => 'Genehmigung und Versendet',
	'SCCP_PAYMENT_STATE_creditplus_cancelled' => 'Storniert',
	'SCCP_PAYMENT_STATE_creditplus_declined_hard' => 'Abgelehnt (Hart)',
	'SCCP_PAYMENT_STATE_creditplus_declined_soft' => 'Abgelehnt (Weich)',
	'SCCP_PAYMENT_STATE_creditplus_docs_received' => 'Dokumente erhalten',
	'SCCP_PAYMENT_STATE_creditplus_error' => 'Fehler',
	'SCCP_PAYMENT_STATE_creditplus_paid' => 'Bezahlt',
	'SCCP_PAYMENT_STATE_creditplus_processing_payment' => 'Zahlung in Bearbeitung',
	'SCCP_PAYMENT_STATE_creditplus_referred' => 'Übertragen',
);
