<?php
//header('Content-type: text/xml');
require_once(dirname(__FILE__) . "/../super/Search/Search.php");
require_once(dirname(__FILE__) . "/../super/API/GoogleAPI.php");
require_once(dirname(__FILE__) . "/../FileManager/FileManager.php");
require_once(dirname(__FILE__) . "/../FileManager/SourceCodeManager.php");

$search = new Search;
$search-> setKeyword($_POST["searchquery"]);
$google = new GoogleAPI();
$search-> setAPI($google);
$search-> query();
$generator = new FileManager($google);
$generator-> generate();
$sourceCode= new SourceCodeManager();
$sourceCode-> generate($_POST["searchquery"]);
?>