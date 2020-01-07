[{$smarty.block.parent}]
[{assign var="oConf" value=$oViewConf->getConfig()}]
[{if $oDetailsProduct && $oConf->getConfigParam('sNetGmcMetaGlobal') == "on" }]
<div itemscope itemtype="http://schema.org/Product">
    <meta itemprop="name" content="[{$oDetailsProduct->oxarticles__oxtitle->value}][{if $oDetailsProduct->oxarticles__oxvarselect->value != ""}][{$oDetailsProduct->oxarticles__oxvarselect->value}] [{/if}]" />
    [{if $oDetailsProduct->oxarticles__oxean->value|count_characters == 14}]
        <meta itemprop="gtin14" content="[{$oDetailsProduct->oxarticles__oxean->value}]" />
    [{elseif $oDetailsProduct->oxarticles__oxean->value|count_characters == 13}]
        <meta itemprop="gtin13" content="[{$oDetailsProduct->oxarticles__oxean->value}]" />
    [{elseif $oDetailsProduct->oxarticles__oxean->value|count_characters == 8}]
        <meta itemprop="gtin8" content="[{$oDetailsProduct->oxarticles__oxean->value}]" />
    [{/if}]
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        [{oxhasrights ident="SHOWARTICLEPRICE"}]
        [{if $oDetailsProduct->getPrice()}]
            [{assign var="oPrice" value=$oDetailsProduct->getPrice()}]
            [{if $oDetailsProduct->isParentNotBuyable() }]
                [{assign var="oPrice" value=$oDetailsProduct->getVarMinPrice()}]
            [{/if}]
            [{assign var="iBrutto" value=$oPrice->getBruttoPrice()}]
            [{if $iBrutto != 0 && $currency->name == "EUR"}]
                <meta itemprop="price" content="[{$iBrutto}]" />
                <meta itemprop="priceCurrency" content="[{$currency->name}]" />
            [{/if}]
        [{/if}]
        [{/oxhasrights}]
        [{if $oDetailsProduct->getStockStatus() == -1}]
            <meta itemprop="availability" content="http://schema.org/OutOfStock" />
        [{elseif $oDetailsProduct->getStockStatus() == 1}]
            <meta itemprop="availability" content="http://schema.org/InStock" />
        [{elseif $oDetailsProduct->getStockStatus() == 0}]
            <meta itemprop="availability" content="http://schema.org/InStock" />
        [{/if}]
        [{if $oDetailsProduct->oxarticles__netblzustand->value == 1}]
            <meta itemprop="itemCondition" content="http://schema.org/NewCondition" />
        [{elseif $oDetailsProduct->oxarticles__netblzustand->value == 0}]
            <meta itemprop="itemCondition" content="http://schema.org/UsedCondition" />
        [{elseif $oDetailsProduct->oxarticles__netblzustand->value == 2}]
            <meta itemprop="itemCondition" content="http://schema.org/OfferItemCondition " />
        [{/if}]
    </div>
</div>
[{/if}]

