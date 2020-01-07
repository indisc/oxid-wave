<?php
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 16.12.2015
 * Time: 11:33
 */

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusHelper;
//namespace CreditPlusHelper;
//hat mit Namespaces nicht funtkioniert...

class Validation {

	function __construct() {
		//initialize
	}

	/**
	 * Check if the var is Not Null
	 *
	 * @param mixed $oVar
	 * @return boolean
	 */
	public function isNotNull( $oVar ) {
		if ( isset($oVar) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the var is Not Null and is an Integer
	 *
	 * @param integer $iVar
	 * @return boolean
	 */
	public function isInteger( $iVar ) {
		return is_int($iVar);
	}

	/**
	 * Check if all items in the array are not null
	 * Breaks if one item is null
	 *
	 * @param mixed[] $aArray
	 * @return boolean
	 */
	public function areParamsNotNull( $aArray ) {

		if ( is_array($aArray) ) {
			foreach ( $aArray as $oVar ) {
				if ( !$this->isNotNull($oVar) ) {
					return false;
				}
			}
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Check if all items in the array are null
	 * Breaks if one item is not null
	 *
	 * @param mixed[] $aArray
	 * @return boolean
	 */
	public function areAllParamsNull( $aArray ) {

		if ( is_array($aArray) ) {
			foreach ( $aArray as $oVar ) {
				if ( $this->isNotNull($oVar) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Check if the string is not longer than $iMaxLength letters
	 *
	 * @param string $sVar
	 * @param int $iMaxLength
	 * @return boolean
	 */
	public function isMaxLengthValid( $sVar, $iMaxLength = 27 ) {
		if ( isset($sVar) ) {
			if ( strlen($sVar) > $iMaxLength ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if the array-parameters are Not Longer Than
	 *
	 * @param string[] $aArray
	 * @param int $iMaxLength
	 * @return boolean
	 */
	public function areMaxLengthsValid( $aArray, $iMaxLength = 27 ) {

		if ( is_array($aArray) ) {
			foreach ( $aArray as $sVar ) {
				if ( !$this->isMaxLengthValid($sVar, $iMaxLength) ) {
					return false;
				}
			}
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Check if the string has min Length
	 *
	 * @param string $sVar
	 * @param int $iMinLength
	 * @return boolean
	 */
	public function isMinLengthValid( $sVar, $iMinLength = 1 ) {
		if ( isset($sVar) ) {
			if ( strlen($sVar) < $iMinLength ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Check if the array-parameters has min Length
	 *
	 * @param string[] $aArray
	 * @param int $iMinLength
	 * @return boolean
	 */
	public function areMinLengthsValid( $aArray, $iMinLength = 1 ) {

		if ( is_array($aArray) ) {
			foreach ( $aArray as $sVar ) {
				if ( !$this->isMinLengthValid($sVar, $iMinLength) ) {
					return false;
				}
			}
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Check if the object is in the array
	 * IF $var is NULL than check is FALSE
	 *
	 * @param mixed $oVar
	 * @param mixed[] $aArray
	 * @return boolean
	 */
	public function isParamInArray( $oVar, $aArray = array() ) {

		if ( isset($oVar) ) {
			return in_array($oVar, $aArray);
		}

		return false;
	}

	/**
	 * Check if a String Starts with a String
	 *
	 * @param string $sHaystack == string
	 * @param string $sNeedle == substring
	 * @return boolean
	 */
	public function startsWith( $sHaystack, $sNeedle ) {
		$iLength = strlen($sNeedle);
		return (substr($sHaystack, 0, $iLength) === $sNeedle);
	}

	/**
	 * Check if a String Ends with a String
	 *
	 * @param string $sHaystack == string
	 * @param string $sNeedle == substring
	 * @return boolean
	 */
	public function endsWith( $sHaystack, $sNeedle ) {
		$iLength = strlen($sNeedle);
		if ( $iLength == 0 ) {
			return true;
		}

		return (substr($sHaystack, -$iLength) === $sNeedle);
	}
}
