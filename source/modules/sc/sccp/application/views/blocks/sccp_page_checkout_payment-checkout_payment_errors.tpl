[{assign var="iPayError" value=$oView->getPaymentError()}]
[{if $iPayError == 13021}]
	<div class="status error">[{oxmultilang ident="SCCP_ERROR_FINANCING_NOT_ALLOWED"}]</div>
[{else}]
	[{$smarty.block.parent}]
[{/if}]
