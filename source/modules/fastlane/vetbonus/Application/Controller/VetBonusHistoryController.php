<?php


namespace Fastlane\VetBonus\Application\Controller;


class VetBonusHistoryController extends \OxidEsales\Eshop\Application\Controller\AccountOrderController
{

    protected $_sThisTemplate = 'page/account/vetbonusHistory.tpl';

    public function render()
    {
        parent::render();

        // is logged in ?
        $oUser = $this->getUser();
        if (!$oUser) {
            return $this->_sThisTemplate = $this->_sThisLoginTemplate;
        }

        return $this->_sThisTemplate;
    }
}