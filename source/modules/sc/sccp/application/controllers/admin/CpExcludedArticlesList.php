<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 08.04.16
 * Time: 15:27
 */

namespace Sinkacom\CreditPlusModule\Controller\Admin;

// class sccp_cpexcluded_articles_list extends oxAdminList {
class CpExcludedArticlesList extends \OxidEsales\Eshop\Application\Controller\Admin\AdminListController {
	/**
	 * Current class template name.
	 *
	 * @var string
	 */
	protected $_sThisTemplate = 'sccp_cpexcluded_articles_list.tpl';
}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpExcludedArticlesList::class,'sccp_cpexcluded_articles_list');
