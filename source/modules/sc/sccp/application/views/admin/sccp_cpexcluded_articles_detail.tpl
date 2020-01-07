[{include file="headitem.tpl" title="OXDIAG_MAIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
	<!--

	function handleSubmit() {
		var oButton = document.getElementById("submitButton");
		oButton.disabled = true;
	}
	//-->
</script>

<style>

	.hidden {
		display: none;
	}

	.checker_comment {
		max-width: 600px;
		padding: 5px;
	}
	.result {
		padding: 15px;
		background-color: #F0F0F0 !important;
		border: 1px solid #C0C0C0 !important;
	}

	.selected {
		background-color: #F0F0F0 !important;
		border: 1px solid #C0C0C0 !important;
	}

</style>

<h1>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES'}]</h1>

<div>
	<form name="sccp-excluded-articles-form" id="sccp-excluded-articles-form" action="[{$oViewConf->getSelfLink()}]" onsubmit="handleSubmit()" method="post">
		[{$oViewConf->getHiddenSid()}]
		<input type="hidden" name="cl" value="sccp_cpexcluded_articles_detail">
		<table width="100%" border="0" style="max-width: 100%; min-width: 300px; border: 0 none transparent;">
			<colgroup>
				<col width="30%" />
				<col width="70%" />
			</colgroup>
			[{* #11894
		<tr>
			<td>
				<input id="fnc_showList" type="radio" name="fnc" value="showList"[{if $smarty.post.fnc == 'showList'}] checked="checked"[{/if}] />
			</td>
			<td>
				<label for="fnc_showList">[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_SHOW_UNASSIGNED"}]</label>
			</td>
		</tr>
*}]
			<tr>
				<td>
					[{* #11894 <input id="fnc_showFinancingOptions" type="radio" name="fnc" value="showFinancingOptions"[{if $smarty.post.fnc == 'showFinancingOptions'}] checked="checked"[{/if}] /> *}]
					<input id="fnc_showFinancingOptions" type="hidden" name="fnc" value="showFinancingOptions" />
				</td>
				<td>
					<label for="fnc_showFinancingOptions">[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_SHOW_OPTIONS_FOR"}]</label><br />
					<label for="sArtNum">[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_ARTICLENUMBER"}]:</label><input type="text" id="sArtNum" name="sArtNum" value="[{$sArtNum}]" />
				</td>
			</tr>
		</table>


		<input type="submit" class="edittext" id="submitButton" name="submitButton" value="[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_SUBMIT"}]" >

	</form>
	<br />
	<br />
</div>

[{if $sMessage}]
	<div id="sccp-message">
		[{oxmultilang ident=$sMessage}]
	</div>
	[{/if}]

[{if $smarty.post.fnc == 'showList'}]
	<div id="sccp-nofinance-articles-list">
		[{if $aArticles}]
		<table width="100%" border="0" style="max-width: 100%; min-width: 300px; border: 0 none transparent;">
			<colgroup>
				<col width="10%" />
				<col width="30%" />
				<col width="60%" />
			</colgroup>
			<tr>
				<td>[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_TD_ROWNUMBER"}]</td>
				<td>[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_TD_ARTICLENUMBER"}]</td>
				<td>[{oxmultilang ident="SCCP_CPEXCLUDED_ARTICLES_TD_ARTICLETITLE"}]</td>
			</tr>
			[{foreach from=$aArticles name='oFEArticles' key='iRow' item='oArticle'}]
			<tr>
				<td>[{$iRow+1}]</td>
				<td>[{$oArticle.oxartnum}]</td>
				<td>[{$oArticle.oxtitle}]</td>
			</tr>
			[{/foreach}]
		</table>
		[{elseif $sDefaultGroups}]
		[{$sDefaultGroups}]
		[{/if}]
	</div>
	[{/if}]

[{if $smarty.post.fnc == 'showFinancingOptions'}]
	<div>
		[{if $aFinancingOptions}]
			[{foreach from=$aFinancingOptions item='aArticleFinancingOptions' key='sArticleNumber'}]
				<br />
				<br />
				<table width="100%" border="0" style="max-width: 100%; min-width: 300px; border: 0 none transparent;">
					<colgroup>
						<col width="10%" />
						<col width="20%" />
						<col width="10%" />
						<col width="10%" />
						<col width="15%" />
						<col width="15%" />
						<col width="20%" />
					</colgroup>
					<tr>
						<td colspan="7"><strong>[{$sArticleNumber}]</strong></td>
					</tr>
					<tr>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_MONTHS'}]</td>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_GROUP_NAME'}]</td>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_TYPE_ID'}]</td>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_CLASS_ID'}]</td>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_PRODUCT_CODE'}]</td>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_EFFECTIVE_INTEREST'}]</td>
						<td>[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_TD_MONTHLY_RATE'}]</td>
					</tr>
					[{foreach from=$aArticleFinancingOptions item='oFinancingOption'}]
						<tr>
							<td>[{$oFinancingOption->months}]</td>
							<td>[{$oFinancingOption->productGroupName}]</td>
							<td>[{$oFinancingOption->productTypeID}]</td>
							<td>[{$oFinancingOption->productClassID}]</td>
							<td>[{$oFinancingOption->productCode}]</td>
							<td>[{$oFinancingOption->interestRate}]</td>
							<td>[{$oFinancingOption->monthlyRate}]</td>
						</tr>
					[{foreachelse}]
						<tr>
							<td colspan="7">[{oxmultilang ident='SCCP_CPEXCLUDED_ARTICLES_FINANCING_OPTIONS_NO_OPTIONS'}]</td>
						</tr>
					[{/foreach}]
				</table>
			[{/foreach}]
		[{else}]
		[{/if}]
	</div>
	[{/if}]
[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]