<?php
/**
 * Backend Konstanten für die Sprachlabels
 */

$sLangName = "English";

$aLang = array(
	"charset" => "ISO-8859-1", // Supports chars like: ê, í, ß, etc.

	// Module Settings
	'SHOP_MODULE_GROUP_features' => 'Features',
	'SHOP_MODULE_GROUP_mails' => 'E-Mails',
	'SHOP_MODULE_bTestMode' => 'Activate test mode',
	'SHOP_MODULE_sCPDealer' => 'CreditPlus merchant number',
	'SHOP_MODULE_sDealerName' => 'Merchant name',
	'SHOP_MODULE_sSoapUser' => 'API username',
	'SHOP_MODULE_sSoapPass' => 'API password',
	'SHOP_MODULE_sSoapType' => 'API password encryption',
	'SHOP_MODULE_sSoapType_PasswordDigest' => 'PasswordDigest',
	'SHOP_MODULE_sSoapType_Plain' => 'Plain text',
	'SHOP_MODULE_sPartnerName' => 'Partner name',
	'SHOP_MODULE_sWSDL' => 'WSDL URL',
	'SHOP_MODULE_sCPShownAs' => 'Display as',
	'SHOP_MODULE_sCPShownAs_iframe' => 'Iframe',
	'SHOP_MODULE_sCPShownAs_popup' => 'Popup',
	'SHOP_MODULE_sRedirectURL' => 'Target URL for CreditPlus System',
	'SHOP_MODULE_sMinRate' => 'Minimum rate',
	'SHOP_MODULE_sTransactionMode' => 'When the payment happens',
	'SHOP_MODULE_sTransactionMode_postorder' => 'After order is complete',
	'SHOP_MODULE_sTransactionMode_inorder' => 'Before order is complete',
	'SHOP_MODULE_sSignatureSalt' => 'Shared Secret (Used for signing data)',
	'SHOP_MODULE_sBasketFinancingMode' => 'Basket financing interest',
	'SHOP_MODULE_sBasketFinancingMode_most-expensive' => 'Most expensive',
	'SHOP_MODULE_sBasketFinancingMode_weighted-majority' => 'Weighted majority',
	'SHOP_MODULE_sBasketFinancingMode_number-majority' => 'Number majority',
	'SHOP_MODULE_sBasketFinancingMode_cheapest' => 'Cheapest',
	'SHOP_MODULE_bShowDetails' => 'Display rate table on article details',
	'SHOP_MODULE_bShowBasket' => 'Display rate table on basket page',
	'SHOP_MODULE_bShowPayment' => 'Display rate table on payment page',
	'SHOP_MODULE_sStateMail_Recipient_0fa7b9d' => 'E-Mail Adress for state Referred',
	'SHOP_MODULE_sStateMail_Recipient_d3405ba' => 'E-Mail Adress for state Accepted',
	'SHOP_MODULE_sStateMail_Recipient_4d09724' => 'E-Mail Adress for state Approved and Sent',
	'SHOP_MODULE_sStateMail_Recipient_3a863ad' => 'E-Mail Adress for state Approved',
	'SHOP_MODULE_sStateMail_Recipient_6a4543d' => 'E-Mail Adress for state Documentes received',
	'SHOP_MODULE_sStateMail_Recipient_5b6a24f' => 'E-Mail Adress for state Payment in progress',
	'SHOP_MODULE_sStateMail_Recipient_1b138f7' => 'E-Mail Adress for state Declined (Soft)',
	'SHOP_MODULE_sStateMail_Recipient_b91d4ad' => 'E-Mail Adress for state Declined (Hard)',
	'SHOP_MODULE_sStateMail_Recipient_a4327c3' => 'E-Mail Adress for state Paid',
	'SHOP_MODULE_sStateMail_Recipient_d7e38be' => 'E-Mail Address for state Cancelled',
	'SHOP_MODULE_sStateMail_Recipient_ae1012d' => 'E-Mail Address for state Error',
	'SHOP_MODULE_sStateMail_RecipientName_0fa7b9d' => 'Name for state Referred',
	'SHOP_MODULE_sStateMail_RecipientName_d3405ba' => 'Name for state Accepted',
	'SHOP_MODULE_sStateMail_RecipientName_4d09724' => 'Name for state Approved and Sent',
	'SHOP_MODULE_sStateMail_RecipientName_3a863ad' => 'Name for state Approved',
	'SHOP_MODULE_sStateMail_RecipientName_6a4543d' => 'Name for state Documents received',
	'SHOP_MODULE_sStateMail_RecipientName_5b6a24f' => 'Name for state Payment in progress',
	'SHOP_MODULE_sStateMail_RecipientName_1b138f7' => 'Name for state Declined (Soft)',
	'SHOP_MODULE_sStateMail_RecipientName_b91d4ad' => 'Name for state Declined (Hard)',
	'SHOP_MODULE_sStateMail_RecipientName_a4327c3' => 'Name for state Paid',
	'SHOP_MODULE_sStateMail_RecipientName_d7e38be' => 'Name for state Cancelled',
	'SHOP_MODULE_sStateMail_RecipientName_ae1012d' => 'Name for state Error',

	//Backend Menu
	'mxsccpcporder' => 'CreditPlus Orders',
	'mxsccpcpproductgroup' => 'CP product groups',
	'mxsccpcpofferedoptions' => 'CP Interest table',
	'mxsccpexcludedarticles' => 'CP Exclusions',
	'tbclsccp_order_details' => 'Details',
	'tbclsccp_order_article' => 'Article',
	'tbsccpcpexcludedarticlesdetail' => 'Excluded Articles',

	//Backend List
	'CPORDER_LIST_TRANSID' => 'Request ID',
	'CPORDER_LIST_MENUITEM' => 'Manage financing',
	'CPORDER_LIST_MENUSUBITEM' => 'Financing details',
	'CPORDER_DETAIL_NOTARRIVED_AT_BANK' => 'The financing request has not yet arrived at the bank, as the customer has not yet completed the process in the frontend. Thus the data is not yet available.',
	'CPORDER_DETAIL_CONTRACT_SIGNED_ON' => 'Signed by customer on:',
	'CPORDER_DETAIL_CONTRACT_SIGNED_NOT_YET' => 'Not yet signed',
	'CPORDER_DETAIL_CONFIRM_DELIVERY' => 'Confirm delivery',

	'SCCP_FINANCING_PRODUCT_GROUPS_SAVED' => 'Saved procut groups',
	'SCCP_FINANCING_PRODUCT_GROUPS_NUMBER' => 'Number',
	'SCCP_FINANCING_PRODUCT_GROUPS_NAME' => 'Name',
	'SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTTYPEID' => 'Product type ID',
	'SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTCLASSID' => 'Product class ID',
	'SCCP_FINANCING_PRODUCT_GROUPS_DEFAULT' => 'Default',
	'SCCP_FINANCING_PRODUCT_GROUPS_DELETE' => 'Delete',
	'SCCP_FINANCING_PRODUCT_GROUPS_ASSIGN_ARTICLES' => 'Assign articles',
	'SCCP_FINANCING_PRODUCT_GROUPS_ADD' => 'Create new product group',
	'SCCP_FINANCING_PRODUCT_GROUPS_RUN_INSTALL_FIRST' => 'Please finish the install process first. You should find the correct <a href="/index.php?cl=sccp_install" target="_blank">link for the install script</a> in your install manual, if <a href="/index.php?cl=sccp_install" target="_blank">this one</a> does not work.',



	'SCCP_FINANCING_OFFERED_OPTIONS_MONTHS' => 'Months',
	'SCCP_FINANCING_OFFERED_OPTIONS_INTEREST' => 'Effective interest',
	'SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR' => 'Rate factor',
	'SCCP_FINANCING_OFFERED_OPTIONS_PRODCODE' => 'Grouping name',
	'SCCP_FINANCING_OFFERED_OPTIONS_ACTIVE' => 'Active',
	'SCCP_FINANCING_OFFERED_OPTIONS_DELETE' => 'Delete',
	'SCCP_FINANCING_OFFERED_OPTIONS_ASSIGN_PROD_GROUPS' => 'Assign product groups',
	'SCCP_FINANCING_OFFERED_OPTIONS_SAVED' => 'Options saved',
	'SCCP_FINANCING_OFFERED_OPTIONS_ADD' => 'Create new option',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MINMONTHS' => 'The minimal financing time is 6 monts - Grouping name ###PRODCODE### ###MONTHS### months ###INTEREST###% has automatically been increased to 6 months.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MININTEREST' => 'The minimal financing interest is 0% - Grouping name ###PRODCODE### ###MONTHS### months ###INTEREST###% has automatically been increased to 0%.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MINRATEFACTOR' => 'The rate factor must be between 0 and 1 - Grouping name ###PRODCODE### ###MONTHS### months ###INTEREST###% has been set to automatic calculation.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MAXRATEFACTOR' => 'The rate factor must be between 0 and 1 - Grouping name ###PRODCODE### ###MONTHS### months ###INTEREST###% has been set to automatic calculation.',
	'SCCP_FINANCING_OFFERED_OPTIONS_ERROR_MAXINTEREST' => 'The maximum interest is 17,99% - Grouping name ###PRODCODE### ###MONTHS### months ###INTEREST###% has automatically been reduced to 17,99%. The interest must not exceed the double of the usual interest, also it should not exceed 12% p.a. above. vgl. BGH Urteil vom 11.01.1995, Aktenzeichen: VIII ZR 82/94',
	'SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR_INFO' => 'The Rate factor lies between 0 und 1. Leaving it empty automatically calculates it mathematically.',

	'GENERAL_AJAX_SORT_SCCP_NAME' => 'Name',
	'SCCP_CPOFFERED_OPTIONS_ALLPRODGROUPS' => 'Not assigned product groups',
	'SCCP_CPOFFERED_OPTIONS_ASSIGNEDPRODGROUPS' => 'Assign product groups',

	'SCCP_CPPRODUCT_GROUP_FILTER_CATEGORIES' => 'Filter by category',
	'SCCP_CPPRODUCT_GROUP_DESCRIPTION' => 'Here you can define which product groups exist and you can assign products to these. These groups can then be assigned financing options. Please keep in mind that if a product group has <strong>no assigned</strong> articles, it is considered default and will be used on <strong>every</strong> article. The product groups will only be saved if you click on the "Save" button at the end. You can however already assign articles before that.',
	'SCCP_CPOFFERED_OPTIONS_DESCRIPTION' => 'Here you can define, which financing options will be offered and assign these to product groups. If multiple interest exist for an article at the same amount of months, the <strong>lower</strong> interest will be used. The options will only be saved if you click on the "Save" button. You can however assign product groups before saving them. If you want to define options before publishing them, just deactivate the "active" checkbox. Inactive options will not be displayed to users in the shop. If an option is assigned to a product group which is open for all products, it will be displayed on all products.',

	'SCCP_CPEXCLUDED_ARTICLES_DETAIL_TITLE' => 'Excluded products',
	'SCCP_CPEXCLUDED_ARTICLES_DETAIL_DESCRIPTION' => 'Excluded products',
	'SCCP_CPEXCLUDED_ARTICLES' => 'Excluded products',
	'SCCP_CPEXCLUDED_ARTICLES_DETAIL' => 'Excluded products',
	'SCCP_CPEXCLUDED_ARTICLES_SHOW_UNASSIGNED' => 'Show list of unassigned articles',
	'SCCP_CPEXCLUDED_ARTICLES_SHOW_OPTIONS_FOR' => 'Show list of financing options assigned to',
	'SCCP_CPEXCLUDED_ARTICLES_ARTICLENUMBER' => 'Article number',
	'SCCP_CPEXCLUDED_ARTICLES_SUBMIT' => 'Show',

	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS' => 'The following financing options exist in this shop for the given article number.',
	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_ARTICLE_FOUND' => 'No articles with this article number have been found.',
	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_ARTICLE_REQUESTED' => 'No article number given.',
	'SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_OPTIONS' => 'For this article there are no financing options.',
	'SCCP_CPEXCLUDED_ARTICLES_TD_MONTHS' => 'Months',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_GROUP_NAME' => 'Product group',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_TYPE_ID' => 'Product type ID',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_CLASS_ID' => 'Product class ID',
	'SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_CODE' => 'Financing name',
	'SCCP_CPEXCLUDED_ARTICLES_TD_EFFECTIVE_INTEREST' => 'Effective interest',
	'SCCP_CPEXCLUDED_ARTICLES_TD_MONTHLY_RATE' => 'Monthly rate (ca.)',

	'SCCP_CPEXCLUDED_ARTICLES_DEFAULT_GROUP_ACTIVE' => 'Default groups are active. While the following groups are active no article will be excluded.',
	'SCCP_CPEXCLUDED_ARTICLES_FOLLOWING_ARTICLES_MISSING' => 'The following articles have no financing options.',
	'SCCP_CPEXCLUDED_ARTICLES_TD_ROWNUMBER' => 'Nr.',
	'SCCP_CPEXCLUDED_ARTICLES_TD_ARTICLENUMBER' => 'Articlenumber',
	'SCCP_CPEXCLUDED_ARTICLES_TD_ARTICLETITLE' => 'Title',

	'SCCP_CPORDER_ARTICLE_ERROR_ALREADY_CANCELLED' => 'This article is already cancelled.',
	'SCCP_CPORDER_ARTICLE_ERROR_NO_INCREASE_POSSIBLE' => 'The financing total can not be increased afterwards.',
	'SCCP_CPORDER_ARTICLE_MESSAGE_REDUCED_BY_PRODUCT_PRICE' => 'The financing total has been reduced by the cancelled articles price.',
	'SCCP_CPORDER_ARTICLE_MESSAGE_PARTIALLY_RETURNED' => 'Partially returned.',
	'SCCP_CPORDER_DETAILS_MESSAGE_TOTALLY_RETURNED' => 'Fully returned.',
	'SCCP_CPORDER_DETAILS_ERROR_CANCEL_NOT_AVAILABLE_CREDITPLUS' => 'The order can not be cancelled because of its state at CreditPlus.<br />The order has however been cancelled in the shop.',
	'SCCP_CPORDER_DETAILS_ERROR_ALREADY_CANCELLED_ORDER' => 'The order has already been cancelled.',
	
	'SCCP_ADMIN_CHANGE_IN_CUSTOM_MENU' => 'Editing partially deactivated!<br /><br />To change the greyed out fields and functions<br />
use the appropriate menu item at<br />
Edit orders -&gt; CreditPlus Orders.',
	'SCCP_ADMIN_TOTAL_CHANGE_IN_CUSTOM_MENU' => 'Editing deactivated!<br /><br />To change this part<br />
use the appropriate menu item at<br />
Edit orders -&gt; CreditPlus Orders.',
	'SCCP_ADMIN_KEEP_ADDRESSES_SYNCHRONIZED' => 'Attention!<br /><br />
If you change this part, please inform CreditPlus about this change. 
For one to keep the data synchronized and also to prevent fraud.',
);
