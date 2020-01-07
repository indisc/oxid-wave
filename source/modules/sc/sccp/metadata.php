<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';


/**
 * Module information
 */
$aModule = array(
	'id' => 'sccp',
	'title' => 'CreditPlus Finanzierung',
	'description' => 'Modul zur Anbindung vom CreditPlus Finanzierungsdienst.',
	'thumbnail' => 'sinkacom.gif',
	'version' => '6.0.0',
	'author' => 'SinkaCom AG',
	'extend' => array(
		\OxidEsales\Eshop\Application\Controller\AccountOrderController::class => Sinkacom\CreditPlusModule\Controller\AccountOrder::class,
		\OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration::class => Sinkacom\CreditPlusModule\Controller\Admin\ModuleConfig::class,
		\OxidEsales\Eshop\Application\Model\Article::class => Sinkacom\CreditPlusModule\Model\Article::class,
		\OxidEsales\Eshop\Application\Model\Basket::class => Sinkacom\CreditPlusModule\Model\Basket::class,
		\OxidEsales\Eshop\Application\Component\BasketComponent::class => Sinkacom\CreditPlusModule\Component\BasketComponent::class,
		\OxidEsales\Eshop\Application\Model\PaymentGateway::class => Sinkacom\CreditPlusModule\Model\Paymentgateway::class,
		\OxidEsales\Eshop\Application\Model\PaymentList::class => Sinkacom\CreditPlusModule\Model\Paymentlist::class,
		\OxidEsales\Eshop\Application\Model\Order::class => Sinkacom\CreditPlusModule\Model\Order::class,
		\OxidEsales\Eshop\Application\Model\OrderArticle::class => Sinkacom\CreditPlusModule\Model\OrderArticle::class,
		\OxidEsales\Eshop\Application\Controller\OrderController::class => Sinkacom\CreditPlusModule\Controller\Order::class,
		\OxidEsales\Eshop\Application\Controller\ArticleDetailsController::class => Sinkacom\CreditPlusModule\Controller\Details::class,
		\OxidEsales\Eshop\Application\Component\Widget\ArticleDetails::class => Sinkacom\CreditPlusModule\Component\Widget\ArticleDetails::class,//'sc/sccp/application/components/widgets/sccp_oxwarticledetails',
		\OxidEsales\Eshop\Application\Controller\Admin\OrderMain::class => Sinkacom\CreditPlusModule\Controller\Admin\OrderMain::class,
		\OxidEsales\Eshop\Application\Controller\PaymentController::class => Sinkacom\CreditPlusModule\Controller\Payment::class,
		\OxidEsales\Eshop\Application\Controller\ThankYouController::class => Sinkacom\CreditPlusModule\Controller\Thankyou::class,
		\OxidEsales\Eshop\Application\Controller\BasketController::class => Sinkacom\CreditPlusModule\Controller\Basket::class,
		\OxidEsales\Eshop\Core\Email::class => Sinkacom\CreditPlusModule\Core\Email::class
	),
	'controllers' => array(
		'sinkacom_creditplusmodule_installcontroller' => \Sinkacom\CreditPlusModule\Controller\Install::class,
		'sinkacom_creditplusmodule_productgroup' => Sinkacom\CreditPlusModule\Controller\Admin\CpProductGroup::class,
		'sinkacom_creditplusmodule_productgroup_ajax' => Sinkacom\CreditPlusModule\Controller\Admin\CpProductGroupAjax::class,
		'sinkacom_creditplusmodule_offeredoption' => Sinkacom\CreditPlusModule\Controller\Admin\CpOfferedOption::class,
		'sinkacom_creditplusmodule_offeredoption_ajax' => Sinkacom\CreditPlusModule\Controller\Admin\CpOfferedOptionAjax::class,
		'sinkacom_creditplusmodule_orderarticle' => Sinkacom\CreditPlusModule\Controller\Admin\CpOrderArticle::class,
		'sinkacom_creditplusmodule_orderdetails' => Sinkacom\CreditPlusModule\Controller\Admin\CpOrderDetails::class,
		'sinkacom_creditplusmodule_orderlist' => Sinkacom\CreditPlusModule\Controller\Admin\CpOrderList::class,
		'sinkacom_creditplusmodule_ordermain' => Sinkacom\CreditPlusModule\Controller\Admin\CpOrderMain::class,
		'sinkacom_creditplusmodule_restartorder' => Sinkacom\CreditPlusModule\Controller\RestartOrder::class,
		'sinkacom_creditplusmodule_trigger' => Sinkacom\CreditPlusModule\Controller\Trigger::class,
	),
	'templates' => array(
		'sccp_install.tpl' => 'sc/sccp/application/views/sccp_install.tpl',
		'sccp_trigger.tpl' => 'sc/sccp/application/views/sccp_trigger.tpl',
		'sccp_checkout_iframed.tpl' => 'sc/sccp/application/views/sccp_checkout_iframed.tpl',
		'sccp_checkout_popup.tpl' => 'sc/sccp/application/views/sccp_checkout_popup.tpl',
		'sccp_checkout_finished.tpl' => 'sc/sccp/application/views/sccp_checkout_finished.tpl',
		'sccp_demo.tpl' => 'sc/sccp/application/views/sccp_demo.tpl',
		'sccp_cporder_list.tpl' => 'sc/sccp/application/views/admin/sccp_cporder_list.tpl',
		'sccp_cporder_main.tpl' => 'sc/sccp/application/views/admin/sccp_cporder_main.tpl',
		'sccp_cporder_details.tpl' => 'sc/sccp/application/views/admin/sccp_cporder_details.tpl',
		'sccp_cporder_article.tpl' => 'sc/sccp/application/views/admin/sccp_cporder_article.tpl',
		'sccp_cpproduct_group.tpl' => 'sc/sccp/application/views/admin/sccp_cpproduct_group.tpl',
		'popups/sccp_cpproduct_group.tpl' => 'sc/sccp/application/views/admin/popups/sccp_cpproduct_group.tpl',
		'sccp_cpoffered_option.tpl' => 'sc/sccp/application/views/admin/sccp_cpoffered_option.tpl',
		'popups/sccp_cpoffered_option.tpl' => 'sc/sccp/application/views/admin/popups/sccp_cpoffered_option.tpl',
		'inc/sccp_cporder_errorbox.tpl' => 'sc/sccp/application/views/admin/inc/sccp_cporder_errorbox.tpl',
		'sccp_restart_order.tpl' => 'sc/sccp/application/views/sccp_restart_order.tpl',
		'sccp_restart_order_showInfo.tpl' => 'sc/sccp/application/views/sccp_restart_order_showInfo.tpl',
		'sccp_cpexcluded_articles_detail.tpl' => 'sc/sccp/application/views/admin/sccp_cpexcluded_articles_detail.tpl',
		'sccp_cpexcluded_articles_list.tpl' => 'sc/sccp/application/views/admin/sccp_cpexcluded_articles_list.tpl',
		'sccp_cpexcluded_articles_main.tpl' => 'sc/sccp/application/views/admin/sccp_cpexcluded_articles_main.tpl',
		'email/html/sc_statechange_creditplus_accepted.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_accepted.tpl',
		'email/html/sc_statechange_creditplus_approved.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_approved.tpl',
		'email/html/sc_statechange_creditplus_approved_and_sent.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_approved_and_sent.tpl',
		'email/html/sc_statechange_creditplus_cancelled.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_cancelled.tpl',
		'email/html/sc_statechange_creditplus_declined_hard.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_declined_hard.tpl',
		'email/html/sc_statechange_creditplus_declined_soft.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_declined_soft.tpl',
		'email/html/sc_statechange_creditplus_docs_received.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_docs_received.tpl',
		'email/html/sc_statechange_creditplus_error.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_error.tpl',
		'email/html/sc_statechange_creditplus_paid.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_paid.tpl',
		'email/html/sc_statechange_creditplus_processing_payment.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_processing_payment.tpl',
		'email/html/sc_statechange_creditplus_referred.tpl' => 'sc/sccp/application/views/email/html/sc_starechange_creditplus_referred.tpl',
		'email/html/sc_statechange_default.tpl' => 'sc/sccp/application/views/email/html/sc_statechange_default.tpl',
		'email/plain/sc_statechange_creditplus_accepted.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_accepted.tpl',
		'email/plain/sc_statechange_creditplus_approved.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_approved.tpl',
		'email/plain/sc_statechange_creditplus_approved_and_sent.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_approved_and_sent.tpl',
		'email/plain/sc_statechange_creditplus_cancelled.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_cancelled.tpl',
		'email/plain/sc_statechange_creditplus_declined_hard.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_declined_hard.tpl',
		'email/plain/sc_statechange_creditplus_declined_soft.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_declined_soft.tpl',
		'email/plain/sc_statechange_creditplus_docs_received.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_docs_received.tpl',
		'email/plain/sc_statechange_creditplus_error.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_error.tpl',
		'email/plain/sc_statechange_creditplus_paid.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_paid.tpl',
		'email/plain/sc_statechange_creditplus_processing_payment.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_processing_payment.tpl',
		'email/plain/sc_statechange_creditplus_referred.tpl' => 'sc/sccp/application/views/email/plain/sc_starechange_creditplus_referred.tpl',
		'email/plain/sc_statechange_default.tpl' => 'sc/sccp/application/views/email/plain/sc_statechange_default.tpl',
	),
	'settings' => array(
		'bTestMode' => array(
			'name' => 'bTestMode',
			'type' => 'bool',
			'value' => '1',
			'group' => 'features',
		),
		'sCPDealer' => array(
			'name' => 'sCPDealer',
			'type' => 'str',
			'value' => '500276',
			'group' => 'features',
		),
		'sDealerName' => array(
			'name' => 'sDealerName',
			'type' => 'str',
			'value' => 'WebFin Demo Hdl',
			'group' => 'features',
		),
		'sSoapUser' => array(
			'name' => 'sSoapUser',
			'type' => 'str',
			'value' => 'testwebshop-500276',
			'group' => 'features',
		),
		'sSoapPass' => array(
			'name' => 'sSoapPass',
			'type' => 'str',
			'value' => '',
			'group' => 'features',
		),
		'sSoapType' => array(
			'name' => 'sSoapType',
			'type' => 'select',
			'value' => 'PasswordDigest',
			//'constraints' => 'PasswordDigest|Plain',
			'constraints' => 'PasswordDigest',
			'group' => 'features',
		),
		'sPartnerName' => array(
			'name' => 'sPartnerName',
			'type' => 'str',
			'value' => '',
			'group' => 'features',
		),
		'sWSDL' => array(
			'name' => 'sWSDL',
			'type' => 'str',
			'value' => 'https://kessdemo.creditplus.de/ws_webshop/cxf/Webshop?wsdl',
			'group' => 'features',
		),
		'sCPShownAs' => array(
			'name' => 'sCPShownAs',
			'type' => 'select',
			'value' => 'popup',
			'constraints' => 'popup|iframe',
			'group' => 'features',
		),
		// sPrivKey and sPubKey as well as sDefaultFinancingTime have been removed
		// sRedirectURL has been removed
		// sFinancingMonths and sEffZins have been replaced from here to their Shop Settings -> CP Interest Table
		'sMinRate' => array(
			'name' => 'sMinRate',
			'type' => 'str',
			'value' => '25.00',
			'group' => 'features',
		),
		'sTransactionMode' => array(
			'name' => 'sTransactionMode',
			'type' => 'select',
			'value' => 'postorder',
			'constraints' => 'postorder|inorder',
			'group' => 'features',
		),
		'sSignatureSalt' => array(
			'name' => 'sSignatureSalt',
			'type' => 'str',
			'value' => '',
			'group' => 'features',
		),
		'sBasketFinancingMode' => array(
			'name' => 'sBasketFinancingMode',
			'type' => 'select',
			'value' => 'cheapest',
			'constraints' => 'cheapest',
			//'constraints' => 'most-expensive|weighted-majority|number-majority|cheapest', #11894
			'group' => 'features',
		),
		'bShowDetails' => array(
			'name' => 'bShowDetails',
			'type' => 'bool',
			'value' => '1',
			'group' => 'features',
		),
		'bShowBasket' => array(
			'name' => 'bShowBasket',
			'type' => 'bool',
			'value' => '1',
			'group' => 'features',
		),
		'bShowPayment' => array(
			'name' => 'bShowPayment',
			'type' => 'bool',
			'value' => '1',
			'group' => 'features',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_referred'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_referred'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_referred'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_referred'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_accepted'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_accepted'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_accepted'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_accepted'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_approved_and_sent'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_approved_and_sent'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_approved_and_sent'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_approved_and_sent'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_approved'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_approved'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_approved'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_approved'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_docs_received'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_docs_received'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_docs_received'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_docs_received'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_processing_payment'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_processing_payment'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_processing_payment'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_processing_payment'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_declined_soft'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_declined_soft'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_declined_soft'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_declined_soft'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_declined_hard'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_declined_hard'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_declined_hard'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_declined_hard'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_paid'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_paid'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_paid'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_paid'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_cancelled'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_cancelled'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_cancelled'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_cancelled'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_Recipient_'.substr(md5('creditplus_error'),0,7) => array(
			'name' => 'sStateMail_Recipient_'.substr(md5('creditplus_error'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),
		'sStateMail_RecipientName_'.substr(md5('creditplus_error'),0,7) => array(
			'name' => 'sStateMail_RecipientName_'.substr(md5('creditplus_error'),0,7),
			'type' => 'str',
			'value' => '',
			'group' => 'mails',
		),

	),
	'blocks' => array(
		array(
			'template' => 'page/details/inc/tabs.tpl',
			'block' => 'details_tabs_main',
			'file' => 'application/views/blocks/sccp_page_details_inc_tabs-details_tabs_main.tpl'
		),
		array(
			'template' => 'page/details/inc/productmain.tpl',
			'block' => 'details_productmain_tobasket',
			'file' => 'application/views/blocks/sccp_page_details_inc_productmain-details_productmain_tobasket.tpl'
		),
		array(
			'template' => 'headitem.tpl',
			'block' => 'admin_headitem_inccss',
			'file' => 'application/views/blocks/sccp_admin_headitem-admin_headitem_incss.tpl'
		),
		array(
			'template' => 'order_main.tpl',
			'block' => 'admin_order_main_form',
			'file' => 'application/views/blocks/sccp_admin_order_main-admin_order_main_form.tpl'
		),
		array(
			'template' => 'order_article.tpl',
			'block' => 'admin_order_article_total',
			'file' => 'application/views/blocks/sccp_admin_order_article-admin_order_article_total.tpl'
		),
		array(
			'template' => 'order_address.tpl',
			'block' => 'admin_order_address_billing',
			'file' => 'application/views/blocks/sccp_admin_order_address-admin_order_address_billing.tpl'
		),
		array(
			'template' => 'order_overview.tpl',
			'block' => 'admin_order_overview_send_form',
			'file' => 'application/views/blocks/sccp_admin_order_overview-admin_order_overview_send_form.tpl'
		),
		array(
			'template' => 'order_list.tpl',
			'block' => 'admin_order_list_item',
			'file' => 'application/views/blocks/sccp_admin_order_list-admin_order_list_item.tpl'
		),
		array(
			'template' => 'page/checkout/thankyou.tpl',
			'block' => 'checkout_thankyou_proceed',
			'file' => 'application/views/blocks/sccp_page_checkout_thankyou-checkout_thankyou_proceed.tpl'
		),
		array(
			'template' => 'bottomnaviitem.tpl',
			'block' => 'admin_bottomnavicustom',
			'file' => 'application/views/blocks/sccp_admin_bottomnaviitem-admin_bottomnavicustom.tpl'
		),
		array(
			'template' => 'page/checkout/inc/payment_other.tpl',
			'block' => 'checkout_payment_longdesc',
			'file' => 'application/views/blocks/sccp_page_checkout_inc_payment_other-checkout_payment_longdesc.tpl'
		),
		array(
			'template' => 'page/details/inc/productmain.tpl',
			'block' => 'details_productmain_weight',
			'file' => 'application/views/blocks/sccp_page_details_inc_productmain-details_productmain_weight.tpl'
		),
		array(
			'template' => 'page/checkout/basket.tpl',
			'block' => 'checkout_basket_next_step_bottom',
			'file' => 'application/views/blocks/sccp_page_checkout_basket-checkout_basket_next_step_bottom.tpl'
		),
		array(
			'template' => 'page/checkout/payment.tpl',
			'block' => 'checkout_payment_errors',
			'file' => 'application/views/blocks/sccp_page_checkout_payment-checkout_payment_errors.tpl'
		),
		array(
			'template' => 'page/account/order.tpl',
			'block' => 'account_order_history',
			'file' => 'application/views/blocks/sccp_page_account_order-account_order_history.tpl'
		),
		array(
			'template' => 'email/html/order_cust.tpl',
			'block' => 'email_html_order_cust_paymentinfo_top',
			'file' => 'application/views/blocks/sccp_email_html_order_cust-email_html_order_cust_paymentinfo_top.tpl'
		),
		array(
			'template' => 'email/html/order_owner.tpl',
			'block' => 'email_html_order_owner_paymentinfo',
			'file' => 'application/views/blocks/sccp_email_html_order_owner-email_html_order_owner_paymentinfo.tpl'
		),
		array(
			'template' => 'email/plain/order_cust.tpl',
			'block' => 'email_plain_order_cust_paymentinfo',
			'file' => 'application/views/blocks/sccp_email_plain_order_cust-email_plain_order_cust_paymentinfo.tpl'
		),
		array(
			'template' => 'email/plain/order_owner.tpl',
			'block' => 'email_plain_order_ownerpaymentinfo',
			'file' => 'application/views/blocks/sccp_email_plain_order_owner-email_plain_order_ownerpaymentinfo.tpl'
		)
	)
);

// Version specific templates
try {
	/** @var oxModule $this */
	if ( $oConfig = $this->getConfig() ) {
		$oShop = $oConfig->getActiveShop();
		$sShopType = $oShop->oxshops__oxedition->value;
		$sShopVersion = $oShop->oxshops__oxversion->value;
		$sShopFolder = '';
		// Back to CE counting
		if ( $sShopType == 'EE' ) {
			if ( version_compare($sShopVersion,'5.0.0', '>=') && version_compare($sShopVersion,'5.1.0','<') ) {
				$sShopVersion = '4.7.0';
			} elseif ( version_compare($sShopVersion,'5.1.0', '>=') && version_compare($sShopVersion,'5.2.0','<') ) {
				$sShopVersion = '4.8.0';
			} elseif ( version_compare($sShopVersion,'5.2.0', '>=') && version_compare($sShopVersion,'5.3.0','<') ) {
				$sShopVersion = '4.9.0';
			} elseif ( version_compare($sShopVersion,'5.3.0', '>=') && version_compare($sShopVersion,'5.4.0','<') ) {
				$sShopVersion = '4.10.0';
			} else {
				// To be continued...
			}
		}
		if ( version_compare($sShopVersion,'4.7.0', '>=') && version_compare($sShopVersion,'4.8.0','<') ) {
			$sShopFolder = '407';
			$aModule['blocks'][] = array(
				'template' => 'page/checkout/basket.tpl',
				'block' => 'basket_btn_next_bottom',
				'file' => 'application/views/blocks_407/sccp_page_checkout_basket-basket_btn_next_bottom.tpl'
			);
			foreach ( $aModule['blocks'] as $sKey => $aBlock ) {
				// 'email_html_order_cust_paymentinfo_top' = 'email_html_order_cust_paymentinfo' in 4.7
				if ( ($aBlock['template'] == 'email/html/order_cust.tpl') && ($aBlock['block'] == 'email_html_order_cust_paymentinfo_top') ) {
					$aBlock['block'] = 'email_html_order_cust_paymentinfo';
					$aModule['blocks'][$sKey] = $aBlock;
				}
			}
		} elseif ( version_compare($sShopVersion,'4.8.0', '>=') && version_compare($sShopVersion,'4.9.0','<') ) {
			$sShopFolder = '408';
			$aModule['blocks'][] = array(
				'template' => 'page/checkout/basket.tpl',
				'block' => 'basket_btn_next_bottom',
				'file' => 'application/views/blocks_408/sccp_page_checkout_basket-basket_btn_next_bottom.tpl'
			);
		} elseif ( version_compare($sShopVersion,'4.9.0', '>=') && version_compare($sShopVersion,'4.10.0','<') ) {
			$sShopFolder = '409';
		} elseif ( version_compare($sShopVersion,'4.10.0', '>=') && version_compare($sShopVersion,'4.11.0','<') ) {
			$sShopFolder = '410';
		} else {
			// To be continued...
		}
		// Map to version specific folder
		if ( $sShopFolder ) {
			foreach ( $aModule['blocks'] as $sKey => $aBlock ) {
				$sFile = $aBlock['file'];
				$sFile = str_replace('/views/blocks/', '/views/blocks_'.$sShopFolder.'/', $sFile);
				if ( is_file(__DIR__.'/'.$sFile) ) {
					$aBlock['file'] = $sFile;
					$aModule['blocks'][$sKey] = $aBlock;
				}
			}
		}
		if ( !class_exists('sccp_updater') ) {
			include_once(dirname(__FILE__) . '/application/controllers/Updater.php');
		}
		sccp_updater::updateModuleData($aModule, $sShopVersion, $oConfig);
	}
} catch (\Exception $oEx) {
	// This is not called from an object context
}
