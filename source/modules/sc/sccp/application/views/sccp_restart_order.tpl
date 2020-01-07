[{capture append="oxidBlock_content"}]
	[{oxifcontent ident="sccporderrestart" object='oOrderRestart'}]
		<div class="sccp-financing-restart-title">
			<h1>[{$oOrderRestart->oxcontents__oxtitle->value}]</h1>
		</div>
		<div class="sccp-financing-restart-body">
			[{$oOrderRestart->oxcontents__oxcontent->value}]
		</div>
	[{/oxifcontent}]
	[{if $sState == 'paid'}]
		[{oxifcontent ident='sccpabortpaid' object='oAbortText'}]
			<h2>[{$oAbortText->oxcontents__oxtitle->value}]</h2>
			<div>[{$oView->replaceUrl($oAbortText->oxcontents__oxcontent->value)}]</div>
		[{/oxifcontent}]
	[{elseif $sState == 'oxid-don-mismatch'}]
		[{oxifcontent ident='sccpabortmismatch' object='oAbortText'}]
			<h2>[{$oAbortText->oxcontents__oxtitle->value}]</h2>
			<div>[{$oView->replaceUrl($oAbortText->oxcontents__oxcontent->value)}]</div>
		[{/oxifcontent}]
	[{else}]
		[{if $oOrder}]
			<div id="basketContainer" class="lineBox">
				[{include file="page/checkout/inc/basketcontents.tpl" editable=false oxcmp_basket=$oView->getPreparedBasket()}]
			</div>
			<form action="[{$sTargetURL}]" method="post">
				[{$sPostFields}]
				<button class="sccp-restart-order-button submitButton largeButton" type="submit">[{oxmultilang ident="SCCP_FINANCING_RESTART_BUTTON_SUBMIT"}]</button>
			</form>
		[{/if}]
	[{/if}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]