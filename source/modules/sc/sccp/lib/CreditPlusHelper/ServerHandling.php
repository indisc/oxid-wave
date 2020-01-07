<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusHelper;

/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 17.12.2015
 * Time: 15:41
 */

class ServerHandling {

    /**
     * End of Main Shop URL
     * For Validation
     *
     * @required true
     * @var string
     */
    private $sMainUrlEnd = "&data=";

    /**
     * End of Main Shop URL
     * For Validation
     * Todo: über Config holen
     *
     * @required true
     * @var string
     */
    private $sMainUrlBase = "index.php?cl=order";

    /**
     * base url
     *
     * @var string
     */
    private $sBaseUrl;

    function __construct() {
        //initialize
        $aPu = parse_url($_SERVER['REQUEST_URI']);
        $this->sBaseUrl = $aPu["scheme"] . "://" . $aPu["host"];
    }

    /**
     * get the base url
     *
     * @return string
     */
    public function getBaseUrl(){
        return $this->sBaseUrl;
    }

    /**
     * get the base url
     *
     * @return string
     */
    public function getShopIntegrationBaseUrl(){
        return $this->sBaseUrl . $this->sMainUrlBase . $this->sMainUrlEnd;
    }
}
