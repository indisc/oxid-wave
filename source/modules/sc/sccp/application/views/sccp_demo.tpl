[{capture append="oxidBlock_content"}]
	<div class="sccp-finance returner">
		[{if $bError}]
			[{$sError}]
		[{else}]
			<h2>CreditPlus Finanzierung <strong style="color: #CF0000">Demo Modus</strong></h2>
			<div style="margin-bottom: 20px;">Wenn Sie einen Fehler produzieren wollen, ändern Sie die Daten in den Textboxen. Die Folgeseite sieht immer identisch aus, die Prüfung geschieht erst nach der Rückkehr in den Shop.<br />Wenn Sie nichts tun und lediglich auf den Button klicken und auf der Folgeseite auf den Button klicken (oder abwarten), dann gelangen Sie zur Abschluss Seite des Shops.</div>
			<form action="[{$sTargetURL}]" method="POST">
				<label for="params">Params: </label><input type="text" id="params" name="params" value="[{$sParams}]" />
				<label for="signature">Signature: </label><input type="text" id="signature" name="signature" value="[{$sSignature}]" />
				<input type="submit" value="Weiter zum Abschluss" />
			</form>
		[{/if}]
	</div>
	<style type="text/css">
		#breadCrumb, #sidebar, #header, #footer { display: none; }
	</style>
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]