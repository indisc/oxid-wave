<?php
/**
 * Credit-Plus TEST API
 *
 * @author Sven Keil
 */

if ( !function_exists('lib_autoLoader') ) {

	/**
	 * init auto load function
	 * @param $class
	 */
	function lib_autoLoader( $class ) {
		if ( !class_exists($class, false) ) {

			$sFileName = $class.'.php';

			$aDirNames = array(
				'CreditPlusHelper/',
				'CreditPlusObjects/',
				'Controller/'
			);
			foreach ( $aDirNames as $sDirName ) {
				$sDirName = dirname(__FILE__)."/".$sDirName;
				if ( is_file($sDirName.$sFileName) ) {
					/**
					 * include Class, File Name is checked in if statement above
					 */
					/** @noinspection PhpIncludeInspection */
					require_once($sDirName.$sFileName);
				}
			}
		}
	}

	/**
	 * register api auto load function
	 */
	spl_autoload_register('lib_autoLoader');
}
?>