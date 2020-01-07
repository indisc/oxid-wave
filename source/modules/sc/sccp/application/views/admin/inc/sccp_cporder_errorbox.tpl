[{if $sError == 200}]
	<div class="greenbox">
		[{if $sErrorMessage == $sErrorMessage|strtoupper}]
			[{oxmultilang ident=$sErrorMessage}]
		[{else}]
			[{$sErrorMessage}]
		[{/if}]
	</div>
[{elseif $sError}]
	<div class="redbox">
		[{if $sErrorMessage == $sErrorMessage|strtoupper}]
			[{oxmultilang ident=$sErrorMessage}]
		[{else}]
			[{$sErrorMessage}]
		[{/if}]
	</div>
[{/if}]