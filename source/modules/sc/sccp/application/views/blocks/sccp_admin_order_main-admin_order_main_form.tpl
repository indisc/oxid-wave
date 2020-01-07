[{if $edit->oxorder__oxpaymenttype->value == "sccp_financing"}]
<tr>
	<td colspan="2">
		[{include file="inc/sccp_cporder_errorbox.tpl" sError="403" sErrorMessage="SCCP_ADMIN_CHANGE_IN_CUSTOM_MENU" }]
	</td>
</tr>
[{/if}]

[{$smarty.block.parent}]

[{if $edit->oxorder__oxpaymenttype->value == "sccp_financing"}]
<tr>
	<td colspan="2">
		<script type="text/javascript">
			window.addEventListener('load',function() {
				
				var aInputs = null;
				var iInput = null;
				var oInput = null;

				aInputs = document.getElementsByTagName('button');
				for ( iInput in aInputs ) {
					if ( aInputs.hasOwnProperty(iInput) ) {
						aInputs[iInput].disabled = 'disabled';
					}
				}
				aInputs = document.getElementsByTagName('input');
				for ( iInput in aInputs ) {
					if ( aInputs.hasOwnProperty(iInput) ) {
						oInput = aInputs[iInput];
						if (
							(oInput.id == 'shippNowButton') || // Ship now button, as it is written
							(oInput.id == 'resetShippingDateButton') || // Reset shipping date button
							(oInput.id == 'sendmail') || // Send mail now checkbox
							(oInput.name == 'editval[oxorder__oxdelcost]') || // Delivery cost
							(oInput.name == 'editval[oxorder__oxdiscount]') || // Discount
							(oInput.id == 'sendmail') || // Send mail now checkbox
							(oInput.value && (oInput.value.trim() == '[{oxmultilang ident="GENERAL_SEND"}]'))  // Send download links now
						) {
							oInput.disabled = 'disabled';
							aInputs[iInput] = oInput;
						}
					}
				}
				aInputs = document.getElementsByTagName('select');
				for ( iInput in aInputs ) {
					if ( aInputs.hasOwnProperty(iInput) ) {
						oInput = aInputs[iInput];
						if (
							(oInput.name == 'setDelSet') // Delivery Set
						) {
							oInput.disabled = 'disabled';
							aInputs[iInput] = oInput;
						}
					}
				}
			},false);
		</script>
	</td>
</tr>
[{/if}]