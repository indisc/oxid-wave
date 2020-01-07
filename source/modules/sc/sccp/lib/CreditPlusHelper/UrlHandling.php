<?php

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusHelper;
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 17.12.2015
 * Time: 15:41
 */
class UrlHandling {

	/**
	 * base url
	 *
	 * @var string
	 */
	public $sTriggerUrl;

	function __construct() {
		//initialize
		$sPath = $this->getServerBaseUrl();
		$this->sTriggerUrl = $sPath;
	}

	/**
	 * Suppose, you are browsing in your localhost and using any url simulated system
	 * which channels everything through the index.php up top
	 *
	 * http://localhost/myproject/index.php?id=8
	 */
	private function getServerBaseUrl() {

		// $sDirName = /myproject
		$sDirname = substr(dirname($_SERVER['SCRIPT_FILENAME']),strlen($_SERVER['DOCUMENT_ROOT']));
		if ( substr($sDirname,0,1) !== '/' ) { $sDirname = '/'.$sDirname; }
		if ( substr($sDirname,-1,0) == '/' ) { $sDirname = substr($sDirname,0,-1); }


		// $sHostName = localhost
		$sHostName = $_SERVER['HTTP_HOST'];

		// $sProtocol = http://
		$sProtocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://'?'https://':'http://';

		// Don't trust http://, there is probably another way this is set to SSL...
		// Usually if Server is behind SSL Proxy as with SysEleven or Profihost
		if ( $sProtocol == 'http://' ) {
			// Alternative for setting HTTPS
			if ( $_SERVER['HTTPS'] == 'on' ) {
				$sProtocol = 'https://';
			}
			//additional special handling for profihost customers - copied from OXIDs oxconfig.php
			if (isset($_SERVER['HTTP_X_FORWARDED_SERVER']) &&
				(strpos($_SERVER['HTTP_X_FORWARDED_SERVER'], 'ssl') !== false ||
					strpos($_SERVER['HTTP_X_FORWARDED_SERVER'], 'secure-online-shopping.de') !== false)
			) {
				$sProtocol = 'https://';
			}
		}

		// Empty string if default ports are used
		$sPort = '';
		if ( ($_SERVER['SERVER_PORT'].'' === '80') && ($sProtocol === 'http://') ) {
			// Default port for HTTP
		} elseif ( ($_SERVER['SERVER_PORT'].'' === '443') && ($sProtocol === 'https://') ) {
			// Default port for HTTPS
		} else {
			// If other port is used, should never happen
			$sPort .= ':'.$_SERVER['SERVER_PORT'];
		}
		// $sPort can be inserted after $sHostName, if required and tested.
		// return: http://localhost/myproject/
		return $sProtocol.$sHostName.$sDirname."/";
	}

	/**
	 * get the base url
	 *
	 * @return string
	 */
	public function getTriggerUrl() {
		return $this->sTriggerUrl;
	}
}
