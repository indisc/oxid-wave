[{block name="dd_widget_promoslider"}]
    [{assign var="oBanners" value=$oView->getBanners()}]
    [{assign var="currency" value=$oView->getActCurrency()}]

    [{if $oBanners|@count}]

    <div id="promo-carousel" class="flexslider">
        <ul class="slides">
            [{block name="dd_widget_promoslider_list"}]
            [{foreach from=$oBanners key="iPicNr" item="oBanner" name="promoslider"}]
            [{assign var="oArticle" value=$oBanner->getBannerArticle()}]
            [{assign var="sBannerPictureUrl" value=$oBanner->getBannerPictureUrl()}]
            [{if $sBannerPictureUrl}]
            <li class="item">
                [{assign var="sBannerLink" value=$oBanner->getBannerLink()}]
                [{if $sBannerLink}]
                <a href="[{$sBannerLink}]" title="[{$oBanner->oxactions__oxtitle->value}]">
                    [{/if}]
                    <img src="[{$sBannerPictureUrl}]" alt="[{$oBanner->oxactions__oxtitle->value}]" title="[{$oBanner->oxactions__oxtitle->value}]">

                    [{if $sBannerLink}]
                </a>
                [{/if}]
                [{if $oViewConf->getViewThemeParam('blSliderShowImageCaption') && $oArticle}]

                [{/if}]
            </li>
            [{/if}]
            [{/foreach}]
            [{/block}]
        </ul>
    </div>
    [{/if}]
    [{/block}]
