<?php
 /**
  * Modul:       DWA_xyz
  * @version:    $Id: metadata.php 12042 2018-12-18 09:02:49Z anja $
  *
  * Diese Datei darf nicht für andere Projekte oder andere Domains als vereinbart, verwendet oder veräußert werden.
  *
  * @link http://www.web-grips.de/
  * @copyright WEB-Grips
  * @author WEB-Grips <info@web-grips.de>
  */
/**
 * Module information
 */


$aModule = array(
    'id'            => 'dwa_cleartmp',
    'title'         =>  '<img src="../modules/dwa/web-grips.png" style="margin-top:-3px; float:left;margin-right:10px"><p style="margin-top:3px; margin-bottom:0"> Clear Tmp/</p>',
    'description'   => '<p><strong>Tmp Verzeichnis leeren</strong></p>',
    'thumbnail'     => 'picture.jpg',
    'version'       => '1.02 (2018-12-18)',
    'author'        => 'WEB-Grips',
    'url'           => 'https://www.web-grips.de',
    'email'         => 'support@web-grips.de',
    'extend'        => array('navigation'     => 'dwa/dwa_cleartmp/dwa_cleartmp_navigation'),
    'blocks'        => array(array('template'           => 'header.tpl',
                                   'block'              => 'admin_header_links',
                                   'file'               => '/out/blocks/admin/header.tpl')),

);

if (oxRegistry::get('oxViewConfig')->isModuleActive('dwa_cleartmp')) {
    $aModule['description'] = '<a href="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '&cl=navigation&amp;fnc=dwacleartmp" id="cleartmplink" target="hiddenframe" class="rc"><button>' . oxRegistry::getLang()->translateString('DWA_NAVIGATION_CLEARTMP') . '</button></a><iframe name="hiddenframe" style="display:none"></iframe>';
}
$aModule['description'] .= '<hr>
     <p><strong><a href="https://www.web-grips.de/newsletter" target="_blank">Unser WEB-Letter informiert über neue Module + Updates. Mehr erfahren &raquo;</a></strong></p>';