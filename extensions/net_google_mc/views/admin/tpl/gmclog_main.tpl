[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]


<script type="text/javascript">
    <!--
    window.onload = function ()
    {
        //top.reloadEditFrame();
        [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
        [{ /if}]
    }
    //-->
</script>


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


<script type="text/javascript">
<!--

    function editArticle(sID, sClass) {
        var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
        oTransfer.oxid.value = sID;
        oTransfer.cl.value = sClass;
        oTransfer.submit();
        //top.forceReloadingListFrame();
        //forcing edit frame to reload after submit
        //top.forceReloadingEditFrame();

        var oSearch = top.basefrm.list.document.getElementById( "search" );
        oSearch.oxid.value = sID;
        oSearch.submit();
    }

//-->
</script>

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="gmclog_main">
</form>


<form name="myedit" enctype="multipart/form-data" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="gmclog_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[netgmclog__oxid]" value="[{ $oxid }]">
<input type="hidden" name="sorting" value="">
<input type="hidden" name="stable" value="">
<input type="hidden" name="starget" value="">

[{if $oxid == "-1"}]
    <strong>Google Merchant Center Export Log:</strong>
    <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="NET_GMC_TRUNCATE_LOG" }]" onClick="Javascript:document.myedit.fnc.value='truncategmclog'" [{ $readonly }] [{ $disableSharedEdit }]><br><br>

[{/if}]


[{assign var="sHtmlErrorString" value=$edit->netgmclog__oxerrors->value|html_entity_decode}]

[{assign var="aExportErrors" value="<br>"|explode:$sHtmlErrorString}]
<table>
    [{foreach from=$aExportErrors item="sOneExportError"}]
    <tr>
        <td class="edittext" style="color:#ff0000;padding:10px;font-weight:bold;">[{$sOneExportError}]</td>
        <td>
            [{if $sOneExportError eq "Fehler: Artikel ist eine Variante und wurde f&uuml;r den Vaterartikel Export angemeldet"}]

            [{elseif $sOneExportError eq "Fehler: Artikel ist im Shop nicht kaufbar"}]

            [{elseif $sOneExportError eq "Fehler: Preis ist 0.00"}]
                <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_main');">Preis editieren</a>
            [{elseif $sOneExportError eq "Fehler: Artikel besitzt keinen Titel"}]
            <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_main');">Titel editieren</a>
            [{elseif $sOneExportError eq "Fehler: Artikel besitzt keine Beschreibung"}]
            <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_main');">Beschreibung editieren</a>
            [{elseif $sOneExportError eq "Fehler: Keine oder ung&uuml;ltige Google Shopping Kategoriezuordnung gefunden."}]
            <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_netgooglemc');">Auf Artikelebene editieren</a>
            [{elseif $sOneExportError eq "Fehler: Artikel URL konnte nicht ermittelt werden."}]
            <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_seo');">Artikel SEO editieren</a>
            [{elseif $sOneExportError eq "Fehler: Artikel-Bild konnte nicht ermittelt werden."}]
            <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_pictures');">Artikel Bildupload</a>
            [{elseif $sOneExportError eq "Fehler: Artikel ist &#039;nicht auf Lager&#039;."}]
            <a href="Javascript:editArticle('[{$edit->netgmclog__oxarticleid->value}]', 'article_stock');">Lagerbestand editieren</a>
            [{/if}]
        </td>
    </tr>
    [{/foreach}]
</table>

</form>


</div>


<div class="actions">
[{strip}]

  <ul>
    <li>[{*<a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.new" href="#" onClick="Javascript:top.oxid.admin.editThis( -1 );return false" target="edit">[{ oxmultilang ident="NET_TOOLTIPS_NEWgmcCAMPAIGN" }]</a>*}]</li>
  </ul>
[{/strip}]
</div>



[{include file="bottomitem.tpl"}]