[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
    <!--
    function ThisDate(sID)
    {
        document.myedit['editval[netcampaigns__oxexpirationdate]'].value=sID;
    }
    //-->
</script>

[{if $readonly }]
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
    <input type="hidden" name="cl" value="campaigns_main">
</form>


<form name="myedit" enctype="multipart/form-data" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="campaigns_main">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{ $oxid }]">
<input type="hidden" name="editval[netcampaigns__oxid]" value="[{ $oxid }]">
<input type="hidden" name="sorting" value="">
<input type="hidden" name="stable" value="">
<input type="hidden" name="starget" value="">


<table cellspacing="0" cellpadding="0" border="0" width="98%">

<tr>
    <td valign="top" class="edittext" style="padding-right: 20px;">
        <table cellspacing="0" cellpadding="0" border="0">
        [{block name="admin_campaigns_main_form"}]
            <tr>
                <td class="edittext" width="120">
                [{ oxmultilang ident="GENERAL_NAME" }]
                </td>
                <td class="edittext">
                <input type="text" class="editinput" size="32" maxlength="128" name="editval[netcampaigns__oxtitle]" value="[{$edit->netcampaigns__oxtitle->value}]" [{ $readonly }] [{ $disableSharedEdit }]>
                [{ oxinputhelp ident="HELP_GENERAL_NAME" }]
                </td>
            </tr>

            <tr>
              <td class="edittext" width="120">
                  [{ oxmultilang ident="NET_GOOGLE_MC_CAMPAIGN_ACTIVE" }]
              </td>
              <td class="edittext">
                <input class="edittext" type="checkbox" name="editval[netcampaigns__oxactive]" value='1' [{if $edit->netcampaigns__oxactive->value == 1}]checked[{/if}] [{ $readonly }]>
                [{ oxinputhelp ident="HELP_GENERAL_ACTIVE" }]
              </td>
            </tr>
            [{if $oxid == "-1"}]
                <input type="hidden" name="editval[netcampaigns__oxtype]" value="1">
            [{else}]
            <tr>
                <td class="edittext">
                    [{ oxmultilang ident="NET_GMC_FEED_FILENAME" }]&nbsp;
                </td>
                <td class="edittext">
                    <input type="text" class="editinput" size="32" maxlength="128" name="editval[netcampaigns__oxlongdesc]" value="[{$edit->netcampaigns__oxlongdesc->value}]" [{ $readonly }] [{ $disableSharedEdit }]>
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_GMC_FEED_LANGUAGE"}]&nbsp;
                </td>
                <td class="edittext">
                    [{assign var="aLanguages" value=$oView->getShopLanguagesArray()}]
                    <select name="editval[netcampaigns__oxlanguage]">
                        [{foreach from=$aLanguages item="oLang" name="languages"}]
                            <option value="[{$oLang->id}]" [{if $edit->netcampaigns__oxlanguage->value eq $oLang->id}]selected[{/if}]>[{$oLang->name}]</option>
                        [{/foreach}]
                    </select>
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_GMC_FEED_CURRENCY"}]&nbsp;
                </td>
                <td class="edittext">
                    [{assign var="aCurrencies" value=$oView->getShopCurrencyArray()}]
                    <select name="editval[netcampaigns__oxcurrency]">
                        [{foreach from=$aCurrencies item="oCurrency" name="currencies"}]
                            <option value="[{$oCurrency->id}]" [{if $edit->netcampaigns__oxcurrency->value eq $oCurrency->id}]selected[{/if}]>[{$oCurrency->name}]</option>
                        [{/foreach}]
                    </select>
                </td>
            </tr>
            <tr>
                <td class="edittext" colspan="2">
                    <hr />
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_EXPIRATIONDATE_ACTIVE"}]&nbsp;
                </td>
                <td class="edittext">
                    <input type="hidden" name="editval[netcampaigns__oxexpirationdateactive]" value='0' [{ $readonly }]>
                    <input class="edittext" type="checkbox" name="editval[netcampaigns__oxexpirationdateactive]" value='1' [{if $edit->netcampaigns__oxexpirationdateactive->value == 1}]checked[{/if}] [{ $readonly }]>
                    [{ oxinputhelp ident="HELP_NET_EXPIRATIONDATE_ACTIVE" }]
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_EXPIRATIONDATE"}]&nbsp;
                </td>
                <td class="edittext">
                    [{assign var=date value=$edit->netcampaigns__oxexpirationdate->value|replace:"0000-00-00 00:00:00":""}]
                    <input type="text" class="editinput" size="25" name="editval[netcampaigns__oxexpirationdate]" value="[{$edit->netcampaigns__oxexpirationdate|oxformdate }]" [{include file="help.tpl" helpid=article_vonbis}] [{ $readonly }]>&nbsp;<a href="Javascript:ThisDate('[{$sNowValue|oxformdate:'datetime':true}]');" class="edittext" [{if $readonly }]onclick="JavaScript:return false;"[{/if}]><u>[{ oxmultilang ident="NET_GOOGLE_MC_CURRENT_DATE" }]</u></a>
                    [{ oxinputhelp ident="HELP_NET_EXPIRATIONDATE" }]
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_EXPIRATIONDATE_OVERRIDE"}]&nbsp;
                </td>
                <td class="edittext">
                    <input type="hidden" name="editval[netcampaigns__oxexpirationoverride]" value='0' [{ $readonly }]>
                    <input class="edittext" type="checkbox" name="editval[netcampaigns__oxexpirationoverride]" value='1' [{if $edit->netcampaigns__oxexpirationoverride->value == 1}]checked[{/if}] [{ $readonly }]>
                    [{ oxinputhelp ident="HELP_NET_EXPIRATIONDATE_OVERRIDE" }]
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_EXPIRATIONDATE_OVERRIDEADD"}]&nbsp;
                </td>
                <td class="edittext">
                    <select name="editval[netcampaigns__oxexpirationoverrideadd]">
                        <option value="1" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "1"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]1 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAY"}]</option>
                        <option value="2" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "2"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]2 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="3" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "3"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]3 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="4" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "4"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]4 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="5" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "5"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]5 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="6" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "6"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]6 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="7" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "7"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]7 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="8" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "8"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]8 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="9" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "9"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]9 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="10" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "10"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]10 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="11" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "11"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]11 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="12" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "12"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]12 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="13" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "13"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]13 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="14" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "14"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]14 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="15" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "15"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]15 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="16" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "16"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]16 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="17" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "17"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]17 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="18" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "18"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]18 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="19" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "19"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]19 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="20" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "20"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]20 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="21" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "21"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]21 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="22" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "22"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]22 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="23" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "23"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]23 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="24" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "24"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]24 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="25" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "25"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]25 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="26" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "26"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]26 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="27" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "27"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]27 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="28" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "28"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]28 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="29" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "29"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]29 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                        <option value="30" [{if $edit->netcampaigns__oxexpirationoverrideadd->value eq "30"}]selected[{/if}]>[{oxmultilang ident="NET_GMC_ACT_EXPORT_DATE"}]30 [{oxmultilang ident="NET_GMC_EXPIRATIONDATE_OVERRIDEADD_DAYS"}]</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="edittext" colspan="2">
                    <hr />
                </td>
            </tr>
            <tr>
                <td class="edittext">
                    [{oxmultilang ident="NET_STOCKMINMAXACTIVE"}]&nbsp;
                </td>
                <td class="edittext">
                    <input type="hidden" name="editval[netcampaigns__oxstockminmaxactive]" value='0' [{ $readonly }]>
                    <input class="edittext" type="checkbox" name="editval[netcampaigns__oxstockminmaxactive]" value='1' [{if $edit->netcampaigns__oxstockminmaxactive->value == 1}]checked[{/if}] [{ $readonly }]>
                    [{ oxinputhelp ident="HELP_NET_STOCKMINMAXACTIVE" }]
                </td>
            </tr>
            <tr>
                <td class="edittext" width="120">
                    [{ oxmultilang ident="NET_STOCKMIN }]
                </td>
                <td class="edittext">
                    <input type="text" class="editinput" size="32" maxlength="128" name="editval[netcampaigns__oxstockmin]" value="[{$edit->netcampaigns__oxstockmin->value}]" [{ $readonly }] [{ $disableSharedEdit }]>
                    [{ oxinputhelp ident="HELP_NET_STOCKMIN" }]
                </td>
            </tr>
            <tr>
                <td class="edittext" width="120">
                    [{ oxmultilang ident="NET_STOCKMAX" }]
                </td>
                <td class="edittext">
                    <input type="text" class="editinput" size="32" maxlength="128" name="editval[netcampaigns__oxstockmax]" value="[{$edit->netcampaigns__oxstockmax->value}]" [{ $readonly }] [{ $disableSharedEdit }]>
                    [{ oxinputhelp ident="HELP_NET_STOCKMAX" }]
                </td>
            </tr>
            [{/if}]
        [{/block}]
        <tr>
            <td class="edittext"><br></td>
            <td class="edittext"><br></td>
        </tr>
        [{if $edit->netcampaigns__oxtype->value == 3 }]
            <td class="edittext" width="120">
            [{ oxmultilang ident="GENERAL_SORT" }]
            </td>
            <td class="edittext">
            <input type="text" class="editinput" size="32" maxlength="128" name="editval[netcampaigns__oxsort]" value="[{$edit->netcampaigns__oxsort->value}]" [{ $readonly }]>
            [{ oxinputhelp ident="HELP_GENERAL_SORT" }]
            </td>
        [{/if}]
        <tr>
            <td class="edittext">
            </td>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{ $readonly }] [{ $disableSharedEdit }]><br><br>


            [{if $oxid != "-1"}]


                   <input type="button" value="[{ oxmultilang ident="GENERAL_ASSIGNARTICLES" }]" class="edittext" onclick="JavaScript:showDialog('&cl=campaigns_main&aoc=1&oxid=[{ $oxid }]');" [{ $readonly }]>

            [{/if}]

            </td>
        </tr>
        </table>
    </td>
    </tr>
</table>


</form>


</div>


<div class="actions">
[{strip}]

  <ul>
    <li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.new" href="#" onClick="Javascript:top.oxid.admin.editThis( -1 );return false" target="edit">[{ oxmultilang ident="NET_TOOLTIPS_NEWCAMPAIGN" }]</a></li>
  </ul>
[{/strip}]
</div>



[{include file="bottomitem.tpl"}]