<?php
require_once(dirname(__FILE__).'\..\middleware\supper\MWLib.php');
$MWLib= MWLib::getInstance();
$smarty= $MWLib->getPackage("Smarty");

require_once(dirname(__FILE__).'\..\module\Header.php');
$header= new Header();
$smarty -> assign("header", $header->display("header.tpl"));
//$smarty -> assign("footer", "");
$smarty->display("standardTemplate.tpl");
?>