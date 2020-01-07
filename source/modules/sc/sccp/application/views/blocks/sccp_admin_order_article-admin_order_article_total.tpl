
[{$smarty.block.parent}]

[{if $edit->oxorder__oxpaymenttype->value == "sccp_financing"}]
	<div id="sccp-deactivation-notice" style="display: none;">[{include file="inc/sccp_cporder_errorbox.tpl" sError="403" sErrorMessage="SCCP_ADMIN_TOTAL_CHANGE_IN_CUSTOM_MENU" }]</div>
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
						(oInput.name == 'search') || // Search Button (Add article number from "sSearchArtNum")
						(oInput.name == 'sSearchArtNum') || // Search field for article number (Add Item to Order)
						(oInput.value && (oInput.value.trim() == '[{oxmultilang ident="ORDER_ARTICLE_UPDATE_STOCK"}]')) || // Refresh/Update amounts in order button
						(oInput.name && (oInput.name.substr(0,14) == 'aOrderArticles'))  // Input for Article Amount
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
			aInputs = document.getElementsByClassName('delete');
			for ( iInput in aInputs ) {
				if ( aInputs.hasOwnProperty(iInput) ) {
					oInput = aInputs[iInput];
					if (
						(oInput.tagName && (oInput.tagName.toLowerCase() == 'a')) // Delivery Set
					) {
						oInput.disabled = 'disabled';
						oInput.href = 'javascript:void(0);';
						aInputs[iInput] = oInput;
					}
				}
			}
			aInputs = document.getElementsByClassName('pause');
			for ( iInput in aInputs ) {
				if ( aInputs.hasOwnProperty(iInput) ) {
					oInput = aInputs[iInput];
					if (
						(oInput.tagName && (oInput.tagName.toLowerCase() == 'a')) // Delivery Set
					) {
						oInput.disabled = 'disabled';
						oInput.href = 'javascript:void(0);';
						aInputs[iInput] = oInput;
					}
				}
			}
			
			var oNotice = document.getElementById('sccp-deactivation-notice');
			var oFormUpTop = document.getElementById('transfer');
			oFormUpTop.outerHTML = oFormUpTop.outerHTML+oNotice.innerHTML;
		},false);
	</script>
[{/if}]