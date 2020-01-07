[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function SchnellSortManager(oObj)
{   oRadio = document.getElementsByName("editval[oxcategories__oxdefsortmode]");
    if(oObj.value)
        for ( i=0; i<oRadio.length; i++)
            oRadio.item(i).disabled="";
    else
        for ( i=0; i<oRadio.length; i++)
            oRadio.item(i).disabled = true;
}

function DeletePic( sField )
{
    var oForm = document.getElementById("myedit");
    oForm.fnc.value="deletePicture";
    oForm.masterPicField.value=sField;
    oForm.submit();
}

function LockAssignment(obj)
{   var aButton = document.myedit.assignArticle;
    if ( aButton != null && obj != null )
    {
        if (obj.value > 0)
        {
            aButton.disabled = true;
        }
        else
        {
            aButton.disabled = false;
        }
    }
}
//-->
</script>
<!-- END add to *.css file -->
<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" id="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="category_netgooglemc">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
[{$oViewConf->getHiddenSid()}]
<input type="hidden" name="cl" value="category_netgooglemc">
<input type="hidden" name="fnc" value="">
<input type="hidden" name="oxid" value="[{$oxid}]">
<input type="hidden" name="editval[oxcategories__oxid]" value="[{$oxid}]">
    <h1>Google Merchant Center:</h1>
    <p>[{oxmultilang ident="NET_GOOGLE_MC_CATEGORY_TABDESC"}]</p>
    <hr>
<table cellspacing="0" cellpadding="0" border="0" width="98%">
<tr>
    <td valign="top" class="edittext">

      <table cellspacing="0" cellpadding="0" border="0" class="article_netgooglemc_table">
        [{block name="admin_category_netgooglemc_form"}]
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_CATEGORY_GOOGLECAT"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CATEGORY_GOOGLECAT"}]&nbsp;
              </td>
              <td class="edittext" colspan="2">
                [{assign var="googlecats" value=$oView->getGoogleCategoryArray()}]
                <select class="editinput" name="editval[oxcategories__netgooglecat]" style="max-width:400px;" [{$readonly}]>
                    <option value="" selected>---</option>
                    [{foreach from=$googlecats item="gCat"}]
                    <option value="[{$gCat[0]}]"[{if $edit->oxcategories__netgooglecat->value == $gCat[0]}] selected[{/if}]>[{$gCat[1]}]</option>
                    [{/foreach}]
                  </select>
              </td>
          </tr>
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADWORDS_GROUPING"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADWORDS_GROUPING"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxcategories__netadwordsgroup]" value="[{$edit->oxcategories__netadwordsgroup->value}]">
              </td>
          </tr>
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADWORDS_LABEL"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADWORDS_LABEL"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxcategories__netadwordslabel]" value="[{$edit->oxcategories__netadwordslabel->value}]">
              </td>
          </tr>
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_ARTICLE_ADWORDS_REDIRECT"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLE_ADWORDS_REDIRECT"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="255" name="editval[oxcategories__netadwordsredirect]" value="[{$edit->oxcategories__netadwordsredirect->value}]">
              </td>
          </tr>
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_SHIPPINGLABEL"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_SHIPPINGLABEL"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netshippinglabel]" value="[{$edit->oxcategories__netshippinglabel->value}]">
              </td>
          </tr>
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_ARTICLETITLEPREFIX"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLETITLEPREFIX"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netarticletitleprefix]" value="[{$edit->oxcategories__netarticletitleprefix->value}]">
              </td>
          </tr>
          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_ARTICLETITLESUFFIX"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_ARTICLETITLESUFFIX"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netarticletitlesuffix]" value="[{$edit->oxcategories__netarticletitlesuffix->value}]">
              </td>
          </tr>

          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL0"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL0"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netcustomlabel0]" value="[{$edit->oxcategories__netcustomlabel0->value}]">
              </td>
          </tr>

          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL1"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL1"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netcustomlabel1]" value="[{$edit->oxcategories__netcustomlabel1->value}]">
              </td>
          </tr>

          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL2"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL2"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netcustomlabel2]" value="[{$edit->oxcategories__netcustomlabel2->value}]">
              </td>
          </tr>

          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL3"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL3"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netcustomlabel3]" value="[{$edit->oxcategories__netcustomlabel3->value}]">
              </td>
          </tr>

          <tr>
              <td class="edittext tdleft">
                  [{oxmultilang ident="NET_GOOGLE_MC_CUSTOMLABEL4"}] [{oxinputhelp ident="NET_GOOGLE_MC_HELP_CUSTOMLABEL4"}]&nbsp;
              </td>
              <td class="edittext">
                  <input type="text" class="editinput" size="32" maxlength="100" name="editval[oxcategories__netcustomlabel4]" value="[{$edit->oxcategories__netcustomlabel4->value}]">
              </td>
          </tr>
        [{/block}]
          </table>
    <table>
        <tr>
            <td class="edittext"><br>
            <input type="submit" class="edittext" name="save" value="[{ oxmultilang ident="CATEGORY_MAIN_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'" [{$readonly}]><br>
            </td>
        </tr>
        [{if $edit->oxcategories__netgooglecat->value != ""}]
        <tr>
            <td class="edittext"><br>
                <input type="submit" class="edittext" name="addGoogleCategoryToSubcategories" value="[{oxmultilang ident="NET_ADD_GOOGLECAT_TO_ALL_SUBCATEGORIES"}]" onClick="Javascript:document.myedit.fnc.value='addgooglecategorytosubcategories'" [{$readonly}]><br>
            </td>
        </tr>
        [{/if}]

        </table>
    </td>
    </tr>
</table>

</form>
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
