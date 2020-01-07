<?php
/**
 * Created by PhpStorm.
 * User: sven.keil
 * Date: 16.12.2015
 * Time: 11:57
 */

namespace Sinkacom\CreditPlusModule\Lib\CreditPlusObjects;
use Sinkacom\CreditPlusModule\Lib\CreditPlusHelper\Validation;

//namespace CreditPlusObjects;
//hat mit Namespaces nicht funtkioniert...

class WebshopCustomerData {

	/**
	 * Mr = 1, Mrs|Miss|Ms = 2
	 *
	 * @required no
	 * @inarray 1,2
	 * @var int
	 */
	private $iSalutation;

	/**
	 * Customer First Name
	 *
	 * @required true
	 * @maxlength 27
	 * @var string
	 */
	private $sFirstName;

	/**
	 * Customer Last Name
	 *
	 * @required true
	 * @maxlength 27
	 * @var string
	 */
	private $sLastName;

	/**
	 * Customer street
	 *
	 * @required true
	 * @maxlength 27
	 * @var string
	 */
	private $sStreet;

	/**
	 * Customer Postal Code
	 *
	 * @required true
	 * @maxlength 5
	 * @var string
	 */
	private $sPostalCode;

	/**
	 * Customer City
	 *
	 * @required true
	 * @maxlength 27
	 * @var string
	 */
	private $sCity;

	/**
	 * Customer City
	 *
	 * @required false
	 * @var string
	 */
	private $sEmail;

	/**
	 * Konstruktor
	 *
	 * @param array $aInput
	 * @throws \Exception If input is no ARRAY
	 */
	function __construct( $aInput ) {
		if ( is_array($aInput) ) {
			// Initialize array with input-data
			if ( isset($aInput["salutation"]) ) {
				$this->iSalutation = intval($aInput["salutation"]);
			}
			if ( isset($aInput["firstName"]) ) {
				$this->sFirstName = $aInput["firstName"].'';
			}
			if ( isset($aInput["lastName"]) ) {
				$this->sLastName = $aInput["lastName"].'';
			}
			if ( isset($aInput["street"]) ) {
				$this->sStreet = $aInput["street"].'';
			}
			if ( isset($aInput["postalCode"]) ) {
				$this->sPostalCode = $aInput["postalCode"].'';
			}
			if ( isset($aInput["city"]) ) {
				$this->sCity = $aInput["city"].'';
			}
			if ( isset($aInput["email"]) ) {
				$this->sEmail = $aInput["email"].'';
			}
		} else {
			throw new \Exception('Wrong input type');
		}
	}


	/**
	 * Check if This Object has valid Data
	 *
	 */
	public function isValid() {

		$validation = new Validation();

		//Check Required Vars
		$arrayNotNull = array(
			$this->sFirstName,
			$this->sLastName,
			$this->sStreet,
			$this->sPostalCode,
			$this->sCity
		);

		if ( !$validation->areParamsNotNull($arrayNotNull) ) {
			return false;
		}

		//Check Fields with Max Length = 27
		$arrayMax27 = array(
			$this->sFirstName,
			$this->sLastName,
			$this->sStreet,
			$this->sCity
		);

		if ( !$validation->areMaxLengthsValid($arrayMax27, 27) ) {
			$aDummyData = $this->getFallbackArray();
			if ( !$validation->isMaxLengthValid($this->sFirstName, 27) ) {
				$this->sFirstName = substr($this->sFirstName, 0, 27);
			}
			if ( !$validation->isMaxLengthValid($this->sLastName, 27) ) {
				$this->sLastName = substr($this->sLastName, 0, 27);
			}
			if ( !$validation->isMaxLengthValid($this->sStreet, 27) ) {
				//$this->sStreet = substr($this->sStreet, 0, 27);
			}
			if ( !$validation->isMaxLengthValid($this->sCity, 27) ) {
				$this->sCity = substr($this->sCity, 0, 27);
			}
			// Pass it through reduced to the max.
			//return false;
		}

		//Validate salutation
		if ( $validation->isNotNull($this->iSalutation) ) {
			if ( $validation->isParamInArray($this->iSalutation, array(
					1,
					2
				)) == false
			) {
				return false;
			}
		}

		//Validate postalCode
		if ( $validation->isNotNull($this->sPostalCode) ) {
			$aDummyData = $this->getFallbackArray();
			if ( $validation->isMaxLengthValid($this->sPostalCode, 5) == false ) {
				$this->sPostalCode = substr($this->sPostalCode, 0, 5);
				// return false;
			}
		}

		return true;

	}

	/**
	 * returns the Data as Array for API
	 * --> empty array if not valid
	 *
	 */
	public function getApiArray() {

		if ( $this->isValid() ) {
			$aApiData = array(
				"salutation" => $this->iSalutation,
				"firstName" => $this->sFirstName,
				"lastName" => $this->sLastName,
				"street" => $this->sStreet,
				"postalCode" => $this->sPostalCode,
				"city" => $this->sCity,
				"email" => $this->sEmail
			);
		} else {
			$aApiData = $this->getDummyArray();
		}

		return $aApiData;
	}


	/**
	 *
	 * returns a Dummy-Data Array for API Requests
	 *
	 * @return array
	 */
	public function getDummyArray() {
		$aDummyData = array(
			'salutation' => 1,
			'firstName' => 'Karl',
			'lastName' => 'Kredit',
			'street' => 'Miesestraße 32',
			'postalCode' => '73512',
			'city' => 'Rückstand'
		);

		return $aDummyData;
	}

	/**
	 *
	 * returns a Dummy-Data Array for API Requests
	 *
	 * @return array
	 */
	public function getFallbackArray() {
		$aFallbackData = array(
			'salutation' => 1,
			'firstName' => ' ',
			'lastName' => ' ',
			'street' => ' ',
			'postalCode' => ' ',
			'city' => ' '
		);

		return $aFallbackData;
	}

	/**
	 * set Salutation
	 *
	 * @param int $iSalutation
	 */
	public function setSalutation( $iSalutation ) {
		$this->iSalutation = $iSalutation;
	}

	/**
	 * set FirstName
	 *
	 * @param string $sFirstName
	 */
	public function setFirstName( $sFirstName ) {
		$this->sFirstName = $sFirstName;
	}

	/**
	 * set LastName
	 *
	 * @param string $sLastName
	 */
	public function setLastName( $sLastName ) {
		$this->sLastName = $sLastName;
	}

	/**
	 * set Street
	 *
	 * @param string $sStreet
	 */
	public function setStreet( $sStreet ) {
		$this->sStreet = $sStreet;
	}

	/**
	 * set PostalCode
	 *
	 * @param string $sPostalCode
	 */
	public function setPostalCode( $sPostalCode ) {
		$this->sPostalCode = $sPostalCode;
	}

	/**
	 * set City
	 *
	 * @param string $sCity
	 */
	public function setCity( $sCity ) {
		$this->sCity = $sCity;
	}

	/**
	 * set Email
	 *
	 * @param string $sEmail
	 */
	public function setEmail( $sEmail ) {
		$this->sEmail = $sEmail;
	}
}
