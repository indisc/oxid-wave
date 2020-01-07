[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign }]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
    [{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="googlecats_main">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>


[{assign var="iCatCountShop" value=$oView->getTaxonomyCountShop()}]


<form name="myupload" id="myupload" action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="googlecats_main">
    <input type="hidden" name="fnc" value="updateGoogleTaxonomy">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
    <p style="margin-bottom:10px;"><strong>[{oxmultilang ident="NET_GOOGLECATS_UPLOAD_HEADER"}]</strong></p>
    <p style="margin-bottom:20px;">[{oxmultilang ident="NET_GOOGLECATS_UPLOAD_DESCRIPTION"}]</p>
    <h4>Schritt 1:</h4>
    <div>
        Bitte w&auml;hlen Sie die Zielsprache im OXID Shop zu der die Google Taxonomie Datei importiert werden soll. Bitte beachten Sie, dass Sprache im Shop und Sprache der Taxonomie Datei identisch sind.<br><br>
        Sprache:
        [{assign var="aLanguages" value=$oView->getShopLanguagesArray()}]
        <select name="taxonomylanguage">
            [{foreach from=$aLanguages item="oLang" name="languages"}]
                <option value="[{$oLang->id}]" [{if $edit->netcampaigns__oxlanguage->value eq $oLang->id}]selected[{/if}]>[{$oLang->name}]</option>
            [{/foreach}]
        </select>
    </div>
    <h4>Schritt 2:</h4>
    <div>Bitte w&auml;hlen Sie die Taxonomie Datei f&uuml;r den Import in der unter Schritt 1 gew&auml;hlten Spracheinstellung aus und starten Sie den Import mittels Klick auf den Upload Button.<br><br>
        <input type="file" name="taxonomyfile" />
    </div>
    <div>
        <input type="submit" value="Upload">
    </div>

</form>
<hr />

[{if $iCatCountShop}]
<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="googlecats_main">
    <input type="hidden" name="fnc" value="save">
    <input type="hidden" name="actshop" value="[{$oViewConf->getActiveShopId()}]">
    <input type="hidden" name="updatenav" value="">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
    <p style="margin-bottom:10px;"><strong>[{oxmultilang ident="NET_GOOGLECATS_ASSIGN_HEADER"}]</strong></p>
    <p style="margin-bottom:20px;">[{oxmultilang ident="NET_GOOGLECATS_ASSIGN_DESCRIPTION"}]</p>
    <input type="button" value="Google Kategorien zu Ihrem Shop zuordnen" class="edittext" onclick="JavaScript:showDialog('&cl=googlecats_main&aoc=1&oxid=[{ $oxid }]');" [{ $readonly }]>

</form>
[{*oxscript include=$oViewConf->getModuleUrl('net_google_mc','out/admin/src/js/widget-tree.js') priority=10*}]

[{/if}]
[{include file="bottomitem.tpl"}]
