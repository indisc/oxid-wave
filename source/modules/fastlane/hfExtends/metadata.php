<?php
$aModule = array(
    'id'          => 'hfExtends',
    'title'       => 'Healthfood24 :: General Extensions',
    'version'     => '1.1',
    'url'         => 'http://www.fastlane.de',
    'email'       => 'info@fastlane.de',
    'extend'      => array(
        'oxarticle' => 'fastlane/hfExtends/models/hfExtends_oxarticle',

    ),
    'blocks'    => array(
        'admin_article_main_editor'  =>  'fastlane/hfExtends/views/admin/tpl/article_main.tpl',
    ),
);