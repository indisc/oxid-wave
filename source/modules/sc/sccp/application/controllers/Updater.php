<?php

namespace Sinkacom\CreditPlusModule\Controller;

/**
 * Created by PhpStorm.
 * @author Mihovil.Bubnjar
 * @date 06.07.2017
 * @time 17:53
 */
class Updater {
	/**
	 * @param array $aModuleData Module Config
	 * @param string $sShopVersion Shop version as determined by our plugin metadata.php (fusing EE and CE to one version number)
	 * @param \OxidEsales\Eshop\Core\Config $oConfig Shop Configuration object
	 */
	public static function updateModuleData( $aModuleData, $sShopVersion, $oConfig ) {
		if ( version_compare($sShopVersion, '6.0.0', '>=') ) {
			return;
		}
		$sShopId = $oConfig->getShopId();
		$sModuleId = 'sccp';
		if ( class_exists('\OxidEsales\Eshop\Core\DatabaseProvider') ) {
			$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
		} else {
			$oDB = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
		}
		if ( version_compare($sShopVersion,'4.7.0', '>=') && version_compare($sShopVersion,'4.8.0','<') ) {
			/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface $oRes */
			$oRes = $oDB->select("SELECT oxid, oxfile FROM oxtplblocks WHERE oxmodule = '$sModuleId' AND oxblockname = 'email_html_order_custpaymentinfo'");
			if ( $oRes && $oRes->count() ) {
				while ( !$oRes->EOF ) {
					$sOXID = $oRes->fields['oxid'];
					$sFile = str_replace('email_html_order_custpaymentinfo','email_html_order_cust_paymentinfo',$oRes->fields['oxfile']);
					$sBlockName = 'email_html_order_cust_paymentinfo';
					$oDB->execute("UPDATE oxtplblocks SET oxfile='$sFile', oxblockname = '$sBlockName' WHERE oxid = '$sOXID'");
					//$oRes->MoveNext();
					$oRes->fetchRow();
				}
			}
		}
		self::updateBlocks($aModuleData, $oDB, $sShopId, $sModuleId);
		self::updateSettings($aModuleData, $oDB, $sShopId, $sModuleId);
	}

	/**
	 * @param array $aModuleData
	 * @param \OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface|oxLegacyDb $oDB
	 * @param string $sShopId
	 * @param string $sModuleId
	 */
	protected static function updateBlocks( $aModuleData, $oDB, $sShopId, $sModuleId ) {
		$aDeleteBlocks = array();
		$aInstalledBlocks = array();
		/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface $oResInstalledBlocks */
		$oResInstalledBlocks = $oDB->select("SELECT oxid, oxtemplate, oxpos, oxblockname, oxfile FROM oxtplblocks WHERE oxmodule = '$sModuleId' AND oxshopid = '$sShopId'");
		if ( $oResInstalledBlocks && !$oResInstalledBlocks->EOF ) {
			while ( !$oResInstalledBlocks->EOF ) {
				$bInstalled = false;
				foreach ( $aModuleData['blocks'] as $aBlock ) {
					if ( self::compareBlock($oResInstalledBlocks->fields,$aBlock) ) {
						$bInstalled = true;
					}
				}
				if ( $bInstalled == false ) {
					$aDeleteBlocks[] = $oResInstalledBlocks->fields;
				} else {
					$aInstalledBlocks[] = $oResInstalledBlocks->fields;
				}
				//$oResInstalledBlocks->MoveNext();
				$oResInstalledBlocks->fetchRow();
			}
		}
		// Delete Blocks which should not be installed
		if ( $aDeleteBlocks ) {
			foreach ( $aDeleteBlocks as $aDeleteBlock ) {
				$sOXID = $aDeleteBlock['oxid'];
				$oDB->execute("DELETE FROM oxtplblocks WHERE oxid = '$sOXID'");
			}
		}
		// Install Blocks, which are missing
		if ( $aInstalledBlocks ) {
			foreach ( $aModuleData['blocks'] as $aBlock ) {
				$bInstalled = false;
				foreach ( $aInstalledBlocks as $aInstalledBlock ) {
					if ( self::compareBlock($aInstalledBlock, $aBlock) ) {
						$bInstalled = true;
					}
				}
				if ( $bInstalled == false ) {
					self::installTemplate($aBlock, $sShopId, $oDB, $sModuleId);
				}
			}
		}
	}

	protected static function compareBlock( $aResultBlock, $aModuleBlock ) {
		if ( $aResultBlock['oxtemplate'] !== $aModuleBlock['template'] ) {
			return false;
		}
		if ( isset($aModuleBlock['position']) && ($aResultBlock['oxpos'] !== $aModuleBlock['position']) ) {
			return false;
		}
		if ($aResultBlock['oxblockname'] !== $aModuleBlock['block'] ) {
			return false;
		}
		if ( $aResultBlock['oxfile'] !== $aModuleBlock['file'] ) {
			return false;
		}
		return true;
	}

