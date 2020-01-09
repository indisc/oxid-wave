<?php


namespace Fastlane\VetBonus\Application\Model;


class VetBonusUser extends \OxidEsales\Eshop\Application\Model\User
{
/*
    protected $_sVetBonusGroupId = 'Tieraerzte';
    protected $_sNormalUserGroupId = 'oxidnewcustomer';

    public function isVetbonus()
    {
        return $this->inGroup($this->_sVetBonusGroupId);
    }

    public function save()
    {
        if( isset($this->oxuser__oxid->value) ) {
            if (isset($this->oxuser__premium)) {
                $this->addToGroup($this->_sVetBonusGroupId);
                $this->removeFromGroup($this->_sNormalUserGroupId);
            } else {
                $this->removeFromGroup($this->_sVetBonusGroupId);
                $this->addToGroup($this->_sNormalUserGroupId);
            }
        }

        return parent::save();
    }

    public function addToGroup($sGroupID)
    {
        if($sGroupID == 'oxidcustomer')
            parent::addToGroup($this->_sNormalUserGroupId);

        return parent::addToGroup($sGroupID);
    }
*/
}