[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
	// <!--
	function ThisDate(sID) {
		document.myedit['editval[oxorder__oxpaid]'].value = sID;
	}

	function sendNow() {
		var sendOrderForm = document.getElementById('sendorder');

		sendOrderForm.submit();
	}
	function cancelOrder() {
		var cancelOrderForm = document.getElementById('cancelOrderForm');

		cancelOrderForm.submit();
	}
	// -->
</script>

[{if $readonly}]
	[{assign var="readonly" value="readonly disabled"}]
[{else}]
	[{assign var="readonly" value=""}]
[{/if}]

[{if $edit}]
	[{assign var="oCurr" value=$edit->getOrderCurrency()}]
[{else}]
	[{assign var="oConfig" value=$oViewConf->getConfig()}]
	[{assign var="oCurr" value=$oConfig->getActShopCurrencyObject()}]
[{/if}]
<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
	[{$oViewConf->getHiddenSid()}]
	<input type="hidden" name="cur" value="[{$oCurr->id}]">
	<input type="hidden" name="oxid" value="[{$oxid}]">
	<input type="hidden" name="cl" value="sccp_order_details">
</form>

[{if $edit}]
	[{include file="inc/sccp_cporder_errorbox.tpl"}]
	<table cellspacing="0" cellpadding="0" border="0" width="100%">

		<tr>

			<td valign="top" class="edittext" width="70%">
				<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
					[{$oViewConf->getHiddenSid()}]
					<input type="hidden" name="cur" value="[{$oCurr->id}]">
					<input type="hidden" name="cl" value="sinkacom_creditplusmodule_orderdetails">
					<input type="hidden" name="fnc" value="save">
					<input type="hidden" name="oxid" value="[{$oxid}]">
					<input type="hidden" name="editval[oxorder__oxid]" value="[{$oxid}]">

					<table cellspacing="0" cellpadding="0" border="0">
						[{block name="admin_order_main_form"}]
							<tr>
								<td class="edittext">
									[{oxmultilang ident="ORDER_MAIN_IPADDRESS"}]
								</td>
								<td class="edittext">
									[{$edit->oxorder__oxip->value}]
								</td>
							</tr>
							<tr>
								<td class="edittext">
									[{oxmultilang ident="GENERAL_ORDERNUM"}]
								</td>
								<td class="edittext">
									[{$edit->oxorder__oxordernr->value}]
								</td>
							</tr>
							<tr>
								<td class="edittext">
									[{oxmultilang ident="ORDER_MAIN_BILLNUM"}]
								</td>
								<td class="edittext">
									[{$edit->oxorder__oxbillnr->value}]
								</td>
							</tr>
							<tr>
								<td class="edittext" colspan="2">
									<br>
									<table style="border : 1px #A9A9A9; border-style : solid solid solid solid; padding-top: 5px; padding-bottom: 5px; padding-right: 5px; padding-left: 5px; width: 600px;">
										<tr>
											<td class="edittext" colspan="3">
												[{oxmultilang ident="ORDER_MAIN_PAYMENT_INFORMATION"}]
											</td>
										</tr>

										[{if $edit->blIsPaid}]
											<tr>
												<td class="edittext" valign="middle">
													<b>[{oxmultilang ident="ORDER_MAIN_ORDERPAID"}]</b>
												</td>
												<td class="edittext" valign="bottom">
													<b>[{$edit->oxorder__oxpaid->value|oxformdate:'datetime':true}]</b>
												</td>
												<td class="edittext"></td>
											</tr>
										[{/if}]

										<tr>
											<td class="edittext">[{oxmultilang ident="ORDER_MAIN_PAIDWITH"}]:</td>
											<td class="edittext">
												<b>[{$paymentType->oxpayments__oxdesc->value}]</b>
											</td>
										</tr>
									</table>
									<br />
									<table cellspacing="0" cellpadding="0" border="0">
										[{if $paymentType->aDynValues}]
											[{foreach from=$paymentType->aDynValues item=value}]
												[{assign var="ident" value='ORDER_OVERVIEW_'|cat:$value->name}]
												[{assign var="ident" value=$ident|oxupper}]
												<tr>
													<td class="edittext" width="70">
														[{oxmultilang ident=$ident}]
													</td>
													<td class="edittext">
														[{$value->value}]
													</td>
												</tr>
											[{/foreach}]
											<tr>
												<td class="edittext" colspan="3">&nbsp;</td>
											</tr>
										[{/if}]
									</table>
								</td>
							</tr>
							<tr>
								<td class="edittext" colspan="2">
									<table style="border: 1px #A9A9A9 solid; padding: 5px; width: 600px;">
										<tr>
											<td class="edittext" colspan="2">
												[{oxmultilang ident="ORDER_MAIN_SHIPPING_INFORMATION"}]
											</td>
										</tr>
										<tr>
											<td class="edittext">
												[{oxmultilang ident="ORDER_MAIN_TRACKCODE"}]&nbsp;&nbsp;
											</td>
											<td class="edittext">
												[{$edit->oxorder__oxtrackcode->value}]
												[{oxinputhelp ident="HELP_ORDER_MAIN_TRACKCODE"}]
												[{if $edit->oxorder__oxtrackcode->value && $edit->getShipmentTrackingUrl()}]
													<a href="[{$edit->getShipmentTrackingUrl()}]" target="_blank">[{oxmultilang ident="ORDER_MAIN_TRACKCODE_LINK"}]</a>
												[{/if}]
											</td>
										</tr>
										<tr>
											<td class="edittext">[{oxmultilang ident="ORDER_MAIN_DELTYPE"}]:</td>
											<td class="edittext">
												[{foreach from=$oShipSet key=sShipSetId item=oShipSet}]
													[{if $edit->oxorder__oxdeltype->value == $sShipSetId}][{$oShipSet->oxdeliveryset__oxtitle->value}][{/if}]
												[{/foreach}]
											</td>
										</tr>
										<tr>
											<td class="edittext" valign="middle">
												<b>[{oxmultilang ident="GENERAL_SENDON"}]</b>
											</td>
											<td class="edittext" valign="bottom">
												<b>[{$edit->oxorder__oxsenddate->value|oxformdate:'datetime':true}]</b>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						[{/block}]

						<tr>
							<td class="edittext" colspan="2">
								<table style="border: 1px #A9A9A9 solid; padding: 5px; width: 600px;">
									<tr>
										<td class="edittext">&nbsp;
										</td>
										<td class="edittext"><br>
											<input type="button" value="[{oxmultilang ident='CPORDER_DETAIL_CONFIRM_DELIVERY'}]" onclick="sendNow(); return false;" />
											<br /><br/>
											<input type="button" class="edittext" name="cancelOrderButton" id="cancelOrderButton" value="Storno" onclick="cancelOrder(); return false;" [{$readonly}]/>
										</td>
									</tr>
								</table>
							</td>

						</tr>
					</table>
				</form>
			</td>

			<!-- Anfang rechte Seite -->
			<td align="left" valign="top" width="30%">
				[{if $oContractData->cpReferenceNumber}]
					<strong>Kreditnehmer</strong><br /><br />
					[{$oLoanAddress->oxfname}] [{$oLoanAddress->oxlname}]<br />
					[{$oLoanAddress->oxstreet}]<br />
					[{$oLoanAddress->oxzip}] [{$oLoanAddress->oxcity}]<br /><br />
					<table width="100%">
						<tr>
							<td colspan="2"><strong>Vertragsdaten</strong><br /><br /></td>
						</tr>
						<tr>
							<td>Referenznummern:</td>
							<td>
								CreditPlus: [{$oContractData->cpReferenceNumber}]<br />
								Shop: [{$oContractData->cpDealerOrderNumber}]<br />
							</td>
						</tr>
						<tr>
							<td>Produktname:</td>
							<td>[{$oContractData->cpConditionName}]</td>
						</tr>
						<tr>
							<td>Status:</td>
							<td>[{$oContractData->cpState}]</td>
						</tr>
						<tr>
							<td>Preis:</td>
							<td>[{$oContractData->cpPrice}] [{$oCurr->sign}]</td>
						</tr>
						<tr>
							<td>Laufzeit:</td>
							<td>[{$oContractData->cpMonths}] Monate</td>
						</tr>
					</table>
				[{else}]
					[{oxmultilang ident='CPORDER_DETAIL_NOTARRIVED_AT_BANK'}]
				[{/if}]
				<form name="resetorder" id="resetorder" action="[{$oViewConf->getSelfLink()}]" method="post">
					[{$oViewConf->getHiddenSid()}]
					<input type="hidden" name="cur" value="[{$oCurr->id}]">
					<input type="hidden" name="cl" value="order_main">
					<input type="hidden" name="fnc" value="resetorder">
					<input type="hidden" name="oxid" value="[{$oxid}]">
					<input type="hidden" name="editval[oxorder__oxid]" value="[{$oxid}]">
				</form>
				<form name="sendorder" id="sendorder" action="[{$oViewConf->getSelfLink()}]" method="post">
					[{$oViewConf->getHiddenSid()}]
					<input type="hidden" name="cur" value="[{$oCurr->id}]">
					<input type="hidden" name="cl" value="sinkacom_creditplusmodule_orderdetails">
					<input type="hidden" name="fnc" value="sendorder">
					<input type="hidden" name="oxid" value="[{$oxid}]">
					<input type="hidden" name="editval[oxorder__oxid]" value="[{$oxid}]">
					<input type="hidden" name="sendmail" value='0'>
				</form>
				<form name="cancelOrderForm" id="cancelOrderForm" action="[{$oViewConf->getSelfLink()}]" method="post">
					[{$oViewConf->getHiddenSid()}]
					<input type="hidden" name="cur" value="[{$oCurr->id}]">
					<input type="hidden" name="cl" value="sinkacom_creditplusmodule_orderdetails">
					<input type="hidden" name="fnc" value="storno">
					<input type="hidden" name="oxid" value="[{$oxid}]">
					<input type="hidden" name="editval[oxorder__oxid]" value="[{$oxid}]">
					<input type="hidden" name="sendmail" value='0'>
				</form>
			</td>
		</tr>
	</table>
[{/if}]
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
