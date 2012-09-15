<?php
//header( 'Content-Type: text/xml ');
//if ( defined('__DIR__') ) {	$__DIR__ = __DIR__;} else {	$__DIR__ = dirname(__FILE__);}
require_once(dirname(__FILE__) . "/supper/Search/Search.php");
require_once(dirname(__FILE__) . "/supper/aDateTime/aDateTime.php");
$search = new Search();
$search-> setKeyword($_POST["searchquery"]);
$search-> query();

/*
$resultXMLString;
$pages= array();
$count= 1;
$limit= 4;
$resultXML;
$exportXML = new SimpleXMLElement('<root></root>');
$searchinfoXML = new SimpleXMLElement('<root></root>');
$keywordXML = new SimpleXMLElement('<root></root>');
$exportRepeater= $exportXML -> addChild('repeater');
$keyword= $_POST['searchquery'];
$keyword_sha1 = sha1($keyword);
$keyword_folder_name = 'head';
$searchID = '';

$datetime = new aDateTime();
function queryGoogle($url){
	$handle = fopen($url, 'rb');
	$body = '';
	while (!feof($handle)) {
	$body .= fread($handle, 8192);
	}
	fclose($handle);
	return $body;
}

function setData($items) {	
	foreach($items as $value) {	
		//echo rawurldecode($value -> url);		
		$itemNode= $GLOBALS['exportRepeater'] -> addChild('item');
		$urlNode= $itemNode -> addChild('url', rawurldecode($value -> url));
		$visibleUrl= $itemNode -> addChild('visibleUrl', rawurldecode($value -> visibleUrl));	
		$cacheUrl= $itemNode -> addChild('cacheUrl', rawurldecode($value -> cacheUrl));		
	}	
}
function convert2XML($result) {	
	$GLOBALS['resultXMLString'] = '<?xml version="1.0"?><root>';
	$collection = json_decode($result, true);
	arrayParser($collection);
	$GLOBALS['resultXMLString'] .= '</root>';	
	return new SimpleXMLElement($GLOBALS['resultXMLString']);
}
function getURL($start){
	return 'http://ajax.googleapis.com/ajax/services/search/web?rsz=large&v=1.0&start='.$start.'&q='. urlencode($_POST['searchquery']); 
}
function query($url){	
	$queryResult = queryGoogle($url);
	//echo $queryResult;
	$GLOBALS['resultXML']= convert2XML($queryResult);	
	if($GLOBALS['resultXML'] -> responseData -> cursor -> resultCount){		
		setData($GLOBALS['resultXML'] -> responseData -> results -> item);		
	}
	return true;
}
function getMore($start){	
	if(query(getURL($start))){
		if(count($GLOBALS['pages']) > 0){
			getMore(array_pop($GLOBALS['pages']));
		}
	};
}

if(query(getURL(0))) {
	if($resultXML -> responseData -> cursor -> pages){
		foreach($resultXML -> responseData -> cursor -> pages -> item as $key => $value) {
			if($value -> start != 0) {
				$count += 1;	
				if($count < $limit){
					array_push($GLOBALS['pages'],$value -> start);
				}		
			}
		}
	}
	//echo count($pages);
	if(count($pages) > 0) {
		array_reverse($pages);
		getMore(array_pop($pages));
	}
}


$xml_sha1 = sha1($exportXML -> asXML());
$folder_name = substr($xml_sha1, 0, 2);
$file_name = substr($xml_sha1, 2);
if(!is_dir($folder_name)){
	if (!mkdir($folder_name, 0, true)) {
		die('Failed to create folders...');
		return;
	}
}
if(!file_exists($folder_name."/".$file_name.".xml")) {
	$exportXML-> asXML($folder_name."/".$file_name.".xml"); 
}

// 判斷keyword sha1 file的內容是否有上一筆查詢xml_sha1
if(file_exists($keyword_folder_name.'/'.$keyword_sha1.'.xml') == true) {
	$xml = simplexml_load_file($keyword_folder_name.'/'.$keyword_sha1.'.xml');
	
	$children = $xml->children();
	//print_r($children);
	foreach($children as $key => $value) {
		if($key == 'searchID') {
			$searchID = $value;
			$searchinfoXML -> addChild('previousID', $searchID);
		}
	}
}
////
// search info XML file 加入 this time search result sha1
$searchinfoXML -> addChild('resultID', $xml_sha1);
$searchinfo_sha1 = sha1($searchinfoXML -> asXML());
$searchinfo_folder_name = substr($searchinfo_sha1, 0, 2);
$searchinfo_file_name = substr($searchinfo_sha1, 2);
// create search info xml file
if(!is_dir($searchinfo_folder_name)){
	if (!mkdir($searchinfo_folder_name, 0, true)) {
		die('Failed to create folders...');
		return;
	}
}
if(!file_exists($searchinfo_folder_name."/".$searchinfo_file_name.".xml")) {
	$searchinfoXML -> asXML($searchinfo_folder_name."/".$searchinfo_file_name.".xml"); 
}
// 更新keyword sha1 file的內容
if(file_exists($keyword_folder_name.'/'.$keyword_sha1.'.xml') == true) {
	unlink($keyword_folder_name.'/'.$keyword_sha1.'.xml');
}
else {
    //create keyword folder
	if(!is_dir($keyword_folder_name)) {
		if (!mkdir($keyword_folder_name, 0, true)) {
			die('Failed to create folders...');
		}
	}
}
$keywordXML -> addChild('searchID', $searchinfo_sha1);
$keywordXML -> addChild('timestamp', $datetime -> getTimestamp());
$keywordXML -> asXML($keyword_folder_name.'/'.$keyword_sha1.'.xml');
*/
?>