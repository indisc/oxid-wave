[{if ($order->oxorder__oxpaymenttype->value != 'sccp_financing') || ($order->oxorder__oxtransstatus->value == 'OK') }]
	[{$smarty.block.parent}]
	[{if ($order->oxorder__oxpaymenttype->value == 'sccp_financing')}]
		[{oxmultilang ident='SCCP_FINANCING_REQUESTNUMBER1'}][{$order->oxorder__oxtransid->value}][{oxmultilang ident='SCCP_FINANCING_REQUESTNUMBER2'}]
	[{/if}]
[{else}]
	<div class="sccp-need-to-pay-box">
		[{oxmultilang ident='SCCP_FINANCING_CONTINUE_TO_PAYMENT1'}][{*
		*}]<a class="sccp-continue-to-payment" href="[{oxgetseourl ident=$sContinueToPaymentURL}]">[{oxmultilang ident='SCCP_FINANCING_CONTINUE_TO_PAYMENT2'}]</a>[{*
		*}][{oxmultilang ident='SCCP_FINANCING_CONTINUE_TO_PAYMENT3'}][{*
		[{oxmultilang ident='SCCP_FINANCING_CONTINUE_TO_PAYMENT4'}][{*
		<a class="sccp-swap-payment" href="[{oxgetseourl ident=$sCancelAndSwapPaymentURL}]">[{oxmultilang ident='SCCP_FINANCING_CONTINUE_TO_PAYMENT5'}]</a>[{*
		[{oxmultilang ident='SCCP_FINANCING_CONTINUE_TO_PAYMENT6'}] *}]
	</div>
[{/if}]