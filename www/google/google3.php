<?php
header('Content-type: text/xml');
require_once(dirname(__FILE__) . "/supper/Search/Search.php");
require_once(dirname(__FILE__) . "/supper/API/GoogleAPI.php");
$search = new Search;
$search-> setKeyword($_POST["searchquery"]);
$google = new GoogleAPI();
$search-> setAPI($google);
//echo $search-> getKeyword();
$search-> query();
$google-> export();
?>