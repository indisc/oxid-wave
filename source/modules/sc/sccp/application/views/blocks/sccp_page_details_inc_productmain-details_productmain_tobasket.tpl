[{$smarty.block.parent}]
[{oxhasrights ident="TOBASKET"}]
	[{assign var="oConfig" value=$oViewConf->getConfig()}]
	[{assign var="bShowDetails" value=$oConfig->getShopConfVar('bShowDetails',null,'module:sccp')}]
	[{if $bShowDetails}]
		[{if method_exists($oView,'getLowestFinancingRate')}]
			[{assign var='sFinanceText' value=$oView->getLowestFinancingRate()}]
			[{if $sFinanceText}]
				<div id="financing-mini-info">[{$sFinanceText}]</div>
			[{/if}]
		[{/if}]
	[{/if}]
[{/oxhasrights}]