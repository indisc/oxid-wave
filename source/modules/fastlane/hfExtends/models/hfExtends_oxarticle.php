<?php

class hfExtends_oxarticle extends hfExtends_oxarticle_parent
{

    /**
     *  loads article parameters and apsses them to smarty engine, return
     *  name of template file "article_main.tpl
     *
     * @return string
     */

    public function render()
    {
        parent::render();

        $this->getConfig()->setConfigParam('bl_perfLoadPrice', true);

        $oArticle = $this->createArticle();
        $oArticle->enablePriceLoad();

        $this->_aViewData['edit'] = $oArticle;

        $sOxId = $this->getEditObjectId();
        $sVoxId = $this->getConfig()->getRequestParameter("voxid");
        $sOxParentId = $this->getConfig()->getRequestParameter("oxparentid");

        // new variant ?
        if (isset($sVoxId) && $sVoxId == "-1" && isset($sOxParentId) && $sOxParentId && $sOxParentId != "-1") {
            $oParentArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oParentArticle->load($sOxParentId);
            $this->_aViewData["parentarticle"] = $oParentArticle;
            $this->_aViewData["oxparentid"] = $sOxParentId;

            $this->_aViewData["oxid"] = $sOxId = "-1";
        }

        if ($sOxId && $sOxId != "-1") {
            // load object
            $oArticle = $this->updateArticle($oArticle, $sOxId);

            // load object in other languages
            $oOtherLang = $oArticle->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oArticle->loadInLang(key($oOtherLang), $sOxId);
            }

            // variant handling
            if ($oArticle->oxarticles__oxparentid->value) {
                $oParentArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                $oParentArticle->load($oArticle->oxarticles__oxparentid->value);
                $this->_aViewData["parentarticle"] = $oParentArticle;
                $this->_aViewData["oxparentid"] = $oArticle->oxarticles__oxparentid->value;
                $this->_aViewData["issubvariant"] = 1;
            }

            // #381A
            $this->_formJumpList($oArticle, $oParentArticle);

            //hook for modules
            $oArticle = $this->customizeArticleInformation($oArticle);

            $aLang = array_diff(\OxidEsales\Eshop\Core\Registry::getLang()->getLanguageNames(), $oOtherLang);
            if (count($aLang)) {
                $this->_aViewData["posslang"] = $aLang;
            }

            foreach ($oOtherLang as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }
        }

        $this->_aViewData["editor"] = $this->_generateTextEditor(
            "100%",
            300,
            $oArticle,
            "oxarticles__oxlongdesc",
            "details.tpl.css"
        );


        $this->_aViewData["editorct2"] = $this->generateTextEditor(
            "100%",
            300,
            $oArticle,
            "oxarticles__d3longdesc2",
            "details.tpl.css"
        );
        $this->_aViewData["editorct3"] = $this->generateTextEditor(
            "100%",
            300,
            $oArticle,
            "oxarticles__d3longdesc3",
            "details.tpl.css"
        );
        $this->_aViewData["blUseTimeCheck"] = $this->getConfig()->getConfigParam('blUseTimeCheck');

        return "article_main.tpl";

    }

    protected function _getEditValue($oObject, $sField)
    {
        $sEditObjectValue = '';
        if ($oObject) {
            if (substr($sField,12,10) == 'd3longdesc') {
                $oDescField = $oObject->getContentTab(substr($sField,-1));
            } else {
                $oDescField = $oObject->getLongDescription();
            }
            //$oDescField = $oObject->getLongDescription();
            $sEditObjectValue = $this->_processEditValue($oDescField->getRawValue());
            $oDescField = new \OxidEsales\Eshop\Core\Field($sEditObjectValue, \OxidEsales\Eshop\Core\Field::T_RAW);
        }

        return $sEditObjectValue;
    }

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
