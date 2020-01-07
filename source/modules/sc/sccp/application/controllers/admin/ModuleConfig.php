<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 09.03.16
 * Time: 14:22
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;



class ModuleConfig extends ModuleConfig_parent {
	public function saveConfVars() {
		parent::saveConfVars();
		$oConfig = $this->getConfig();
		$oRequest = OxidEsales\Eshop\Core\Registry::getRequest();
		$sShopId = $oConfig->getShopId();
		$sModuleId = $this->_getModuleForConfigVars();
		if ( $sModuleId == 'module:sccp' ) {
			foreach ($this->_aConfParams as $sType => $sParam) {
				$aConfVars = $oRequest->getRequestParameter($sParam);
				if (is_array($aConfVars)) {
					foreach ($aConfVars as $sName => $sValue) {
						if ( $sName == 'sMinRate' ) {
							$sValueNew = str_replace(',','.',$sValue);
							$fValue = floatval($sValueNew);
							if ( ($fValue < 25) || ($sValueNew != $sValue) ) {
								if ($fValue < 25) { $fValue = 25; }
								$sDbType = $this->_getDbConfigTypeName($sType);
								$oConfig->saveShopConfVar(
									$sDbType,
									$sName,
									$this->_serializeConfVar($sDbType, $sName, number_format($fValue,2,'.','')),
									$sShopId,
									$sModuleId
								);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Convert metadata type to DB type.
	 *
	 * @param string $sType Metadata type.
	 *
	 * @return string
	 */
	private function _getDbConfigTypeName($sType)
	{
		$sDbType = $sType;
		if ($sType === 'password') {
			$sDbType = 'str';
		}

		return $sDbType;
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\ModuleConfig::class,'sccp_module_config');

if ( false ) {
	class ModuleConfig_parent extends \OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration {

	}
}
