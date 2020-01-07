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
[{include file="_formparams.tpl" cl="gmclog_list" lstrt=$lstrt actedit=$actedit oxid=$oxid fnc="" language=$actlang editlanguage=$actlang}]
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <colgroup>
        [{block name="admin_gmclog_list_colgroup"}]
        <col width="15%">
        <col width="10%">
        <col width="45%">
        <col width="10%">
        <col width="10%">
        <col width="5%">
        [{/block}]
    </colgroup>

<tr class="listitem">
    [{block name="admin_campaigns_list_filter"}]
    <td valign="top" class="listfilter first" height="20"  colspan="6">
        <div class="r1">
            <div class="b1">
                <input class="listedit" type="text" size="30" maxlength="128" name="where[netgmclog][oxtimestamp]" value="[{ $where.netgmclog.oxtimestamp }]" placeholder="Nach Zeitstempel filtern">
                <input class="listedit" type="text" size="30" maxlength="128" name="where[netgmclog][oxartnum]" value="[{ $where.netgmclog.oxartnum }]" placeholder="Nach Artikel-Nr. filtern">
              [{*<div class="find">
                <select name="changelang" class="editinput" onChange="Javascript:top.oxid.admin.changeLanguage();">
                  [{foreach from=$languages item=lang}]
                  <option value="[{ $lang->id }]" [{ if $lang->selected}]SELECTED[{/if}]>[{ $lang->name }]</option>
                  [{/foreach}]
                </select>

              </div>
              *}]

                <input class="listedit" type="text" size="50" maxlength="128" name="where[netgmclog][oxtitle]" value="[{ $where.netgmclog.oxtitle }]" placeholder="Nach Produkttitel filtern">
                <input class="listedit" type="text" size="50" maxlength="128" name="where[netgmclog][oxarticleid]" value="[{ $where.netgmclog.oxarticleid }]" placeholder="Nach Artikel Datensatz ID (OXID) filtern">
                <input class="listedit" type="submit" name="submitit" value="[{ oxmultilang ident="GENERAL_SEARCH" }]">
            </div>
        </div>
    </td>
    [{/block}]
</tr>
<tr class="listitem">
    [{block name="admin_gmclog_list_sorting"}]
    <td class="listheader first"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netgmclog', 'oxtimestamp', 'asc');document.search.submit();" class="listheader">Erstellt am:</a></td>
    <td class="listheader first"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netgmclog', 'oxartnum', 'asc');document.search.submit();" class="listheader">Artikel-Nr:</a></td>
    <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netgmclog', 'oxtitle', 'asc');document.search.submit();" class="listheader">Betroffener Artikel</a></td>
    <td class="listheader" height="15"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netgmclog', 'oxerrorcount', 'asc');document.search.submit();" class="listheader">Probleme</a></td>
    <td class="listheader first"><a href="Javascript:top.oxid.admin.setSorting( document.search, 'netgmclog', 'oxarticleid', 'asc');document.search.submit();" class="listheader">Artikel-OXID:</a></td>
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
    [{block name="admin_gmccampaigns_list_item"}]
        <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netgmclog__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->netgmclog__oxtimestamp->value }]</a></div></td>
        <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netgmclog__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->netgmclog__oxartnum->value }]</a></div></td>
        <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netgmclog__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->netgmclog__oxtitle->value }]</a></div></td>
    <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netgmclog__oxid->value}]');" class="[{ $listclass}]">[{if $listitem->netgmclog__oxerrorcount->value >= 2}][{$listitem->netgmclog__oxerrorcount->value}] Probleme[{else}][{$listitem->netgmclog__oxerrorcount->value}] Problem[{/if}]</a></div></td>
        <td valign="top" class="[{ $listclass}]" height="15"><div class="listitemfloating"><a href="Javascript:top.oxid.admin.editThis('[{ $listitem->netgmclog__oxid->value}]');" class="[{ $listclass}]">[{ $listitem->netgmclog__oxarticleid->value }]</a></div></td>
    <td class="[{ $listclass}]"><a href="Javascript:top.oxid.admin.deleteThis('[{ $listitem->netgmclog__oxid->value }]');" class="delete" id="del.[{$_cnt}]" [{include file="help.tpl" helpid=item_delete}]></a></td>
    [{/block}]
</tr>
[{if $blWhite == "2"}]
[{assign var="blWhite" value=""}]
[{else}]
[{assign var="blWhite" value="2"}]
[{/if}]
[{/foreach}]
[{include file="pagenavisnippet.tpl" colspan="6"}]
</table>
</form>
</div>

[{include file="pagetabsnippet.tpl"}]


<script type="text/javascript">
if (parent.parent)
{   parent.parent.sShopTitle   = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
    parent.parent.sMenuItem    = "[{ oxmultilang ident="GMCLOG_LIST_MENUITEM" }]";
    parent.parent.sMenuSubItem = "[{ oxmultilang ident="GMCLOG_LIST_MENUSUBITEM" }]";
    parent.parent.sWorkArea    = "[{$_act}]";
    parent.parent.setTitle();
}
</script>
</body>
</html>

