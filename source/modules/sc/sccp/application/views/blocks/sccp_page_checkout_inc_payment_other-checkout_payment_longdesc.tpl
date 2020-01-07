
[{if $paymentmethod->oxpayments__oxid->value == 'sccp_financing'}]
	[{assign var="oConfig" value=$oViewConf->getConfig()}]
	[{assign var="bShowPayment" value=$oConfig->getShopConfVar('bShowPayment',null,'module:sccp')}]
	[{if $paymentmethod->oxpayments__oxlongdesc->value|trim}]
		<div class="desc">
			[{$paymentmethod->oxpayments__oxlongdesc->getRawValue()}]
			[{if $bShowPayment}]
				[{assign var="aFinancingMonths" value=$oxcmp_basket->getFinancingMonths()}]
				[{if $aFinancingMonths}]
					<div id="financing">
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
							[{foreach from=$aFinancingMonths item='aMonthRow'}]
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
						[{oxifcontent ident='sccppangvtext' object='oPAngVText'}]
							<div class="sccp-pangvtext">
								[{$oPAngVText->oxcontents__oxcontent->value}]
							</div>
						[{/oxifcontent}]
					</div>
				[{/if}]
			[{/if}]
		</div>
	[{/if}]
[{else}]
	[{$smarty.block.parent}]
[{/if}]