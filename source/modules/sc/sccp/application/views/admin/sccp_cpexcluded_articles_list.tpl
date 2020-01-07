[{include file="headitem.tpl" title="OXCHKVERSION_MAIN_TITLE"|oxmultilangassign box="list"}]

<script type="text/javascript">
	if (parent.parent) {
		parent.parent.sShopTitle = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
		parent.parent.sMenuItem = "[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES' }]";
		parent.parent.sMenuSubItem = "[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_DETAIL' }]";
		parent.parent.sWorkArea = "[{$_act}]";
		parent.parent.setTitle();
	}
</script>

<script type="text/javascript">
	<!--
	window.onload = function () {
		top.reloadEditFrame();
		[{if $updatelist == 1}]
		top.oxid.admin.updateList('[{$oxid}]');
		[{/if}]
	};
	//-->
</script>

<div id="liste">

</div>

[{include file="pagetabsnippet.tpl"}]

</body>
</html>