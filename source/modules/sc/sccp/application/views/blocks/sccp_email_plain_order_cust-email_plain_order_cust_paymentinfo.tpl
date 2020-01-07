[{$smarty.block.parent}][{if $payment->oxuserpayments__oxpaymentsid->value == "sccp_financing"}]
[{capture name='sLinkText'}][{oxmultilang ident='SCCP_FINANCING_MAIL_LINK_RETRY_PLAIN'}][{/capture}]
[{$smarty.capture.sLinkText|replace:'http://retry.me/':$sRetryURL}]
[{/if}]