	/**
	 * @param array $aBlock
	 * @param string $sShopId
	 * @param DatabaseInterface|\OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface|oxLegacyDb $oDB
	 * @param string $sModuleId
	 */
	protected static function installTemplate( $aBlock, $sShopId, $oDB, $sModuleId ) {
		if ( class_exists('\OxidEsales\Eshop\Core\UtilsObject') ) {
			$sOxId = \OxidEsales\Eshop\Core\UtilsObject::getInstance()->generateUId(); 
		} else {
			$sOxId = oxUtilsObject::getInstance()->generateUId();
		}

		$sTemplate = $aBlock["template"];
		$iPosition = $aBlock["position"]?$aBlock["position"]:1;
		$sBlock    = $aBlock["block"];
		$sFile     = $aBlock["file"];

		$sSql = "INSERT INTO `oxtplblocks` (`OXID`, `OXACTIVE`, `OXSHOPID`, `OXTEMPLATE`, `OXBLOCKNAME`, `OXPOS`, `OXFILE`, `OXMODULE`)
	                         VALUES ('{$sOxId}', 1, '{$sShopId}', ".$oDB->quote($sTemplate).", ".$oDB->quote($sBlock).", ".$oDB->quote($iPosition).", ".$oDB->quote($sFile).", '{$sModuleId}')";

		$oDB->execute( $sSql );
	}

	/**
	 * @param array[] $aModuleData
	 * @param DatabaseInterface|\OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface|oxLegacyDb $oDB
	 * @param string $sShopId
	 * @param string $sModuleId
	 */
	protected static function updateSettings( $aModuleData, $oDB, $sShopId, $sModuleId ) {
		$aSettings = $aModuleData['settings'];
		if ( class_exists('\OxidEsales\Eshop\Core\UtilsObject') ) {
			$oUtilsObject = \OxidEsales\Eshop\Core\UtilsObject::getInstance();
		} else {
			$oUtilsObject = oxUtilsObject::getInstance();
		}

		$oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

		$sConfigKey = $oConfig->getConfigParam('sConfigKey');
		foreach ( $aSettings as $sKey => $aSetting ) {
			$sKey = $aSetting['name'];
			/** @var \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface|mysqli_driver_ResultSet $oRes */
			$oRes = $oDB->select('SELECT oxid, oxcfgvarname, oxgrouping, oxvarconstraint FROM oxconfigdisplay WHERE oxcfgmodule = ? AND oxcfgvarname = ?', array(
				'module:'.$sModuleId,
				$sKey
			));
			if ( $oRes instanceof \OxidEsales\Eshop\Core\Database\Adapter\ResultSetInterface ) {
				$iCount = $oRes->count();
			} else {
				$iCount = $oRes->RecordCount();
			}
			if ( $oRes && $iCount ) {
				while ( $aRow = $oRes->fields ) {
					$sUpdateKeys = '';
					$aUpdateValues = array();
					if ( $aRow['oxgrouping'] !== $aSetting['group'] ) {
						$sUpdateKeys .= ', oxgrouping = ?';
						$aUpdateValues[] = $aSetting['group'];
					}
					if ( $aRow['oxvarconstraint'] !== $aSetting['constraints'] ) {
						$sUpdateKeys .= ', oxvarconstraint = ?';
						$aUpdateValues[] = $aSetting['constraints'];
					}
					if ( $sUpdateKeys != '' ) {
						$sUpdateKeys = substr($sUpdateKeys,2);
						$aUpdateValues[] = $aRow['oxid'];
						$sUpdateString = "UPDATE oxconfigdisplay SET $sUpdateKeys WHERE oxid = ?";
						$oDB->execute($sUpdateString, $aUpdateValues);
					}
					//$oRes->MoveNext();
					$oRes->fetchRow();
				}
			} else {
				$oDB->execute("INSERT INTO oxconfigdisplay (oxid,oxcfgmodule,oxcfgvarname,oxgrouping,oxvarconstraint,oxpos,oxtimestamp) VALUES (?,?,?,?,?,?,?)",array(
					$oUtilsObject->generateUId(),
					'module:'.$sModuleId,
					$aSetting['name'],
					$aSetting['group'],
					(isset($aSetting['constraints'])?:''),
					1,
					date('Y-m-d H:i:s')
				));
				$oDB->execute('INSERT INTO oxconfig (oxid, oxshopid, oxmodule, oxvarname, oxvartype, oxvarvalue, oxtimestamp) VALUES (?,?,?,?,?,ENCODE(?,?),?)', array(
					$oUtilsObject->generateUId(),
					$sShopId,
					'module:'.$sModuleId,
					$aSetting['name'],
					$aSetting['type'],
					$aSetting['value'],
					$sConfigKey,
					date('Y-m-d H:i:s')
				));
			}
		}
	}
}

class_alias(\Sinkacom\CreditPlusModule\Controller\Updater::class,'sccp_updater');
