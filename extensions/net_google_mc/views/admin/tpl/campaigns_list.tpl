[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign box="list"}]
[{assign var="where" value=$oView->getListFilter()}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{ if $updatelist == 1}]
        top.oxid.admin.updateList('[{ $oxid }]');
    [{ /if}]
}
//-->
</script>


<div id="liste">

<form name="search" id="search" action="[{ $oViewConf->getSelfLink() }]" method="post">
[{include file="_formparams.tpl" cl="campaigns_list" lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang editlanguage=$actlang}]
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <colgroup>
        [{block name="admin_campaigns_list_colgroup"}]
        <col width="3%">
        <col width="45%">
        [{*<col width="45%">*}]
        <col width="45%">
        <col width="7%">
        [{/block}]
    </colgroup>

    <tr class="listitem">
        [{block name="admin_campaigns_list_sorting"}]
        <td class="listheader first" height="15" width="30" align="center"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netcampaigns', 'oxactive', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_ACTIVTITLE" }]</a></td>
        <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netcampaigns', 'oxtitle', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_NAME" }]</a></td>
        [{*<td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netcampaigns', 'oxactivefrom', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="PROMOTION_LIST_STARTTIME" }]</a></td>*}]
        <td class="listheader"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netcampaigns', 'oxtype', 'asc');document.search.submit();" class="listheader">[{ oxmultilang ident="GENERAL_TYPE" }]</a></td>
        <td class="listheader"></td>
        [{/block}]
    </tr>

[{assign var="blWhite" value=""}]
[{assign var="_cnt" value=0}]
[{foreach from=$mylist item=listitem}]
    [{assign var="_cnt" value=$_cnt+1}]
    <tr id="row.[{$_cnt}]">

    [{ if $listitem->blacklist == 1}]
        [{assign var="listclass" value=listitem3 }]
    [{ else}]
        [{assign var="listclass" value=listitem$blWhite }]
    [{ /if}]
    [{ if $listitem->getId() == $oxid }]
        [{assign var="listclass" value=listitem4 }]
    [{ /if}]

    [{block name="admin_campaigns_list_item"}]
    <td valign="top" class="[{ $listclass}][{ if $listitem->netcampaigns__oxactive->value == 1}] active[{/if}]" height="15"><div class="listitemfloating">&nbsp</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netcampaigns__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->netcampaigns__oxtitle->value }]</a></div></td>
    [{*<td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netcampaigns__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->netcampaigns__oxactivefrom->value }]</a></div></td>*}]
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating">
        <a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netcampaigns__oxid->value}]');" class="[{ $listclass}]">
            [{if $listitem->netcampaigns__oxtype->value == 3 }]
                [{ oxmultilang ident="PROMOTIONS_MAIN_TYPE_BANNER" }]
            [{elseif $listitem->netcampaigns__oxtype->value == 2 }]
                [{ oxmultilang ident="NET_CAMPAIGNS_MAIN_TYPE_GOOGLEXML" }]
            [{else}]
                [{ oxmultilang ident="NET_CAMPAIGNS_MAIN_TYPE_GOOGLECSV" }]
            [{/if}]
        </a></div></td>
    <td class="[{ $listclass}]">[{ if !$listitem->isOx() && !$readonly && $listitem->netcampaigns__oxtype->value > 0}]<a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->netcampaigns__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a>[{/if}]</td>
    [{/block}]
</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
[{include file="pagenavisnippet.tpl" colspan="5"}]
</table>
</form>
</div>

[{include file="pagetabsnippet.tpl"}]


<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="CAMPAIGNS_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="CAMPAIGNS_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>

