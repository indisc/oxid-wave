[{oxscript include="js/libs/jquery.flexslider.min.js" priority=2}]
[{oxstyle include="css/libs/jquery.flexslider.min.css"}]

[{oxscript include="js/newslider.js"}]


<div class="row">
    <div id="productSlider" class="manufacturer-slider boxwrapper">
        <div class="page-header">
            <h3>[{oxmultilang ident="OUR_BRANDS"}]</h3>
            <span class="subhead">[{oxmultilang ident="MANUFACTURERSLIDER_SUBHEAD"}]</span>
        </div>

        <div class="flexslider">
            <ul class="slides">

                [{foreach from=$products item="_product" name="productlist"}]
                    [{counter print=false assign="productlistCounter"}]
                    [{assign var="testid" value=$listId|cat:"_"|cat:$smarty.foreach.productlist.iteration}]
                        <li>
                            [{oxid_include_widget cl="oxwArticleBox" _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() iLinkType=$_product->getLinkType() _object=$_product anid=$_product->getId() sWidgetType=product sListType=listitem_$type iIndex=$testid blDisableToCart=$blDisableToCart isVatIncluded=$oView->isVatIncluded() showMainLink=$showMainLink recommid=$recommid owishid=$owishid toBasketFunction=$toBasketFunction removeFunction=$removeFunction altproduct=$altproduct inlist=$_product->isInList() skipESIforUser=1 testid=$testid}]
                        </li>
                [{/foreach}]


            </ul>
        </div>
    </div>
</div>

