<?php
/**
 * @TODO LICENCE
 */
namespace fastlane\hfconact\Controller\Admin;

/**
 * Class LinslinSliderMain.
 */
class MainController extends \OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController
{

    /**
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        return "main.tpl";
    }
}
