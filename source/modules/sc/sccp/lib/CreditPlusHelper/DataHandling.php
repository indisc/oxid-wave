<?php
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 16.12.2015
 * Time: 14:46
 */

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusHelper;

class DataHandling {

    function __construct() {
        //initialize
    }

    /**
     * Get all Vars as array
     *
     * @param object $oClass
     * @return array $apiData
     */
    public function getClassArray($oClass){

        $oReflect = new \ReflectionClass($oClass);
        $aClassProps   = $oReflect->getProperties(\ReflectionProperty::IS_PUBLIC);

        $aApiData = array();

        foreach ($aClassProps as $oClassProp) {
            $aApiData[$oClassProp->getName()] = $oClassProp->getValue($oClass);
        }

        return $aApiData;
}

}
