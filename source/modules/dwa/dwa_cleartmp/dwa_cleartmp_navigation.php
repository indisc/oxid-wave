<?php

class dwa_cleartmp_navigation extends dwa_cleartmp_navigation_parent
{
    // löscht alle Dateien außer .htaccess + .htpasswd
    public function dwaCleartmp()
    {
        $this->_dwaNotDelArray = array('.htaccess', '.htpasswd');
        $this->_tmpFolder = oxRegistry::getConfig()->getConfigParam('sCompileDir');
        $this->_delFiles($this->_tmpFolder);
        echo oxRegistry::getLang()->translateString('DWA_CLEARTMP_SUCCESSFUL', 0, 1);
        exit;
    }
    
    /**
    * rekursive Funktion
    * 
    * @param array $aFiles
    */
    protected function _delFiles($sDir)
    {
        foreach(scandir($sDir) as $sFile) { 
            if (is_dir($sDir . '/' . $sFile) && $sFile != '.' && $sFile != '..') {
                $this->_delFiles($sDir . '/' . $sFile);    // Unterverzeichnisse
            }
            if (!is_dir($sDir . $sFile) && $sFile != '.' && $sFile != '..' && !in_array($sFile, $this->_dwaNotDelArray)) {
                unlink($sDir . '/' . $sFile);
            } 
        }
    }
}
