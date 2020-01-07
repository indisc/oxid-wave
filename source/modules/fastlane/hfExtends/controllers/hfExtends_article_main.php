<?php

class hfExtends_oxarticle extends hfExtends_oxarticle_parent
{
    /**
     * Product content tabs
     *
     * @var array
     */
    protected $_aContentTabs = [];

    /**
     * Get article content tab
     *
     * @param int $index
     * @return object $oField field object
     */
    public function getContentTab($index = 2)
    {
        if ($index < 2 || $index > 5)
            return false;

        if (!array_key_exists($index, $this->_aContentTabs)) {
            // initializing
            $this->_aContentTabs[$index] = new \OxidEsales\Eshop\Core\Field();

            $sOxid = $this->getId();
            $sViewName = getViewName('oxartextends', $this->getLanguage());

            $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $sDbValue = $oDb->getOne("select d3longdesc{$index} from {$sViewName} where oxid = " . $oDb->quote($sOxid));

            if ($sDbValue != false) {
                $this->_aContentTabs[$index]->setValue($sDbValue, \OxidEsales\Eshop\Core\Field::T_RAW);
            } elseif ($this->oxarticles__oxparentid->value) {
                if (!$this->isAdmin() || $this->_blLoadParentData) {
                    $oParent = $this->getParentArticle();
                    if ($oParent) {
                        $this->_aContentTabs[$index]->setValue($oParent->getContentTab($index)->getRawValue(), \OxidEsales\Eshop\Core\Field::T_RAW);
                    }
                }
            }
        }

        return $this->_aContentTabs[$index];
    }

    /**
     * inserts article long description to artextends table
     *
     * @return null
     */
    public function setArticleD3LongDesc($index = 2, $longDescription = "")
    {
        // setting current value
        $this->_aContentTabs[$index] = new \OxidEsales\Eshop\Core\Field($longDescription, \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->{'oxarticles__d3longdesc' . $index} = new \OxidEsales\Eshop\Core\Field($longDescription, \OxidEsales\Eshop\Core\Field::T_RAW);
    }

    public function save()
    {
        $blRet = parent::save();
        $this->_saveD3ArtLongDescs();
        return $blRet;
    }

    protected function _saveD3ArtLongDescs()
    {
// TODO: finde heraus für was das ist und ob wir d3longdesc da auch einfügen müssen
//        if (in_array("oxlongdesc", $this->_aSkipSaveFields)) {
//            return;
//        }

        for ($i = 2; $i < count($this->_aContentTabs) + 2; $i++)  {
            if ($this->_blEmployMultilanguage) {
                $sValue = $this->getContentTab($i)->getRawValue();
                if ($sValue !== null) {
                    $oArtExt = oxNew(\OxidEsales\Eshop\Core\Model\MultiLanguageModel::class);
                    $oArtExt->init('oxartextends');
                    $oArtExt->setLanguage((int) $this->getLanguage());
                    if (!$oArtExt->load($this->getId())) {
                        $oArtExt->setId($this->getId());
                    }
                    $oArtExt->{'oxartextends__d3longdesc' . $i} = new \OxidEsales\Eshop\Core\Field($sValue, \OxidEsales\Eshop\Core\Field::T_RAW);
                    $oArtExt->save();
                }
            } else {
                $oArtExt = oxNew(\OxidEsales\Eshop\Core\Model\MultiLanguageModel::class);
                $oArtExt->setEnableMultilang(false);
                $oArtExt->init('oxartextends');
                $aObjFields = $oArtExt->_getAllFields(true);
                if (!$oArtExt->load($this->getId())) {
                    $oArtExt->setId($this->getId());
                }

                foreach ($aObjFields as $sKey => $sValue) {
                    if (preg_match("/^d3longdesc$i(_(\d{1,2}))?$/", $sKey)) {
                        $sField = $this->_getFieldLongName($sKey);

                        if (isset($this->$sField)) {
                            $sLongDesc = null;
                            if ($this->$sField instanceof \OxidEsales\Eshop\Core\Field) {
                                $sLongDesc = $this->$sField->getRawValue();
                            } elseif (is_object($this->$sField)) {
                                $sLongDesc = $this->$sField->value;
                            }
                            if (isset($sLongDesc)) {
                                $sAEField = $oArtExt->_getFieldLongName($sKey);
                                $oArtExt->$sAEField = new \OxidEsales\Eshop\Core\Field($sLongDesc, \OxidEsales\Eshop\Core\Field::T_RAW);
                            }
                        }
                    }
                }
                $oArtExt->save();
            }
        }
    }
}
