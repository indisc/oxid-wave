[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{ if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]



<script type="text/javascript">
<!--

function DeletePic( sField )
{
    var oForm = document.getElementById("myedit");
    document.getElementById(sField).value="";
    oForm.fnc.value='save';
    oForm.submit();
}

//-->
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="campaigns_export">
</form>


<h1>[{oxmultilang ident="NET_GOOGLE_MC_CAMPAIGN_EXPORT_HEADLINE"}]</h1>
<p>[{oxmultilang ident="NET_GOOGLE_MC_CAMPAIGN_EXPORT_PARAGRAPH"}]</p>
<hr>
<form name="myedit" enctype="multipart/form-data" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="campaigns_export">
<input type="hidden" name="fnc" value="netgenexport">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[netcampaigns__oxid]" value="[{ $oxid }]">
    <input type="hidden" name="editval[netcampaigns__oxtype]" value="[{$edit->netcampaigns__oxtype->value}]">
    <input type="hidden" name="editval[iCampaignLanguage]" value="[{$edit->netcampaigns__oxlanguage->value}]">
    <input type="hidden" name="editval[iCampaignCurrency]" value="[{$edit->netcampaigns__oxcurrency->value}]">
    <input type="hidden" name="cur" value="[{$edit->netcampaigns__oxcurrency->value}]">
    <input id="iStart" type="hidden" name="editval[iStart]" value="[{if $iStart}][{$iStart}][{else}]0[{/if}]">
    <input id="iStop" type="hidden" name="editval[iStop]" value="[{if $iStop}][{$iStop}][{else}][{$oView->netGetGmcTicksize()}][{/if}]">
    <input type="hidden" name="editval[sFilename]" value="[{$edit->netcampaigns__oxlongdesc->value}]">
    <table class="article_netgooglemc_table">
        <tr>
            <td class="edittext tdleft"><strong>[{oxmultilang ident="NET_GOOGLE_MC_ACT_FEED"}]</strong></td><td>[{$edit->netcampaigns__oxtitle->value}]</td>
        </tr>
        <tr>
            <td class="edittext tdleft"><strong>[{oxmultilang ident="NET_GOOGLE_MC_ACT_COUNT"}]</strong></td><td>[{$oView->netGetExportCount()}]</td>
        </tr>
        <tr>
            <td class="edittext tdleft"><strong>[{oxmultilang ident="NET_GOOGLE_MC_ACT_COUNTACTIVE"}]</strong></td><td>[{$oView->netGetExportCountActive()}]</td>
        </tr>
        <tr>
            <td class="edittext tdleft"><strong>[{oxmultilang ident="NET_GOOGLE_MC_ACT_FEED_FILENAME"}]</strong></td><td>[{if $edit->netcampaigns__oxlongdesc->value}][{$edit->netcampaigns__oxlongdesc->value}][{else}]<span style="color:#ff0000;">[{oxmultilang ident="NET_GOOGLE_MC_ACT_FEED_NOFILENAME"}]</span>[{/if}]</td>
        </tr>
        <tr>
            <td class="edittext tdleft"><strong>[{oxmultilang ident="NET_GOOGLE_MC_ACT_EXPORT_TYPE"}]</strong></td>
            <td>
                [{if $edit->netcampaigns__oxtype->value == 1}]
                    [{oxmultilang ident="NET_CAMPAIGNS_MAIN_TYPE_GOOGLECSV"}]
                [{elseif $edit->netcampaigns__oxtype->value == 2}]
                    [{oxmultilang ident="NET_CAMPAIGNS_MAIN_TYPE_GOOGLEXML"}]
                [{/if}]
            </td>
        </tr>
        </table>
    <br>
        <table style="width:100%;">
        [{if $iStart && $iStart != 0 && $iProgress}]
            <tr>
                <td>[{oxmultilang ident="NET_GOOGLE_MC_WAIT_EXPORTING"}] [{$iProgress}]%</td>
            </tr>
        [{/if}]
        [{if !$iProgress}]
            [{if $edit->netcampaigns__oxlongdesc->value}]
            <tr>
                <td><input type="submit" value="[{oxmultilang ident="NET_GOOGLE_MC_ACT_FEED_GENERATE"}]"></td>
                <td>&nbsp;</td>
            </tr>
            [{/if}]
        [{elseif $iProgress == 100}]
            <tr>
                <td>Export abgeschlossen. <a href="[{$oView->netGetExportFileUrl($edit->netcampaigns__oxlongdesc->value)}]" target="_blank"><strong>Zum Datenfeed</strong></a>
            </tr>
        [{/if}]
    </table>
</form>


[{oxscript include=$oViewConf->getModuleUrl('net_google_mc','out/admin/src/js/campaigns_export.js') priority=10}]

</div>

<!-- START new promotion button -->
<div class="actions">
    [{if $iStart && $iStart != 0 && $iProgress}]
    <div class="progress"><div class="progress-bar" style="width:[{$iProgress}]%;">[{$iProgress}]%</div></div>
    [{/if}]
[{strip}]

  <ul>
    <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.new" href="#" onClick="Javascript:top.oxid.admin.editThis( -1 );return false" target="edit">[{ oxmultilang ident="NET_TOOLTIPS_NEWCAMPAIGN" }]</a></li>
  </ul>
[{/strip}]
</div>

<!-- END new promotion button -->

[{include file="bottomitem.tpl"}]