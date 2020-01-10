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
use OxidEsales\Eshop\Core\DatabaseProvider;

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

        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);


        // check if tables exist, for later insertion
        $hasUsers = $db->select("SHOW COLUMNS FROM `oxuser` WHERE Field = 'HF24_VETREFNR'");
        $hasUsers = (boolean)$hasUsers->count();

        $hasCounters = $db->select("SHOW COLUMNS FROM `oxcounters` WHERE Field = 'HF24_VETREFNR'");
        $hasCounters = (boolean)$hasCounters->count();

        $hasOrders = $db->select("SHOW COLUMNS FROM `oxorder` WHERE Field = 'HF24_VETREFNR'");
        $hasOrders = (boolean)$hasOrders->count();

        if ( $hasUsers == false || $hasCounters == false || $hasOrders == false){
            $sSql .= "ALTER TABLE oxuser ADD HF24_VETREFNR INT(11);";
            $sSql .= "ALTER TABLE oxcounters ADD HF24_VETREFNR INT(11);";
            $sSql .= "ALTER TABLE oxorder ADD HF24_VETREFNR INT(11);";
            $db->execute($sSql);
        }


        /*
        $sSql  = "ALTER TABLE oxuser ADD HF24_VETREFNR INT(11);";
        $sSql .= "ALTER TABLE oxcounters ADD HF24_VETREFNR INT(11);";
        $sSql .= "ALTER TABLE oxorder ADD HF24_VETREFNR INT(11);";
        */
        /*
                $oRsUser = $oDb->select($sCheckUser);
                $oRsCounters = $oDb->select($sCheckCounters);
                $oRsOrder = $oDb->select($sCheckOrder);
        */
        /*
                oxDb::getDb()->execute($sSql);
        */


    }

    /**
     * Removes field on deactivation.
     */


    public static function onDeactivate()
    {


        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        // check if tables exist, for later insertion
        $hasUsers = $db->select("SHOW COLUMNS FROM `oxuser` WHERE Field = 'HF24_VETREFNR'");
        $hasUsers = (boolean)$hasUsers->count();
        var_dump($hasUsers);



        $hasCounters = $db->select("SHOW COLUMNS FROM `oxcounters` WHERE Field = 'HF24_VETREFNR'");
        $hasCounters = (boolean)$hasCounters->count();
        var_dump($hasCounters);

        $hasOrders = $db->select("SHOW COLUMNS FROM `oxorder` WHERE Field = 'HF24_VETREFNR'");
        $hasOrders = (boolean)$hasOrders->count();
        var_dump($hasOrders);

        /*

        $sSql .= "ALTER TABLE oxuser DROP COLUMN HF24_VETREFNR;";
        $sSql .= "ALTER TABLE oxcounters DROP COLUMN HF24_VETREFNR;";
        $sSql .= "ALTER TABLE oxorder DROP COLUMN HF24_VETREFNR;";
        $db->execute($sSql);
        */
    }

}

