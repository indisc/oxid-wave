<?php

/**
 * Created by PhpStorm.
 * User: sinkacom
 * Date: 08.04.16
 * Time: 15:26
 */
//class sccp_cpexcluded_articles extends oxAdminView {
namespace Sinkacom\CreditPlusModule\Controller\Admin;

class CpExcludedArticles extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController {

	/**
	 * Current class template name.
	 *
	 * @var string
	 */
	protected $_sThisTemplate = 'sccp_cpexcluded_articles_main.tpl';

}
class_alias(\Sinkacom\CreditPlusModule\Controller\Admin\CpExcludedArticles::class,'sccp_cpexcluded_articles');
