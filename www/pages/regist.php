<?php
//joshua tai 2
require_once(dirname(__FILE__).'\..\middleware\supper\Altarmap.php');
require_once(dirname(__FILE__).'\..\layout\StandardLayout.php');
$altarmap= Altarmap::getInstance();
$altarmap-> layout = new StandardLayout;
$altarmap-> setContent("regist.tpl");
$altarmap->assign('name', '123');
$altarmap-> display();
//joshua tai 2
?>