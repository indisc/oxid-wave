[{if $bottom_buttons->sccp_prodgroup_new }]
	<li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.prodgroup.new" href="#" onClick="createNewRow(); return false;">[{ oxmultilang ident="SCCP_FINANCING_PRODUCT_GROUPS_ADD" }]</a> |</li>
[{/if}]
[{if $bottom_buttons->sccp_offered_option_new }]
	<li><a [{if !$firstitem}]class="firstitem"[{assign var="firstitem" value="1"}][{/if}] id="btn.prodgroup.new" href="#" onClick="createNewRow(); return false;">[{ oxmultilang ident="SCCP_FINANCING_OFFERED_OPTIONS_ADD" }]</a> |</li>
[{/if}]
[{$smarty.block.parent}]