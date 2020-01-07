<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 23.02.16
 * Time: 13:02
 */
namespace Sinkacom\CreditPlusModule\Model;
//class sccp_offered_option extends oxBase {
class OfferedOption extends \OxidEsales\Eshop\Core\Model\BaseModel {
	protected $_sClassName = 'sccp_offered_option';

	protected $_sCoreTable = 'sccp_offered_option';

	public function save() {
		$sPossiblyGermanValue = $this->sccp_offered_option__sccp_interest->value;
		if ( strpos($sPossiblyGermanValue,',') ) {
			$sDefinitelyGermanValue = str_replace(array('.',','),array('','.'),$sPossiblyGermanValue);
			$this->sccp_offered_option__sccp_interest->setValue(floatval($sDefinitelyGermanValue));
		}
		return parent::save();
	}

	public function countAssignedProductGroups() {
		$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_NUM);
		$sOxid = $oDB->quote($this->getId());
		/** @var mysql_driver_ResultSet $oRes */
		$oRes = $oDB->execute("SELECT COUNT(*) assignedprodgroups FROM sccp_offered_option_prodgroup soop WHERE soop.sccp_offered_option_id = $sOxid");
		if ( $oRes && $oRes->fields[0] ) {
			return $oRes->fields[0];
		}
		return 0;
	}
}
class_alias(\Sinkacom\CreditPlusModule\Model\OfferedOption::class,'sccp_offered_option');
