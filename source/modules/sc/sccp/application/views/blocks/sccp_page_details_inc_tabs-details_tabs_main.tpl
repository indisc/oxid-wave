[{assign var="oConfig" value=$oViewConf->getConfig()}]
[{assign var="bShowDetails" value=$oConfig->getShopConfVar('bShowDetails',null,'module:sccp')}]
[{if $bShowDetails}]
	[{assign var="aFinancingTable" value=$oView->getFinancingTable()}]
	[{if $aFinancingTable.aMonthRows}]
		[{capture append="tabs"}]<a href="#financing" data-toggle="tab">[{oxmultilang ident="SCCP_DETAILS_FINANCING"}]</a>[{/capture}]
		[{capture append="tabsContent"}]

			<div id="financing" class="tab-pane">
				<div id="financing-description">
					[{$aFinancingTable.sFinancingDescription}]
				</div>
				<table id="financing-table">
					<colgroup>
						<col width="15%" />
						<col width="16%" />
						<col width="20%" />
						<col width="17%" />
						<col width="15%" />
						<col width="17%" />
					</colgroup>
					<thead>
						<tr class="headrow">
							<td>[{oxmultilang ident='SCCP_FINANZIERUNG_DURATION'}]</td>
							<td>[{oxmultilang ident='SCCP_FINANZIERUNG_INTEREST_RATE'}]</td>
							<td>[{oxmultilang ident='SCCP_FINANZIERUNG_NOMINAL_INTEREST_RATE'}]</td>
							<td>[{oxmultilang ident='SCCP_FINANZIERUNG_REQUESTED_AMOUNT'}]</td>
							<td>[{oxmultilang ident='SCCP_FINANZIERUNG_AMOUNT_OF_PAYMENTS'}]</td>
							<td>[{oxmultilang ident='SCCP_FINANZIERUNG_MONTHLY_RATE'}]</td>
						</tr>
					</thead>
					<tbody>
						[{foreach from=$aFinancingTable.aMonthRows item='aMonthRow'}]
							<tr>
								<td>[{$aMonthRow->months}] [{oxmultilang ident='SCCP_FINANZIERUNG_MONTHS'}]</td>
								<td>[{$aMonthRow->interestRate}]</td>
								<td>[{$aMonthRow->nominalInterestRate}]</td>
								<td>[{$aMonthRow->totalAmount}]</td>
								<td>[{$aMonthRow->months}]</td>
								<td>[{$aMonthRow->monthlyRate}]</td>
							</tr>
						[{/foreach}]
					</tbody>
				</table>
				[{assign var='sFinanceText' value=$oView->getLowestFinancingRate()}]
				[{if $sFinanceText}]
					[{oxifcontent ident='sccppangvtext' object='oPAngVText'}]
						<div class="sccp-pangvtext">
							[{$oPAngVText->oxcontents__oxcontent->value}]
						</div>
					[{/oxifcontent}]
				[{/if}]
			</div>
		[{/capture}]
	[{/if}]
[{/if}]
[{$smarty.block.parent}]
