<?php
//header('Content-type: text/xml');
require_once(dirname(__FILE__) . "/supper/Search/Search.php");
require_once(dirname(__FILE__) . "/supper/API/GoogleAPI.php");
require_once(dirname(__FILE__) . "/../middleware/FileManager/FileManager.php");
$search = new Search;
$search-> setKeyword($_POST["searchquery"]);
$google = new GoogleAPI();
$search-> setAPI($google);
//echo $search-> getKeyword();
$search-> query();
$generator = new FileManager($google);
$generator-> generate();
$generator = new FileManager($yahoo);
/*
$searchInfoXML = $generator -> getXML($lastSearchID);
$resultXML = $generator -> getXML($searchInfoXML-> resultID);
$items = $resultXML->item;*/

?>