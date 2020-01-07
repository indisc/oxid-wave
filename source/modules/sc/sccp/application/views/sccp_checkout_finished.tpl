[{capture append="oxidBlock_content"}]
	[{* Test your might! *}]
	<div class="sccp-finance finish-him">
		[{if $sFinanceError}]
			<div class="sccp-finance-error">[{$sFinanceError}]</div>
		[{else}]
			Sie haben den Kreditantrag abgeschlossen und werden auf die Abschlusseite weitergeleitet.
			<form action="[{$sTargetURL}]" method="POST" target="_top">
				[{$sHiddenFields}]
				<input type="hidden" name="cl" value="[{if $sClass}][{$sClass}][{else}]order[{/if}]" />
				<input type="hidden" name="fnc" value="[{if $sClass || $sFunction}][{$sFunction}][{else}]execute[{/if}]" />
				[{if $sContentOxid}]<input type="hidden" name="oxid" value="[{$sContentOxid}]" />[{/if}]
				<input type="hidden" name="data" value="[{$sParams}]" />
				<input type="hidden" name="signature" value="[{$sSignature}]" />
				<input type="submit" id="submitFinanceFurther" value="Weiter" />
			</form>
			<script type="text/javascript">
				// Moved to footer - look for function submitFinanceFurther
				// [{capture name="submitFinanceFurther"}]
				/**
				 * Callback function for a setTimeout to send the form back to the original page breaking the iframe.
				 */
				function submitFinanceFurther() {
					jQuery("#submitFinanceFurther").click();
				}
				jQuery(document).ready(function() { setTimeout(submitFinanceFurther,3000); });
				// [{/capture}]
			</script>
			[{oxscript add=$smarty.capture.submitFinanceFurther}]
		[{/if}]
	</div>

	<style type="text/css">
		#breadCrumb, #sidebar, #header, #footer { display: none; }
	</style>
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]