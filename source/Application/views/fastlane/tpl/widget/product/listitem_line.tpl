
[{oxscript include="js/widgets/input-in_crement.js" priority=2}]

[{block name="widget_product_listitem_line"}]
    [{assign var="product"         value=$oView->getProduct()}]
    [{assign var="blDisableToCart" value=$oView->getDisableToCart()}]
    [{assign var="iIndex"          value=$oView->getIndex()}]
    [{assign var="showMainLink"    value=$oView->getShowMainLink()}]

    [{assign var="currency" value=$oView->getActCurrency()}]
    [{if $showMainLink}]
    [{assign var='_productLink' value=$product->getMainLink()}]
    [{else}]
    [{assign var='_productLink' value=$product->getLink()}]
    [{/if}]
    [{assign var="aVariantSelections" value=$product->getVariantSelections(null,null,1)}]
    [{assign var="blShowToBasket" value=true}] [{* tobasket or more info ? *}]
    [{if $blDisableToCart || $product->isNotBuyable()||($aVariantSelections&&$aVariantSelections.selections)||$product->getVariants()||($oViewConf->showSelectListsInList()&&$product->getSelections(1))}]
    [{assign var="blShowToBasket" value=false}]
    [{/if}]

    [{oxscript include="js/widgets/oxlistremovebutton.js" priority=10}]
    [{oxscript add="$('button.removeButton').oxListRemoveButton();"}]

    <form name="tobasket.[{$testid}]" [{if $blShowToBasket}]action="[{$oViewConf->getSelfActionLink()}]" method="post"[{else}]action="[{$_productLink}]" method="get"[{/if}]  class="js-oxProductForm">
        <div class="hidden">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="pgNr" value="[{$oView->getActPage()}]">
            [{if $recommid}]
        <input type="hidden" name="recommid" value="[{$recommid}]">
            [{/if}]
            [{if $blShowToBasket}]
            [{oxhasrights ident="TOBASKET"}]
        <input type="hidden" name="cl" value="[{$oViewConf->getTopActiveClassName()}]">
            [{if $owishid}]
        <input type="hidden" name="owishid" value="[{$owishid}]">
            [{/if}]
            [{if $toBasketFunction}]
        <input type="hidden" name="fnc" value="[{$toBasketFunction}]">
            [{else}]
        <input type="hidden" name="fnc" value="tobasket">
            [{/if}]
        <input type="hidden" name="aid" value="[{$product->oxarticles__oxid->value}]">
            [{if $altproduct}]
        <input type="hidden" name="anid" value="[{$altproduct}]">
            [{else}]
        <input type="hidden" name="anid" value="[{$product->oxarticles__oxnid->value}]">
            [{/if}]
        <input id="am_[{$testid}]" type="hidden" name="am" value="1">
            [{/oxhasrights}]
            [{else}]
        <input type="hidden" name="cl" value="details">
        <input type="hidden" name="anid" value="[{$product->oxarticles__oxnid->value}]">
            [{/if}]
        </div>

        <div class="panel panel-default product-panel">
            <div class="panel-heading" style="height: 40px;">

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 pic">
                        [{block name="widget_product_listitem_line_picturebox"}]
                        <a id="[{$testid}]" href="[{$_productLink}]" class="title" title="[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]">
                            <img src="[{$oViewConf->getImageUrl('spinner.gif')}]" data-src="[{$product->getThumbnailUrl()}]" alt="[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]" class="img-responsive">
                        </a>
                        [{/block}]
                    </div>
                    <div class="col-xs-12 col-sm-7 text">
                        <a id="[{$testid}]" href="[{$_productLink}]" class="lead" title="[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]">[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]</a>

                        [{block name="widget_product_listitem_line_description"}]
                        <div class="description">
                            [{if $recommid}]
                            <div>[{$product->text}]</div>
                            [{else}]
                            [{oxhasrights ident="SHOWSHORTDESCRIPTION"}]
                            [{$product->oxarticles__oxshortdesc->rawValue}]
                            [{/oxhasrights}]
                            [{/if}]
                        </div>
                        [{/block}]
                    </div>
                    <div class="col-xs-12 col-sm-3 price text-right">
                        <div class="functions">
                            [{block name="widget_product_listitem_line_price"}]
                            [{oxhasrights ident="SHOWARTICLEPRICE"}]
                            [{assign var="oUnitPrice" value=$product->getUnitPrice()}]
                            [{assign var="tprice"     value=$product->getTPrice()}]
                            [{assign var="price"      value=$product->getPrice()}]
                            [{if $tprice && $tprice->getBruttoPrice() > $price->getBruttoPrice()}]
                            <span>
                                            <span class="oldPrice">[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_REDUCEDFROM"}] <del>[{$product->getFTPrice()}] [{$currency->sign}]</del></span>
                                        </span>
                        <br/>
                            [{/if}]
                            [{block name="widget_product_listitem_line_price_value"}]
                            <span id="productPrice_[{$testid}]" class="lead price text-nowrap[{if $tprice && $tprice->getBruttoPrice() > $price->getBruttoPrice()}] text-danger[{/if}]">
                                            [{if $product->isRangePrice()}]
                                                [{oxmultilang ident="PRICE_FROM"}]
                                                [{if !$product->isParentNotBuyable()}]
                                                    [{$product->getFMinPrice()}]
                                                [{else}]
                                                    [{$product->getFVarMinPrice()}]
                                                [{/if}]
                                            [{else}]
                                                [{if !$product->isParentNotBuyable()}]
                                                    [{$product->getFPrice()}]
                                                [{else}]
                                                    [{$product->getFVarMinPrice()}]
                                                [{/if}]
                                            [{/if}]
                                            [{$currency->sign}]
                                            [{if $oView->isVatIncluded()}]
                                                [{if !($product->hasMdVariants() || ($oViewConf->showSelectListsInList() && $product->getSelections(1)) || $product->getVariants())}][{/if}]
                                            [{/if}]
                                        </span><br/>
                            [{/block}]

                            [{if $oUnitPrice}]
                            <span id="productPricePerUnit_[{$testid}]" class="pricePerUnit text-nowrap">[{$product->oxarticles__oxunitquantity->value}] [{$product->getUnitName()}] | [{oxprice price=$oUnitPrice currency=$currency}]/[{$product->getUnitName()}]</span>
                            [{elseif $product->oxarticles__oxweight->value }]
                            <span id="productPricePerUnit_[{$testid}]" class="pricePerUnit text-nowrap">
                                            <span class="weight" title="weight">[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ARTWEIGHT"}]</span>
                                            <span class="weight value">[{$product->oxarticles__oxweight->value}] [{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_ARTWEIGHT2"}]</span>
                                        </span>
                            [{/if}]
                            [{/oxhasrights}]
                            [{/block}]

                            [{if $product->loadAmountPriceInfo()}]
                            <div class="form-group">
                                [{include file="page/details/inc/priceinfo.tpl" oDetailsProduct=$product}]
                            </div>
                            [{/if}]

                            [{block name="widget_product_listitem_line_selections"}][{/block}]

                            [{block name="widget_product_listitem_line_tobasket"}]
                            [{if $blShowToBasket}]
                            [{oxhasrights ident="TOBASKET"}]
                            <div class="form-group">
                                [{*
                                            <span>[{ $oDetailsProduct->oxarticles__oxstock->value|var_dump}]</span>
                                            <div class="input-group inp-number-group">
                                                <div class="inp-number">
                                                    <span class="input-number-decrement">-</span>
                                                    <input id="amountToBasket_[{$testid}]" type="text" name="am" value="1" size="3" autocomplete="off" class="form-control input-number" type="text" min="0" max="">
                                                    <span class="input-number-increment">+</span>
                                                </div>

                                            </div>
                                            *}]
                                <span class="input-group-btn btn-cart-group">
                                                    <button id="toBasket_[{$testid}]" type="submit" class="btn btn-primary hasTooltip btn-shopping" title="[{oxmultilang ident="DETAILS_ADDTOCART"}]">
                                                        <i class="icon-shopping-cart"></i>
                                                        <span class="shopping-txt">In den Warenkorb</span>
                                                    </button>
                                                    [{if $removeFunction && (($owishid && ($owishid==$oxcmp_user->oxuser__oxid->value)) || (($wishid==$oxcmp_user->oxuser__oxid->value)) || $recommid)}]
                                                        <button triggerForm="remove_[{$removeFunction}][{$testid}]" type="submit" class="btn btn-danger removeButton hasTooltip" title="[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_REMOVE"}]">
                                                            <i class="icon-times"></i>
                                                        </button>
                                                    [{/if}]
                                                </span>
                            </div>
                            [{/oxhasrights}]
                            [{else}]
                            <a class="btn btn-primary btn-block" href="[{$_productLink}]" >[{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_MOREINFO"}]</a>

                            [{if $removeFunction && (($owishid && ($owishid==$oxcmp_user->oxuser__oxid->value)) || (($wishid==$oxcmp_user->oxuser__oxid->value)) || $recommid)}]
                            <button triggerForm="remove_[{$removeFunction}][{$testid}]" type="submit" class="btn btn-danger btn-block removeButton">
                                <i class="icon-times"></i> [{oxmultilang ident="WIDGET_PRODUCT_PRODUCT_REMOVE"}]
                            </button>
                            [{/if}]
                            [{/if}]
                            [{/block}]
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    [{if $removeFunction && (($owishid && ($owishid==$oxcmp_user->oxuser__oxid->value)) || (($wishid==$oxcmp_user->oxuser__oxid->value)) || $recommid)}]
    <form action="[{$oViewConf->getSelfActionLink()}]" method="post" id="remove_[{$removeFunction}][{$testid}]">
        <div>
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="cl" value="[{$oViewConf->getTopActiveClassName()}]">
            <input type="hidden" name="fnc" value="[{$removeFunction}]">
            <input type="hidden" name="aid" value="[{$product->oxarticles__oxid->value}]">
            <input type="hidden" name="am" value="0">
            <input type="hidden" name="itmid" value="[{$product->getItemKey()}]">
            [{if $recommid}]
        <input type="hidden" name="recommid" value="[{$recommid}]">
            [{/if}]
        </div>
    </form>
    [{/if}]
    [{/block}]
