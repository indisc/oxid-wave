<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2015
 * @version   OXID eShop CE
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;

/**
 * Admin article main order manager.
 * Performs collection and updatind (on user submit) main item information.
 * Admin Menu: Orders -> CreditPlus Orders -> Main.
 */
//class sccp_cporder_main extends oxAdminView {
class CpOrderMain extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController {

	protected $_sThisTemplate = "sccp_cporder_main.tpl";

}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpOrderMain::class,'sccp_cporder_main');
