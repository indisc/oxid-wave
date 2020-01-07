[{capture append="oxidBlock_content"}]
	[{if $sState == 'paid'}]
		[{oxifcontent ident='sccpabortpaid' object='oAbortText'}]
			<h1>[{$oAbortText->oxcontents__oxtitle->value}]</h1>
			<div>[{$oView->replaceUrl($oAbortText->oxcontents__oxcontent->value)}]</div>
		[{/oxifcontent}]
	[{elseif $sState == 'oxid-don-mismatch'}]
		[{oxifcontent ident='sccpabortmismatch' object='oAbortText'}]
			<h1>[{$oAbortText->oxcontents__oxtitle->value}]</h1>
			<div>[{$oView->replaceUrl($oAbortText->oxcontents__oxcontent->value)}]</div>
		[{/oxifcontent}]
	[{else}]
		[{oxifcontent ident='sccpabortinfo' object='oAbortText'}]
			<h1>[{$oAbortText->oxcontents__oxtitle->value}]</h1>
			<div>[{$oView->replaceUrl($oAbortText->oxcontents__oxcontent->value)}]</div>
		[{/oxifcontent}]
	[{/if}]
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]