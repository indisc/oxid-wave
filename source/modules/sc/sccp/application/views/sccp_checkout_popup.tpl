[{capture append="oxidBlock_content"}]
	<div class="sccp-popup">
		<div class="sccp-payment-popup" style="min-height: 600px; background-color: white; overflow: hidden;">
			<iframe id="sccp-payment-iframe" scrolling="yes" src="[{$sTargetURL}]" class="sccp-payment-iframe" style="width: 100%; height: 100%; min-height: 100%;"></iframe>


			<div class="sccp-payment-buttons">

			</div>
		</div>

		<script type="text/javascript">
			// Transferred to footer, look for .sccp-payment-popup
			// [{capture name='sPopupBlock'}]
			jQuery(document).ready(function() {
				var oPopup = jQuery('.sccp-payment-popup'); 
				oPopup.dialog({
					resizable : true,
					openDialog: true,
					modal: true,
					width: '90%',
					height: "auto",
					minHeight: "600",
					maxHeight: "100%",
					close: function(oEvent, oUI) {
						document.location.href = '[{$sBackURL}]';
					},
					zIndex    : 10000,
					draggable : false,
					buttons: {
						"[{assign var='oConfig' value=$oView->getConfig()}][{if !$oConfig->isUtf()}]schließen[{else}]schlieÃŸen[{/if}]": function() {
							$( this ).dialog( "close" );
						}
					},

					open: function(event, ui) {
//						$('div.sccp-payment-buttons').html($('div.ui-dialog-buttonset').html());
//						$('div.ui-dialog-buttonset').remove();
					}
				});

				var bodyHeight = document.body.offsetHeight - 10;
				var docHeight = window.outerHeight - 10;
				var popupHeight = bodyHeight > docHeight ? bodyHeight : docHeight;
				oPopup.dialog( "option", "height", popupHeight );
				$( ".ui-widget-overlay").height(popupHeight);
				oPopup.dialog( "option", "position", { my: "center", at: "center", of: window } );
				var oCloseButton = $(".ui-icon.ui-icon-closethick");
				if ( !oCloseButton || oCloseButton.length == 0 ) {
					oCloseButton = $(".ui-dialog-titlebar-close");
				}
				oCloseButton.text("X");
				
				$(".ui-dialog.ui-widget").css("background-color","white");


			});
			// [{/capture}]
		</script>
		[{oxscript add=$smarty.capture.sPopupBlock}]
	</div>
[{/capture}]
[{include file="layout/page.tpl" sidebar="Right"}]