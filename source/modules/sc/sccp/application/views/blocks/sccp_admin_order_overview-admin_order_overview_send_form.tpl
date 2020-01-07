
[{$smarty.block.parent}]

[{if $edit->oxorder__oxpaymenttype->value == "sccp_financing"}]
	<tr>
		<td>
			<div id="sccp-deactivation-notice" style="display: none;">[{include file="inc/sccp_cporder_errorbox.tpl" sError="403" sErrorMessage="SCCP_ADMIN_CHANGE_IN_CUSTOM_MENU" }]</div>
			<script type="text/javascript">
				window.addEventListener('load',function() {
					
					var aInputs = null;
					var iInput = null;
					var oInput = null;
					aInputs = document.getElementsByTagName('input');
					for ( iInput in aInputs ) {
						if ( aInputs.hasOwnProperty(iInput) ) {
							oInput = aInputs[iInput];
							if (
								(oInput.name == 'sendmail') || // Send Mail Checkbox
								(oInput.value && (oInput.value.trim() == '[{oxmultilang ident="GENERAL_NOWSEND"}]')) || // Send now button for delivery confirmation
								(oInput.value && (oInput.value.trim() == '[{oxmultilang ident="GENERAL_SETBACKSENDTIME"}]')) // Reset send time button
							) {
								oInput.disabled = 'disabled';
								aInputs[iInput] = oInput;
							}
						}
					}
					var oNotice = document.getElementById('sccp-deactivation-notice');
					var oFormUpTop = document.getElementById('transfer');
					oFormUpTop.outerHTML = oFormUpTop.outerHTML+oNotice.innerHTML;
				},false);
			</script>
		</td>
	</tr>
[{/if}]