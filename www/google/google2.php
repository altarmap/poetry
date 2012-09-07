<?php
//header ("Content-Type:text/xml"); 
$resultXMLString;
$pages= array();
$count= 1;
$limit= 4;
$resultXML;
$exportXML = new SimpleXMLElement('<root></root>');
$searchinfoXML = new SimpleXMLElement('<searchinfo></searchinfo>');
$keywordXML = new SimpleXMLElement('<keyword></keyword>');
$exportRepeater= $exportXML -> addChild('repeater');
$keyword= $_POST['searchquery'];
$keyword_sha1 = sha1($keyword);
$keyword_folder_name = 'head';
$lastsearchinfo = '';
class MyDateTime extends DateTime {
    public function getTimestamp() {
         return method_exists('DateTime', 'getTimestamp') ? 
             parent::getTimestamp() : $this->format('U');
    }
}

$datetime = new MyDateTime();

function getNowTimestamp() {
	$date = new DateTime();
	return $date -> getTimestamp();
}
function queryGoogle($url){
	$handle = fopen($url, 'rb');
	$body = '';
	while (!feof($handle)) {
	$body .= fread($handle, 8192);
	}
	fclose($handle);
	return $body;
}
function arrayParser($array) {    
	foreach($array as $key => $value) {			
		// 沒有空白和折行
		if(is_array($value)){
			if(preg_match('/[^0-9]/i',$key, $arrayMatch)){
				$GLOBALS['resultXMLString'] .= '<'.$key.'>';
				arrayParser($value);
				$GLOBALS['resultXMLString'] .= '</'.$key.'>';
			}else{				
				$GLOBALS['resultXMLString'] .= '<item>';
				arrayParser($value);
				$GLOBALS['resultXMLString'] .= '</item>';				
			}            
		} else {						
			if(preg_match('/[^0-9]/i',$key, $arrayMatch)){
				$GLOBALS['resultXMLString'] .= '<'.$key.'>'.rawurlencode($value).'</'.$key.'>';				
			}
		}
	}
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

//echo $exportXML -> asXML();
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
		if($key == 'lastsearchinfo') {
			$lastsearchinfo = $value;
			$searchinfoXML -> addChild('previoussearchinfo', $lastsearchinfo);
		}
	}
}
////
// search info XML file 加入 this time search result sha1
$searchinfoXML -> addChild('resultsha1', $xml_sha1);
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
////



// 更新keyword sha1 file的內容
if(file_exists($keyword_folder_name.'/'.$keyword_sha1.'.xml') == true) {
	unlink($keyword_folder_name.'/'.$keyword_sha1.'.xml');
}
else {
// create keyword folder
	if(!is_dir($keyword_folder_name)) {
		if (!mkdir($keyword_folder_name, 0, true)) {
			die('Failed to create folders...');
		}
	}
}
$keywordXML -> addChild('lastsearchinfo', $searchinfo_sha1);
$keywordXML -> addChild('timestamp', $datetime -> getTimestamp());
$keywordXML -> asXML($keyword_folder_name.'/'.$keyword_sha1.'.xml');
////	

?>