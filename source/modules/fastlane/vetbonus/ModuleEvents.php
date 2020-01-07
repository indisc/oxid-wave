<?php
/**
 * @package       vetlinebonus
 * @category      module
 * @author        Fastlane
 * @link          http://www.fastlane.de
 * @licenses      GNU GENERAL PUBLIC LICENSE. More info can be found in LICENSE file.
 * @copyright (C) fastlane, 2003-2017
 */

namespace Fastlane\VetBonus;

use oxDb;

/**
 * Module on activate and on deactivate events.
 */

class ModuleEvents
{
    /**
     * Creates needed field.
     */

    public static function onActivate()
    {
        $sSql = "ALTER TABLE oxuser ADD EXTENDUSER_ADDITIONALCONTACTINFO VARCHAR(255);";
        $sSql .= "ALTER TABLE oxuser ADD HF24_VETREFNR INT(11);";
        $sSql .= "ALTER TABLE oxcounters ADD HF24_VETREFNR INT(11);";
        $sSql .= "ALTER TABLE oxorder ADD HF24_VETREFNR INT(11);";
        oxDb::getDb()->execute($sSql);
    }

    /**
     * Removes field on deactivation.
     */


    public static function onDeactivate()
    {
        $sSql = "ALTER TABLE oxuser DROP COLUMN EXTENDUSER_ADDITIONALCONTACTINFO;";
        $sSql .= "ALTER TABLE oxuser DROP COLUMN HF24_VETREFNR;";
        $sSql .= "ALTER TABLE oxcounters DROP COLUMN HF24_VETREFNR;";
        $sSql .= "ALTER TABLE oxorder DROP COLUMN HF24_VETREFNR;";
        oxDb::getDb()->execute($sSql);

    }

}

