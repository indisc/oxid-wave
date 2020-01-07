[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var='iRowCount' value=0}]
[{if $oList}]
	[{assign var='iRowCount' value=$oList|@count}]
[{/if}]

<script type="text/javascript">

	var iRowCount = [{$iRowCount}];
	// <!--
	function createNewRow() {
		iRowCount++;
		var sOxid = '[{$sOxidBase}]';
		var iChar = 0;
		var sChar = '';
		for ( var iChars = 0 ; iChars < 29; iChars++ ) {
			iChar = Math.floor(16*Math.random());
			if ( iChar > 14 ) {
				sChar = 'f';
			} else if ( iChar > 13 ) {
				sChar = 'e';
			} else if ( iChar > 12 ) {
				sChar = 'd';
			} else if ( iChar > 11 ) {
				sChar = 'c';
			} else if ( iChar > 10 ) {
				sChar = 'b'
			} else if ( iChar > 9 ) {
				sChar = 'a';
			} else {
				sChar = ''+iChar;
			}
			sOxid = sOxid+sChar;
		}
		var oTableBody = document.getElementById('listTableBody');
		var oTR = document.createElement('tr');
		oTR.innerHTML = '<td>'+iRowCount+'</td>'+
			'<td><input type="text" name="editlist['+sOxid+'][sccp_name]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_NAME'}]" value="" /></td>'+
			'<td><input type="text" name="editlist['+sOxid+'][sccp_producttypeid]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTTYPEID'}]" value="" /></td>'+
			'<td><input type="text" name="editlist['+sOxid+'][sccp_productclassid]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTCLASSID'}]" value="" /></td>'+
			'<td><input type="hidden" name="editlist['+sOxid+'][default]" value="0" /><input type="checkbox" name="editlist['+sOxid+'][default]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_DEFAULT'}]" value="1" readonly="readonly" disabled="disabled" checked="checked" /></td>'+
			'<td><input type="hidden" name="editlist['+sOxid+'][delete]" value="0" /><input type="checkbox" name="editlist['+sOxid+'][delete]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_DELETE'}]" value="1" /></td>'+
			'<td><input type="button" value="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_ASSIGN_ARTICLES'}]" class="edittext" onclick="showDialog(\'&cl=sccp_cpproduct_group&aoc=1&oxid='+sOxid+'\');" /></td>';
		oTableBody.appendChild(oTR);
	}
	// -->
</script>

[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
[{else}]
	[{assign var="readonly" value=""}]
[{/if}]

[{if $oList}]
	[{include file="inc/sccp_cporder_errorbox.tpl"}]
	[{oxmultilang ident='SCCP_CPPRODUCT_GROUP_DESCRIPTION'}]<br /><br />
	<form action="[{$oViewConf->getSelfLink()}]" method="post">
		[{$oViewConf->getHiddenSid()}]
		<input type="hidden" name="cl" value="sinkacom_creditplusmodule_productgroup">
		<input type="hidden" name="fnc" value="save">
		<table id="listTable" style="width: 60%;" width="70%">
			<colgroup>
				<col width="5%" />
				<col width="45%" />
				<col width="20%" />
				<col width="5%" />
				<col width="5%" />
				<col width="20%" />
			</colgroup>
			<thead>
				<tr>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_NUMBER'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_NAME'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTTYPEID'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTCLASSID'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_DEFAULT'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_DELETE'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_ASSIGN_ARTICLES'}]
					</th>
				</tr>
			</thead>
			<tbody id="listTableBody">
				[{foreach name='prods' from=$oList item='oEntry' key='sKey'}]
					[{* #11894 *}]
					[{if $oEntry->sccp_prodgroup__oxid->value == 'sccponlyone' && !$readonly}]
						[{assign var='sReadOnlyOne' value=' readonly="readonly"'}]
					[{/if}]
					<tr>
						<td>[{$smarty.foreach.prods.iteration}]</td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_name]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_NAME'}]" value="[{$oEntry->sccp_prodgroup__sccp_name->value}]" [{$readonly}] /></td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_producttypeid]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTTYPEID'}]" value="[{$oEntry->sccp_prodgroup__sccp_producttypeid->value}]" [{$readonly}] /></td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_productclassid]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_PRODUCTCLASSID'}]" value="[{$oEntry->sccp_prodgroup__sccp_productclassid->value}]" [{$readonly}][{$sReadOnlyOne}] /></td>
						<td><input type="hidden" name="editlist[[{$sKey}]][default]" value="0" /><input type="checkbox" name="editlist[[{$sKey}]][default]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_DEFAULT'}]" value="1" readonly="readonly" disabled="disabled" [{if $oEntry->countAssignedProducts() === 0}]checked="checked"[{/if}] /></td>
						<td><input type="hidden" name="editlist[[{$sKey}]][delete]" value="0" /><input type="checkbox" name="editlist[[{$sKey}]][delete]" title="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_DELETE'}]" value="1" [{$readonly}] /></td>
						<td><input [{$readonly}] type="button" value="[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_ASSIGN_ARTICLES'}]" class="edittext" onclick="showDialog('&cl=sinkacom_creditplusmodule_productgroup&aoc=1&oxid=[{$sKey}]');" /></td>
					</tr>
					[{if $oEntry->sccp_prodgroup__oxid->value == 'sccponlyone'}]
						[{assign var='sReadOnlyOne' value=''}]
					[{/if}]
				[{foreachelse}]
					<tr><td colspan="7">[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_RUN_INSTALL_FIRST'}]</td></tr>
				[{/foreach}]
			</tbody>
		</table>
		<input type="submit" value="[{oxmultilang ident='GENERAL_SAVE'}]" />
	</form>
[{/if}]
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
