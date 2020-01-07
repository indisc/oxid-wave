[{if $payment->oxuserpayments__oxpaymentsid->value == "sccp_financing"}]
	<h3 style="font-weight: bold; margin: 20px 0 7px; padding: 0; line-height: 35px; font-size: 12px;font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; border-bottom: 4px solid #ddd;">
		[{oxmultilang ident="EMAIL_ORDER_CUST_HTML_PAYMENTMETHOD"}]
	</h3>
	<p style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; margin: 5px 0 10px;">
		<b>[{$payment->oxpayments__oxdesc->value}]
			[{if $basket->getPaymentCosts() }]([{ $basket->getFPaymentCosts() }] [{ $currency->sign}])[{/if}]</b>
	</p>
	<br />
	[{capture name='sLinkText'}][{oxmultilang ident='SCCP_FINANCING_MAIL_LINK_RETRY'}][{/capture}]
	[{$smarty.capture.sLinkText|replace:'http://retry.me/':$sRetryURL}]
[{else}]
	[{$smarty.block.parent}]
[{/if}]
