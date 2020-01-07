[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
[{assign var="readonly" value="readonly disabled"}]
[{else}]
[{assign var="readonly" value=""}]
[{/if}]



<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{if $campaignsid}][{$campaignsid}][{else}][{$oView->getFirstExportCampaignId()}][{/if}]">
    <input type="hidden" name="cl" value="allexport_main">
</form>


<h1>[{oxmultilang ident="NET_GOOGLE_MC_ALLEXPORT_HEAD"}]</h1>
<p>[{oxmultilang ident="NET_GOOGLE_MC_ALLEXPORT_PARAGRAPH"}]</p>
<hr>

<form name="myedit" enctype="multipart/form-data" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="allexport_main">
    <input type="hidden" name="fnc" value="netgenexport">
    <input type="hidden" name="oxid" value="[{if $campaignsid}][{$campaignsid}][{else}][{$oView->getFirstExportCampaignId()}][{/if}]">
    <input type="hidden" name="campaignsid" value="[{if $campaignsid}][{$campaignsid}][{else}][{$oView->getFirstExportCampaignId()}][{/if}]">
    <input type="hidden" name="count" value="[{if $count}][{$count}][{else}]0[{/if}]">
    <input type="hidden" name="editval[netcampaigns__oxid]" value="[{if $campaignsid}][{$campaignsid}][{else}][{$oView->getFirstExportCampaignId()}][{/if}]">
    <input type="hidden" name="editval[iStart]" value="[{if $iStart}][{$iStart}][{else}]0[{/if}]" id="iStart">
    <input type="hidden" name="editval[iStop]" value="[{if $iStop}][{$iStop}][{else}][{$oView->netGetGmcTicksize()}][{/if}]" id="iStop">

    [{assign var="cycle" value=$count+1}]
    <table style="width:100%;">
        [{if $iStart && $iStart != 0 && $iProgress}]
        <tr>
            <td>[{oxmultilang ident="NET_GOOGLE_MC_WAIT_EXPORTING"}] - Export [{$cycle}] : [{$iProgress}]%</td>
        </tr>
        [{/if}]
        [{if !$iProgress && !$isnext}]
        <tr>
            <td><input type="submit" value="[{oxmultilang ident="NET_GOOGLE_MC_ALLEXPORT_GENERATE"}]"></td>
        </tr>
        [{elseif !$iProgress && $isnext == 1}]
        <tr>
            <td>[{oxmultilang ident="NET_GOOGLE_MC_WAIT_EXPORTING"}] - Export [{$cycle}] : 0%</td>
        </tr>
        [{elseif $iProgress == 100}]
        <tr>
            <td>Exporte abgeschlossen.</td>
        </tr>
        [{/if}]
    </table>
</form>


[{oxscript include=$oViewConf->getModuleUrl('net_google_mc','out/admin/src/js/allexport_export.js') priority=10}]

</div>

<!-- START new promotion button -->
<div class="actions">
    [{if $iStart && $iStart != 0 && $iProgress}]
    <div class="progress"><div class="progress-bar" style="width:[{$iProgress}]%;">[{$iProgress}]%</div></div>
    [{/if}]
</div>

<!-- END new promotion button -->

[{include file="bottomitem.tpl"}]