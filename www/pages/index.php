<?php
 /**
 * Example Application

 * @package Example-application
 */
require(dirname(__FILE__).'\..\middleware\supper\MWLib.php');
$MWLib= MWLib::getInstance();	
$smarty= $MWLib->getPackage('Smarty');


$smarty->display('index.tpl');
?>
