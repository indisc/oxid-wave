[{capture append="oxidBlock_content"}]
	<div class="sccp-iframe">
		<div class="sccp-buttons">
			<button id="sccp-iframe-close-1" class="sccp-button">schließen</button>
		</div>
		<iframe src="[{$sTargetURL}]" class="sccp-payment-iframe" style="width: 100%; height: 525px; min-height: 100%;"></iframe>
		<div class="sccp-buttons">
			<button id="sccp-iframe-close-2" class="sccp-button">schließen</button>
			[{oxscript add="jQuery('#sccp-iframe-close-1, #sccp-iframe-close-2').click(function(){ document.location.href = '$sBackURL'; });"}]
		</div>
	</div>
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]