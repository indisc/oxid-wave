[{$smarty.block.parent}]

[{*if $oViewConf->getActiveShopId() == 2 *}]
<div class="form-group">
    <label class="control-label col-lg-3">[{oxmultilang ident="VETBONUS_CHECK"}]</label>
    <div class="col-lg-9">
        <input type="hidden" name="chbx_vetbonus" value="0">
        <div class="checkbox">
            <label class="chbx_vetbonus">
                <input id="chbx_vetbonus" type="checkbox" name="invadr[oxuser__extenduser_hf24_vetrefnr]" value="[{$oxcmp_user->oxuser__extenduser_hf24_vetrefnr->value}]">
                [{oxmultilang ident="VETBONUS_CHECK_DESCRIPTION"}]
            </label>
        </div>
    </div>
</div>
   [{*/if*}]