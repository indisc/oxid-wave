[{$smarty.block.parent}]
[{if $oView->getClassName() == "googlecats_main" || $oView->getClassName() == "campaigns_export" || $oView->getClassName() == "allexport_main"}]
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('net_google_mc','out/admin/src/js/jquery.min.js')}]"></script>
[{/if}]
[{if $oView->getClassName() == "googlecats_main"}]
    <script type="text/javascript" src="[{$oViewConf->getModuleUrl('net_google_mc','out/admin/src/js/jstree.min.js')}]"></script>
[{/if}]
