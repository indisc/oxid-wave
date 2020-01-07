<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 23.02.16
 * Time: 13:02
 */

namespace Sinkacom\CreditPlusModule\Model;

//class sccp_prodgroup extends oxBase {
class Prodgroup extends \OxidEsales\Eshop\Core\Model\BaseModel {
	protected $_sClassName = 'sccp_prodgroup';

	protected $_sCoreTable = 'sccp_prodgroup';

	public function countAssignedProducts() {
		$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_NUM);
		$sOxid = $oDB->quote($this->getId());
		/** @var mysql_driver_ResultSet $oRes */
		$oRes = $oDB->select("SELECT COUNT(*) assignedprods FROM sccp_prodgroup_article pga WHERE pga.sccp_prodgroup_id = $sOxid");
		if ( $oRes && $oRes->fields[0] ) {
			return $oRes->fields[0];
		}
		return 0;
	}
}
class_alias(\Sinkacom\CreditPlusModule\Model\Prodgroup::class,'sccp_prodgroup');
