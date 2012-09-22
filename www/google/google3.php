<?php
require_once(dirname(__FILE__) . "/supper/Search/Search.php");
require_once(dirname(__FILE__) . "/supper/API/GoogleAPI.php");
$search = new Search;
$search-> setKeyword("戴");
$search-> setAPI(new GoogleAPI);
//echo $search-> getKeyword();
$search-> query();
?>