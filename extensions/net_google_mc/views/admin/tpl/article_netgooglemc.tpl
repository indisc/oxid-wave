[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
    oSearch.submit();
}
[{if !$oxparentid}]
window.onload = function ()
{
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
    var oField = top.oxid.admin.getLockTarget();
    oField.onchange = oField.onkeyup = oField.onmouseout = top.oxid.admin.unlockSave;
}
[{/if}]
//-->
</script>

[{ if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="oxidCopy" value="[{$oxid}]">
    <input type="hidden" name="cl" value="article_netgooglemc">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>
<h1>Google Merchant Center:</h1>
<p>[{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_TABDESC"}]</p>
<hr>
      <table cellspacing="0" cellpadding="0" border="0" style="width:98%;">
        <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" style="padding: 0px;margin: 0px;height:0px;">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="article_netgooglemc">
        <input type="hidden" name="fnc" value="">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="voxid" value="[{$oxid}]">
        <input type="hidden" name="oxparentid" value="[{$oxparentid}]">
        <input type="hidden" name="editval[oxarticles__oxid]" value="[{$oxid}]">
        <tr>
          <td valign="top" class="edittext" style="padding-top:10px;padding-left:10px;">
                [{if $edit->isParentNotBuyable()}]
                    <div class="errorbox">[{ oxmultilang ident="ARTICLE_MAIN_PARENTNOTBUYABLE" }]</div>
            [{/if}]
              [{if $errorsavingatricle }]
                [{if $errorsavingatricle eq 1 }]
                <div class="errorbox">[{ oxmultilang ident="ARTICLE_MAIN_ERRORSAVINGARTICLE" }]</div>
                [{/if}]
                [{/if}]
                <table>
                    [{if $oxid!=-1 && $thisvariantlist}]
                    <tr>
                        <td class="edittext">[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_GOTOVARIANT" }]</td>
                        <td class="edittext">
                            [{if $oxid != "-1"}]
                            <script type="text/javascript">
                                <!--
                                function JumpVariant(obj)
                                {
                                    var oTransfer = document.getElementById("transfer");
                                    oTransfer.oxid.value=obj.value;
                                    oTransfer.cl.value='article_netgooglemc';

                                    //forcing edit frame to reload after submit
                                    top.forceReloadingEditFrame();

                                    var oSearch = parent.list.document.getElementById("search");
                                    oSearch.oxid.value=obj.value;
                                    oSearch.submit();
                                }
                                //-->
                            </script>
                            <table cellspacing="2" cellpadding="2" border="0">
                                <tr>
                                    <td>
                                        [{*$thisvariantlist|var_dump*}]
                                        <select name="art_variants" onChange="Javascript:JumpVariant(this);" class="editinput">
                                            [{foreach from=$thisvariantlist key=num item=variant}]
                                        <option value="[{$variant[0]}]" [{if $oxid eq $variant[0]}]selected[{/if}]>[{$oView->getArticleArtnumByOxid($variant[0])}] [{$variant[1]}]</option>
                                            [{/foreach}]
                                        </select>
                                    </td>
                                </tr>
                            </table>
                            [{/if}]
                        </td>
                    </tr>
                    [{/if}]
                </table>
            <table cellspacing="0" cellpadding="0" border="0" class="article_netgooglemc_table">
                    <tr>
                      <td class="edittext tdleft">
                        [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ID"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ID"}]&nbsp;
                      </td>
                      <td class="edittext">
                        [{$edit->oxarticles__oxid->value}]
                      </td>
                    </tr>

                    <tr>
                      <td class="edittext tdleft">
                        [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_TITLE"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_TITLE"}]&nbsp;
                      </td>
                      <td class="edittext">
                        [{if $edit->oxarticles__oxparentid->value != ""}]
                            [{assign var="oParentArticle" value=$edit->getParentArticle()}]
                            [{$oParentArticle->oxarticles__oxtitle->value}]
                            [{foreach from=$thisvariantlist key=num item=variant}]
                                [{if $oxid eq $variant[0]}]
                                    [{$variant[1]}]
                                [{/if}]
                            [{/foreach}]
                          [{else}]
                            [{$edit->oxarticles__oxtitle->value}]
                          [{/if}]

                      </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLETITLEPREFIX"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netarticletitleprefix]" value="[{$edit->oxarticles__netarticletitleprefix->value|html_entity_decode}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLETITLESUFFIX"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netarticletitlesuffix]" value="[{$edit->oxarticles__netarticletitlesuffix->value|html_entity_decode}]">
                        </td>
                    </tr>
                    <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_DESCRIPTION" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_DESCRIPTION" }]&nbsp;
                      </td>
                      <td class="edittext" style="white-space:normal;">

                          [{if $oView->getGmcDescConfigParam() == "shortdesc"}]
                              [{if $oView->net_strip_html_tags($edit->oxarticles__oxshortdesc->value) != "" && $oView->net_strip_html_tags($edit->oxarticles__oxshortdesc->value) != " "}]
                                [{$oView->net_strip_html_tags($edit->oxarticles__oxshortdesc->value)}]
                              [{else}]
                                [{if $edit->oxarticles__oxparentid->value != ""}]
                                    [{assign var="oParentArticle" value=$edit->getParentArticle()}]

                                    [{if $oView->net_strip_html_tags($oParentArticle->oxarticles__oxshortdesc->value) != "" && $oView->net_strip_html_tags($oParentArticle->oxarticles__oxshortdesc->value) != " "}]
                                        [{$oView->net_strip_html_tags($oParentArticle->oxarticles__oxshortdesc->value)}]
                                    [{/if}]
                                [{/if}]
                              [{/if}]
                          [{else}]
                              [{assign var="sLongdesc" value=$edit->getLongDesc()}]
                                    [{if $oView->net_strip_html_tags($sLongdesc) != "" && $oView->net_strip_html_tags($sLongdesc) != " "}]
                                        [{$oView->net_strip_html_tags($sLongdesc)}]
                                    [{else}]
                                        [{if $edit->oxarticles__oxparentid->value != ""}]
                                            [{assign var="oParentArticle" value=$edit->getParentArticle()}]
                                            [{assign var="sLongdesc" value=$oParentArticle->getLongDesc()}]
                                            [{if $oView->net_strip_html_tags($sLongdesc) != "" && $oView->net_strip_html_tags($sLongdesc) != " "}]
                                                [{$oView->net_strip_html_tags($sLongdesc)}]
                                            [{/if}]
                                        [{/if}]
                                    [{/if}]
                          [{/if}]
                      </td>
                    </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_GOOGLECAT"}] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_GOOGLECAT" }]&nbsp;
                      </td>
                      <td class="edittext">
                          [{assign var="googlecats" value=$oView->getGoogleCategoryArray()}]
                          <select class="editinput" name="editval[oxarticles__netgooglecat]" style="width:190px;" [{$readonly}]>
                          <option value="" selected>---</option>
                          [{foreach from=$googlecats item="gCat"}]
                      <option value="[{$gCat[0]}]"[{if $edit->oxarticles__netgooglecat->value == $gCat[0]}] selected[{/if}]>[{$gCat[1]}]</option>
                          [{/foreach}]
                          </select>
                      </td>
                  </tr>
                    <tr>
                      <td class="edittext tdleft">
                        [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_OXIDMAINCAT" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_OXIDMAINCAT" }]&nbsp;
                      </td>
                      <td class="edittext">
                        [{assign var="oArtCat" value=$edit->getCategory()}]
                          [{if $oArtCat}]
                            [{$oArtCat->oxcategories__oxtitle->value}]
                          [{/if}]
                      </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_OXIDMAINCAT_OTHER" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_OXIDMAINCAT_OTHER" }]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxarticles__netproducttype]" value="[{$edit->oxarticles__netproducttype->value|html_entity_decode}]">
                        </td>
                    </tr>
                    <tr>
                      <td class="edittext tdleft">
                        [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_LINK" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_LINK" }]&nbsp;
                      </td>
                      <td class="edittext">
                        [{$edit->getLink()}]
                      </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_URL_SUFFIX" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_URL_SUFFIX" }]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxarticles__neturlsuffix]" value="[{$edit->oxarticles__neturlsuffix->value}]">
                        </td>
                    </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_MOBILE_LINK" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_MOBILE_LINK" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxarticles__netmobilelink]" value="[{$edit->oxarticles__netmobilelink->value}]">
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_IMAGE_LINK" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_IMAGE_LINK" }]&nbsp;
                      </td>
                      <td class="edittext">
                          [{$oView->getArticleMainExportPicture($edit)}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADDIMAGE_LINK" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADDIMAGE_LINK" }]&nbsp;
                      </td>
                      <td class="edittext" style="white-space:normal;">
                          [{$oView->getArticleAdditionalExportImages($edit)}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_CONDITION" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_CONDITION" }]&nbsp;
                      </td>
                      <td class="edittext" style="white-space:normal;">
                          <select class="editinput" name="editval[oxarticles__netblzustand]" style="width:190px;" [{$readonly}]>
                            <option value="1"[{if $edit->oxarticles__netblzustand->value == 1}] selected[{/if}]>[{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_CONDITION_NEW"}]</option>
                            <option value="0"[{if $edit->oxarticles__netblzustand->value == 0}] selected[{/if}]>[{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_CONDITION_USED"}]</option>
                            <option value="2"[{if $edit->oxarticles__netblzustand->value == 2}] selected[{/if}]>[{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_CONDITION_REFURBISHED"}]</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AVAILABILITY" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_AVAILABILITY" }]&nbsp;
                      </td>
                      <td class="edittext" style="white-space:normal;">
                          [{if $oView->getOxidStockUsage() == 1}]
                            [{if $oView->getOxidStockAllowNegative() == 1}]
                                [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_IN_STOCK"}]
                            [{elseif $edit->oxarticles__oxstock->value && $edit->oxarticles__oxstock->value >= 1}]
                                [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_IN_STOCK"}]
                            [{else}]
                                [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_NOT_IN_STOCK"}]
                            [{/if}]
                          [{else}]
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_IN_STOCK"}]
                          [{/if}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_PRICE" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_PRICE" }]&nbsp;
                      </td>
                      <td class="edittext" style="white-space:normal;">
                          [{assign var="oArticlePrice" value=$edit->getPrice()}]
                          [{assign var="sBruttoPrice" value=$oArticlePrice->getBruttoPrice()}]
                          [{$sBruttoPrice|string_format:"%.2f"}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_BRAND" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_BRAND" }]&nbsp;
                      </td>
                      <td class="edittext">
                          [{if $oView->getGmcBrandConfigParam() == "Manufacturer"}]
                            [{foreach from=$oView->getManufacturerList() item=oManufacturer }]
                                [{if $edit->oxarticles__oxmanufacturerid->value == $oManufacturer->oxmanufacturers__oxid->value}]
                                    [{$oManufacturer->oxmanufacturers__oxtitle->value}]
                                [{/if}]
                            [{/foreach}]
                          [{else}]
                            [{foreach from=$oView->getVendorList() item=oVendor}]
                                [{if $edit->oxarticles__oxvendorid->value == $oVendor->oxvendor__oxid->value}]
                                    [{$oVendor->oxvendor__oxtitle->value}]
                                [{/if}]
                            [{/foreach}]
                          [{/if}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_GTIN" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_GTIN" }]&nbsp;
                      </td>
                      <td class="edittext">
                          [{$edit->oxarticles__oxean->value}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_MPN" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_MPN" }]&nbsp;
                      </td>
                      <td class="edittext">
                          [{$edit->oxarticles__oxmpn->value}]
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_GENDER" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_GENDER" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <select class="editinput" name="editval[oxarticles__netgooglegender]" style="width:190px;" [{$readonly}]>
                          <option value="Unisex" [{if $edit->oxarticles__netgooglegender->value == "Unisex"}] selected[{/if}]>Unisex</option>
                          <option value="Herren" [{if $edit->oxarticles__netgooglegender->value == "Herren"}] selected[{/if}]>Herren</option>
                          <option value="Frauen" [{if $edit->oxarticles__netgooglegender->value == "Frauen"}] selected[{/if}]>Frauen</option>
                          <option value="" [{if $edit->oxarticles__netgooglegender->value == ""}] selected[{/if}]>---</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AGEGROUP" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_AGEGROUP" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <select class="editinput" name="editval[oxarticles__netgoogleagegroup]" style="width:190px;" [{$readonly}]>
                            <option value="Erwachsene" [{if $edit->oxarticles__netgoogleagegroup->value == "Erwachsene"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AGEGROUP_ADULT" }]</option>
                            <option value="Kinder" [{if $edit->oxarticles__netgoogleagegroup->value == "Kinder"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AGEGROUP_KIDS" }]</option>
                            <option value="newborn" [{if $edit->oxarticles__netgoogleagegroup->value == "newborn"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AGEGROUP_NEWBORN" }]</option>
                            <option value="infant" [{if $edit->oxarticles__netgoogleagegroup->value == "infant"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AGEGROUP_INFANT" }]</option>
                            <option value="toddler" [{if $edit->oxarticles__netgoogleagegroup->value == "toddler"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_AGEGROUP_TODDLER" }]</option>
                            <option value="" [{if $edit->oxarticles__netgoogleagegroup->value == ""}] selected [{/if}]>---</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_COLOR" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_COLOR" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netgooglecolor]" value="[{$edit->oxarticles__netgooglecolor->value}]">
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_SIZE" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netgooglesize]" value="[{$edit->oxarticles__netgooglesize->value}]">
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_TYPE" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_SIZE_TYPE" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <select class="editinput" name="editval[oxarticles__netgooglesizetype]" style="width:190px;" [{$readonly}]>
                          <option value="regular" [{if $edit->oxarticles__netgooglesizetype->value == "regular"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_TYPE_REGULAR" }]</option>
                          <option value="petite" [{if $edit->oxarticles__netgooglesizetype->value == "petite"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_TYPE_PETITE" }]</option>
                          <option value="plus" [{if $edit->oxarticles__netgooglesizetype->value == "plus"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_TYPE_PLUS" }]</option>
                          <option value="big and tall" [{if $edit->oxarticles__netgooglesizetype->value == "big and tall"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_TYPE_BIGTALL" }]</option>
                          <option value="maternity" [{if $edit->oxarticles__netgooglesizetype->value == "maternity"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_TYPE_MATERNITY" }]</option>
                          <option value="" [{if $edit->oxarticles__netgooglesizetype->value == ""}] selected [{/if}]>---</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS" }] [{ oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_SIZE_SYS" }]&nbsp;
                      </td>
                      <td class="edittext">
                          <select class="editinput" name="editval[oxarticles__netgooglesizesys]" style="width:190px;" [{$readonly}]>
                          <option value="US" [{if $edit->oxarticles__netgooglesizesys->value == "US"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_US" }]</option>
                          <option value="UK" [{if $edit->oxarticles__netgooglesizesys->value == "UK"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_UK" }]</option>
                          <option value="EU" [{if $edit->oxarticles__netgooglesizesys->value == "EU"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_EU" }]</option>
                          <option value="DE" [{if $edit->oxarticles__netgooglesizesys->value == "DE"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_DE" }]</option>
                          <option value="FR" [{if $edit->oxarticles__netgooglesizesys->value == "FR"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_FR" }]</option>
                          <option value="JP" [{if $edit->oxarticles__netgooglesizesys->value == "JP"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_JP" }]</option>
                          <option value="CN" [{if $edit->oxarticles__netgooglesizesys->value == "CN"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_CN" }]</option>
                          <option value="IT" [{if $edit->oxarticles__netgooglesizesys->value == "IT"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_IT" }]</option>
                          <option value="BR" [{if $edit->oxarticles__netgooglesizesys->value == "BR"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_BR" }]</option>
                          <option value="MEX" [{if $edit->oxarticles__netgooglesizesys->value == "MEX"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_MEX" }]</option>
                          <option value="AU" [{if $edit->oxarticles__netgooglesizesys->value == "AU"}] selected [{/if}]>[{ oxmultilang ident="NET_GOOGLE_MC_ARTICLE_SIZE_SYS_AU" }]</option>
                          <option value="" [{if $edit->oxarticles__netgooglesizesys->value == ""}] selected [{/if}]>---</option>
                          </select>
                      </td>
                  </tr>
                  <tr>
                      <td class="edittext tdleft">
                          [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ITEM_GROUP_ID"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ITEM_GROUP_ID"}]&nbsp;
                      </td>
                      <td class="edittext">
                          [{if $edit->isVariant()}]
                            [{$edit->oxarticles__oxparentid->value}]
                          [{/if}]
                      </td>
                  </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_NO_ADULTITEM"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_NO_ADULTITEM"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="hidden" name="editval[oxarticles__netblnoadult]" value="0">
                            <input class="edittext" type="checkbox" name="editval[oxarticles__netblnoadult]" value='1' [{if $edit->oxarticles__netblnoadult->value == 1}]checked[{/if}] [{ $readonly }]>
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADWORDS_GROUPING"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADWORDS_GROUPING"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxarticles__netadwordsgroup]" value="[{$edit->oxarticles__netadwordsgroup->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADWORDS_LABEL"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADWORDS_LABEL"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxarticles__netadwordslabel]" value="[{$edit->oxarticles__netadwordslabel->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADWORDS_REDIRECT"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADWORDS_REDIRECT"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxarticles__netadwordsredirect]" value="[{$edit->oxarticles__netadwordsredirect->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_SHIPPINGLABEL"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_SHIPPINGLABEL"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netshippinglabel]" value="[{$edit->oxarticles__netshippinglabel->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL0"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL0"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netcustomlabel0]" value="[{$edit->oxarticles__netcustomlabel0->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL1"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL1"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netcustomlabel1]" value="[{$edit->oxarticles__netcustomlabel1->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL2"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL2"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netcustomlabel2]" value="[{$edit->oxarticles__netcustomlabel2->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL3"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL3"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netcustomlabel3]" value="[{$edit->oxarticles__netcustomlabel3->value}]">
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext tdleft">
                            [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL4"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL4"}]&nbsp;
                        </td>
                        <td class="edittext">
                            <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxarticles__netcustomlabel4]" value="[{$edit->oxarticles__netcustomlabel4->value}]">
                        </td>
                    </tr>
            </table>
        <table>
              <tr>
                <td class="edittext" colspan="2"><br><br>
                <input type="submit" class="edittext" id="oLockButton" name="saveArticle" value="[{ oxmultilang ident="NET_GOOGLE_MC_SAVE_BUTTON" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ if !$edit->oxarticles__oxtitle->value && !$oxparentid }]disabled[{/if}] [{ $readonly }]>
                <p>&nbsp;</p>
                </td>
              </tr>

            </table>
          </td>
        </tr>
        </form>
      </table>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
