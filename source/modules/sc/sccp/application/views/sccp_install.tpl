[{capture append="oxidBlock_content"}]
	<div class="installsuccess">
		Die Installation ergab folgendes:
		[{if $bSuccess}]
			[{$sSuccess}]
		[{else}]
			[{$sError}]
		[{/if}]
	</div>
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]