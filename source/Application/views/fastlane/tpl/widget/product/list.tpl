[{if !$type}]
    [{assign var="type" value="infogrid"}]
    [{assign var="realtype" value="infogrid"}]
    [{else}]
    [{assign var="realtype" value=$type}]
    [{/if}]

[{if !$iProductsPerLine}]
    [{assign var="iProductsPerLine" value=3}]
    [{/if}]

[{if $type == 'infogrid'}]
    [{assign var="iProductsPerLine" value=2}]
    [{elseif $type == 'grid'}]
    [{assign var="type" value="infogrid"}]
    [{assign var="iProductsPerLine" value=3}]
    [{elseif $type == 'line'}]
    [{assign var="iProductsPerLine" value=1}]
    [{/if}]

<div class="boxwrapper" id="boxwrapper_[{$listId}]">
    [{if $head}]
    [{if $header eq "light"}]
    <div class="page-header">
        <span class="h3">[{$head}]</span>
    </div>
    [{else}]
    <div class="page-header">
        <h2 class="pull-left">[{$head}]</h2>
        [{if $rsslink}]
        <small class="pull-right">
            <a class="rss" id="[{$rssId}]" href="[{$rsslink.link}]" title="[{$rsslink.title}]" target="_blank">
                <i class="icon-rss"></i> [{$rsslink.title}]
            </a>
        </small>
        [{/if}]
        <div class="clearfix"></div>
    </div>
    [{/if}]
    [{/if}]

    [{assign var="productsCount" value=$products|@count}]
    [{if $productsCount gt 0}]
    [{math equation="x / y" x=12 y=$iProductsPerLine assign="iColIdent"}]

    <div class="list-container" id="[{$listId}]">
        [{foreach from=$products item="_product" name="productlist"}]
        [{counter print=false assign="productlistCounter"}]
        [{assign var="testid" value=$listId|cat:"_"|cat:$smarty.foreach.productlist.iteration}]

        [{if $productlistCounter == 1}]
        <div class="row [{$realtype}]View newItems">
        [{/if}]

        <div class="productData col-xs-12[{if $type != 'line'}] col-sm-[{$iColIdent}][{/if}] productBox">
            [{oxid_include_widget cl="oxwArticleBox" _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() iLinkType=$_product->getLinkType() _object=$_product anid=$_product->getId() sWidgetType=product sListType=listitem_$type iIndex=$testid blDisableToCart=$blDisableToCart isVatIncluded=$oView->isVatIncluded() showMainLink=$showMainLink recommid=$recommid owishid=$owishid toBasketFunction=$toBasketFunction removeFunction=$removeFunction altproduct=$altproduct inlist=$_product->isInList() skipESIforUser=1 testid=$testid}]
        </div>

        [{if $productlistCounter%$iProductsPerLine == 0 || $productsCount == $productlistCounter}]
        </div>
        [{/if}]

        [{if $productlistCounter%$iProductsPerLine == 0 && $productsCount > $productlistCounter}]
        <div class="row [{$realtype}]View newItems">
            [{/if}]
            [{/foreach}]

            [{* Counter resetten *}]
            [{counter print=false assign="productlistCounter" start=0}]
        </div>
        [{/if}]
    </div>