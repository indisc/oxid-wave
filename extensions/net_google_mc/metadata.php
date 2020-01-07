<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id' => 'net_google_mc',
    'title' => 'Netensio: Google Merchant Center',
    'description' => array(
        'de' => 'Unter Einstellungen k&ouml;nnen Sie das Modul konfigurieren.<p>Die Google Merchant Center Schnittstelle f&uuml;r den Oxid eShop erm&ouml;glicht einfaches Kategoriesieren und Exportieren Ihrer Artikel und Kategorien im Google leserlichen Datenformat.</p><p>Support erhalten Sie <a href="https://www.netensio.de"><strong>hier</strong></a>.</p>',
        'en' => 'In the settings area you can configure the extension.',
    ),
    'thumbnail' => 'netensio.jpg',
    'version' => '2.4.3',
    'author' => 'Netensio',
    'url' => 'https://www.netensio.de',
    'email' => 'info@netensio.de',
    'extend' => array(
        \OxidEsales\Eshop\Application\Model\Maintenance::class      =>      \Netensio\GoogleMerchantCenter\Model\Maintenance::class,
    ),
    'controllers' => array(
        'article_netgooglemc'       =>      \Netensio\GoogleMerchantCenter\Controller\Admin\ArticleGoogleMerchantCenter::class,
        'campaigns'                 =>      \Netensio\GoogleMerchantCenter\Controller\Admin\CampaignsController::class,
        'campaigns_list'            =>      \Netensio\GoogleMerchantCenter\Controller\Admin\CampaignsList::class,
        'campaigns_main'            =>      \Netensio\GoogleMerchantCenter\Controller\Admin\CampaignsMain::class,
        'campaigns_main_ajax'       =>      \Netensio\GoogleMerchantCenter\Controller\Admin\CampaignsMainAjax::class,
        'campaigns_export'          =>      \Netensio\GoogleMerchantCenter\Controller\Admin\CampaignsExport::class,
        'category_netgooglemc'      =>      \Netensio\GoogleMerchantCenter\Controller\Admin\CategoryGoogleMerchantCenter::class,
        'googlecats'                =>      \Netensio\GoogleMerchantCenter\Controller\Admin\GooglecatsController::class,
        'googlecats_list'           =>      \Netensio\GoogleMerchantCenter\Controller\Admin\GooglecatsList::class,
        'googlecats_main'           =>      \Netensio\GoogleMerchantCenter\Controller\Admin\GooglecatsMain::class,
        'googlecats_main_ajax'      =>      \Netensio\GoogleMerchantCenter\Controller\Admin\GooglecatsMainAjax::class,

      /*'netcampaigns'              => 'netensio/net_google_mc/models/netcampaigns.php',
        'netcampaignslist'          => 'netensio/net_google_mc/models/netcampaignslist.php',
        'netexportgmc'              => 'netensio/net_google_mc/models/netexportgmc.php',*/

        'allexport'                 =>      \Netensio\GoogleMerchantCenter\Controller\Admin\AllexportController::class,
        'allexport_list'            =>      \Netensio\GoogleMerchantCenter\Controller\Admin\AllexportList::class,
        'allexport_main'            =>      \Netensio\GoogleMerchantCenter\Controller\Admin\AllexportMain::class,
        'gmclog'                    =>      \Netensio\GoogleMerchantCenter\Controller\Admin\LogController::class,
        'gmclog_list'               =>      \Netensio\GoogleMerchantCenter\Controller\Admin\LogList::class,
        'gmclog_main'               =>      \Netensio\GoogleMerchantCenter\Controller\Admin\LogMain::class,

      /*'netgmclog'                 => 'netensio/net_google_mc/models/netgmclog.php',
        'netgmcloglist'             => 'netensio/net_google_mc/models/netgmcloglist.php',*/

    ),
    'events' => array(
        'onActivate'                =>      '\Netensio\GoogleMerchantCenter\Core\Events::onActivate',
    ),
    'templates' => array(
        'campaigns.tpl'                     => 'netensio/net_google_mc/views/admin/tpl/campaigns.tpl',
        'campaigns_list.tpl'                => 'netensio/net_google_mc/views/admin/tpl/campaigns_list.tpl',
        'campaigns_main.tpl'                => 'netensio/net_google_mc/views/admin/tpl/campaigns_main.tpl',
        'popups/campaigns_main.tpl'         => 'netensio/net_google_mc/views/admin/tpl/popups/campaigns_main.tpl',
        'campaigns_export.tpl'              => 'netensio/net_google_mc/views/admin/tpl/campaigns_export.tpl',
        'googlecats.tpl'                    => 'netensio/net_google_mc/views/admin/tpl/googlecats.tpl',
        'googlecats_list.tpl'               => 'netensio/net_google_mc/views/admin/tpl/googlecats_list.tpl',
        'googlecats_main.tpl'               => 'netensio/net_google_mc/views/admin/tpl/googlecats_main.tpl',
        'popups/googlecats_main.tpl'        => 'netensio/net_google_mc/views/admin/tpl/popups/googlecats_main.tpl',
        'article_netgooglemc.tpl'           => 'netensio/net_google_mc/views/admin/tpl/article_netgooglemc.tpl',
        'category_netgooglemc.tpl'          => 'netensio/net_google_mc/views/admin/tpl/category_netgooglemc.tpl',
        'allexport.tpl'                     => 'netensio/net_google_mc/views/admin/tpl/allexport.tpl',
        'allexport_list.tpl'                => 'netensio/net_google_mc/views/admin/tpl/allexport_list.tpl',
        'allexport_main.tpl'                => 'netensio/net_google_mc/views/admin/tpl/allexport_main.tpl',
        'gmclog.tpl'                        => 'netensio/net_google_mc/views/admin/tpl/gmclog.tpl',
        'gmclog_list.tpl'                   => 'netensio/net_google_mc/views/admin/tpl/gmclog_list.tpl',
        'gmclog_main.tpl'                   => 'netensio/net_google_mc/views/admin/tpl/gmclog_main.tpl',
    ),
    'blocks' => array(
        array('template' => 'headitem.tpl', 'block' => 'admin_headitem_incjs', 'file'=>'/views/blocks/admin_headitem_incjs.tpl'),
        array('template' => 'headitem.tpl', 'block' => 'admin_headitem_inccss', 'file'=>'/views/blocks/admin_headitem_inccss.tpl'),
        array('template' => 'page/details/inc/productmain.tpl', 'block' => 'details_productmain_tobasket', 'file' => '/views/blocks/details_productmain_tobasket.tpl'),
    ),
    'settings' => array(
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcTicksize', 'type' => 'str',  'value' => '100'),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcWithVariants', 'type' => 'bool',  'value' => 'true'),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcBrandSelect',  'type' => 'select', 'constrains' => 'Manufacturer|Vendor', 'value' => 'Manufacturer'),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetShpWeightUnit',  'type' => 'select', 'constrains' => 'kg|g', 'value' => 'kg'),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcDescSelect', 'type' => 'select', 'constrains' => 'shortdesc|longdesc', 'value' => 'longdesc' ),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcMainPic', 'type' => 'select', 'constrains' => '1|2|3|4|5|6|7|8|9|10|11', 'value' => '1' ),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcFeedIdField',  'type' => 'select', 'constrains' => 'oxid|oxartnum', 'value' => 'oxid'),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcFremdlager', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCEXPORTCONFIG', 'name' => 'sNetGmcReplaceComma', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcUseTtlPrefix', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcTtlPrefixVal', 'type' => 'str',  'value' => ''),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcTtlPrefixDiv', 'type' => 'str',  'value' => ''),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcUseTtlSuffix', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcTtlSuffixVal', 'type' => 'str',  'value' => ''),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcTtlSuffixDiv', 'type' => 'str',  'value' => ''),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcField2Title', 'type' => 'select', 'constrains' => 'false|oxartnum|oxean', 'value' => 'false' ),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcTtlNumberize', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCTITLECONFIG', 'name' => 'sNetGmcTtlTruncate', 'type' => 'str',  'value' => ''),
        array('group' => 'NETGMCMETACONFIG', 'name' => 'sNetGmcMetaGlobal', 'type' => 'select', 'constrains' => 'on|off', 'value' => 'off' ),
        array('group' => 'NETGMCCHARCONFIG', 'name' => 'sNetGmcCharLongdesc', 'type' => 'select', 'constrains' => 'nothing|utf8_encode|utf8_decode', 'value' => 'nothing' ),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogging', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogBuyable', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogZeroPrice', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogTitle', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogDesc', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogGooglecat', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogArtUrl', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogImage', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogIdentifier', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogAdult', 'type' => 'bool',  'value' => 'false'),
        array('group' => 'NETGMCLOGCONFIG', 'name' => 'sNetGmcLogCondition', 'type' => 'bool',  'value' => 'false'),
    )
);