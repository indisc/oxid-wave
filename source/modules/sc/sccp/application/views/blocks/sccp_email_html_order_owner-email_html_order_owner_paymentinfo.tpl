[{if $payment->oxuserpayments__oxpaymentsid->value == "sccp_financing"}]
	<h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
		[{oxmultilang ident="PAYMENT_METHOD" suffix="COLON"}]
	</h3>
	<p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 5px 0 10px;">
		<b>[{$payment->oxpayments__oxdesc->value}]
			[{assign var="oPaymentCostPrice" value=$basket->getPaymentCost()}]
			[{if $oPaymentCostPrice}]([{oxprice price=$oPaymentCostPrice->getBruttoPrice() currency=$currency}])[{/if}]</b>
	</p>
	<br />
	[{capture name='sLinkText'}][{oxmultilang ident='SCCP_FINANCING_MAIL_LINK_RETRY'}][{/capture}]
	[{$smarty.capture.sLinkText|replace:'http://retry.me/':$sRetryURL}]
[{else}]
	[{$smarty.block.parent}]
[{/if}]
