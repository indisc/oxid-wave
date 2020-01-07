[{$smarty.block.parent}]
[{if $oView->getClassName() == "article_netgooglemc" || $oView->getClassName() == "category_netgooglemc" || $oView->getClassName() == "campaigns_export" || $oView->getClassName() == "googlecats_main" || $oView->getClassName() == "allexport_main"}]
    <link rel="stylesheet" type="text/css" href="[{$oViewConf->getModuleUrl('net_google_mc','out/admin/src/css/netgooglemc.css')}]">
[{/if}]