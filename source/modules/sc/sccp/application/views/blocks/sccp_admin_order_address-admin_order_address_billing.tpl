
[{$smarty.block.parent}]

[{if $edit->oxorder__oxpaymenttype->value == "sccp_financing"}]
	<tr>
		<td>
			<div id="sccp-edit-notice" style="display: none;">[{include file="inc/sccp_cporder_errorbox.tpl" sError="200" sErrorMessage="SCCP_ADMIN_KEEP_ADDRESSES_SYNCHRONIZED"}]</div>
			<script type="text/javascript">
				window.addEventListener('load',function() {
					// No deactivation, just a "small" notice
					var oNotice = document.getElementById('sccp-edit-notice');
					var oFormUpTop = document.getElementById('transfer');
					oFormUpTop.outerHTML = oFormUpTop.outerHTML+oNotice.innerHTML;
				},false);
			</script>
		</td>
	</tr>
[{/if}]