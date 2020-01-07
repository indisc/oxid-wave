[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{assign var='iRowCount' value=0}]
[{if $oList}]
	[{assign var='iRowCount' value=$oList|@count}]
[{/if}]
[{assign var='oConfig' value=$oViewConf->getConfig()}]
[{assign var='oCurrency' value=$oConfig->getActShopCurrencyObject()}]
[{if !$readonly}]
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
				'<td><input type="text" name="editlist['+sOxid+'][sccp_prodcode]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_PRODCODE'}]" value="" /></td>'+
				'<td><input type="text" name="editlist['+sOxid+'][sccp_months]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_MONTHS'}]" value="" /></td>'+
				'<td><input type="text" name="editlist['+sOxid+'][sccp_interest]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_INTEREST'}]" value="" /></td>'+
				'<td><input type="text" name="editlist['+sOxid+'][sccp_ratefactor]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR'}]" value="" /></td>'+
				'<td><input type="hidden" name="editlist['+sOxid+'][sccp_active]" value="0" /><input type="checkbox" name="editlist['+sOxid+'][sccp_active]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_ACTIVE'}]" value="1" /></td>'+
				'<td><input type="hidden" name="editlist['+sOxid+'][delete]" value="0" /><input type="checkbox" name="editlist['+sOxid+'][delete]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_DELETE'}]" value="1" /></td>'+
				'<td><input type="button" value="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_ASSIGN_PROD_GROUPS'}]" class="edittext" onclick="showDialog(\'&cl=sinkacom_creditplusmodule_offeredoption&aoc=1&oxid='+sOxid+'\');" /></td>';
		oTableBody.appendChild(oTR);
	}
	// -->
</script>
[{/if}]

[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
[{else}]
	[{assign var="readonly" value=""}]
[{/if}]

[{if $oList}]
	[{include file="inc/sccp_cporder_errorbox.tpl"}]
	[{if $aAdditionalMessages}]
		[{foreach from=$aAdditionalMessages item='sMessage'}]
			<div class="redbox">
				<div class="sccp-finance-error">[{$sMessage}]</div>
			</div>
		[{/foreach}]
	[{/if}]
	[{oxmultilang ident='SCCP_CPOFFERED_OPTIONS_DESCRIPTION'}]<br /><br />
	<form action="[{$oViewConf->getSelfLink()}]" method="post">
		[{$oViewConf->getHiddenSid()}]
		<input type="hidden" name="cl" value="sinkacom_creditplusmodule_offeredoption">
		<input type="hidden" name="fnc" value="save">
		<table id="listTable" style="width: 90%;" width="90%">
			<colgroup>
				<col width="5%" />
				<col width="15%" />
				<col width="5%" />
				<col width="10%" />
				<col width="10%" />
				<col width="5%" />
				<col width="5%" />
				<col width="45%" />
			</colgroup>
			<thead>
				<tr>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_PRODUCT_GROUPS_NUMBER'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_PRODCODE'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_MONTHS'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_INTEREST'}]
					</th>
					<th style="text-align: left; cursor: help;" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR_INFO'}]">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_ACTIVE'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_DELETE'}]
					</th>
					<th style="text-align: left;">
						[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_ASSIGN_PROD_GROUPS'}]
					</th>
				</tr>
			</thead>
			<tbody id="listTableBody">
				[{foreach name='prods' from=$oList item='oEntry' key='sKey'}]
					<tr>
						<td>[{$smarty.foreach.prods.iteration}]</td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_prodcode]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_PRODCODE'}]" value="[{$oEntry->sccp_offered_option__sccp_prodcode->value}]" [{$readonly}] /></td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_months]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_MONTHS'}]" value="[{$oEntry->sccp_offered_option__sccp_months->value}]" [{$readonly}] /></td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_interest]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_INTEREST'}]" value="[{$oEntry->sccp_offered_option__sccp_interest->value|number_format:$oCurrency->decimal:$oCurrency->dec:$oCurrency->thousand}]" [{$readonly}] /></td>
						<td><input type="text" name="editlist[[{$sKey}]][sccp_ratefactor]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_RATEFACTOR'}]" value="[{if $oEntry->sccp_offered_option__sccp_ratefactor->value > 0.00}][{$oEntry->sccp_offered_option__sccp_ratefactor->value}][{/if}]" [{$readonly}] /></td>
						<td><input type="hidden" name="editlist[[{$sKey}]][sccp_active]" value="0" /><input type="checkbox" name="editlist[[{$sKey}]][sccp_active]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_ACTIVE'}]" value="1" [{if $oEntry->sccp_offered_option__sccp_active->value === '1'}]checked="checked" [{/if}][{$readonly}] /></td>
						<td><input type="hidden" name="editlist[[{$sKey}]][delete]" value="0" /><input type="checkbox" name="editlist[[{$sKey}]][delete]" title="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_DELETE'}]" value="1" [{$readonly}] /></td>
						<td><input [{$readonly}] type="button" value="[{oxmultilang ident='SCCP_FINANCING_OFFERED_OPTIONS_ASSIGN_PROD_GROUPS'}]" class="edittext" onclick="showDialog('&cl=sinkacom_creditplusmodule_offeredoption&aoc=1&oxid=[{$sKey}]');" /></td>
					</tr>
				[{/foreach}]
			</tbody>
		</table>
		<input type="submit" value="[{oxmultilang ident='GENERAL_SAVE'}]" />
	</form>
[{/if}]
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
