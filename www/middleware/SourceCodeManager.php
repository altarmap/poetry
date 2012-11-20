<?php
require_once("FileManager.php");
require_once(dirname(__FILE__) . "/../../lib/server/simplehtmldom_1_5/simple_html_dom.php");
class SourceCodeManager {
	protected static $REPO;
	protected static $HEAD;
	protected static $SOURCE_CODE;
	protected $fileManager = null;
	public function __construct($api = null){
		self :: $REPO = dirname(__FILE__) . "\\repo";
		self :: $HEAD = self :: $REPO . "\\head";
		self :: $SOURCE_CODE = dirname(__FILE__) . "\\source_code";
		$this-> fileManager = new FileManager();
	}
	protected function _createFolder($folder){
		try{
			if( !is_dir($folder) ){
				if( !mkdir($folder) ){
					$type = ErrorMessages::CREATE_DIR_DENIED;
					$msg = ErrorMessages::getErrorMsg ( $type );
					$code = ErrorMessages::getErrorCode( $type );
					throw Exception( $msg, $code);
				}else{
					return true;
				}
			}else{
				return true;
			}
		}catch( Exception $e){
			throw $e;
		}
	}
	protected function _createFile($file, $content){
		try{
			if( !$fp= fopen($file, w) ){
				$type = ErrorMessages::CREATE_FILE_DENIED;
				$msg = ErrorMessages::getErrorMsg ( $type );
				$code = ErrorMessages::getErrorCode( $type );
				throw Exception( $msg, $code);
			}else{
				fwrite($fp, $content);
				fclose($fp);
			}
		}catch( Exception $e){
			throw $e;
		}
	}
	public function fileGenerate($resultId, $resultXML){
		$resultXMLHTML = str_get_html($resultXML-> asXML()); //XML format 轉 HTML format
		//echo $resultXMLHTML; //自動濾掉 <root></root>
		$tmpFlag = array();
		$items = $resultXMLHTML-> find('item');
		for ( $index = 0; $index < count($items); $index += 1) {
			$curentItemURL = $items[$index]-> find('url', 0)-> innertext;  //$items[$index]-> find('url') is array
			$urlEncodeString = rawurlencode($curentItemURL); //因為url中會有亂碼
			if ($tmpFlag[$urlEncodeString]) {
				array_push($tmpFlag[$urlEncodeString], $index);
			} else {
				$sourceURL[] = $curentItemURL; //只會有唯一 url
				$tmpFlag[$urlEncodeString] = array($index);
			}
		}		
		/*
		if ( $item-> cacheUrl ) {
			$sourceURL[] = $item-> cacheUrl . "";						
		} else {
			$sourceURL[] = $item-> url . "";				
		}*/
		foreach( $sourceURL as $url) {			
			//echo $url."<br>";
			//echo $tmpFlag[rawurlencode($url)][0];
			set_time_limit(60);
			$date = new DateTime();
			$currentTime =  $date-> format("U");
			$DestinationTime = $currentTime + rand(3, 6);
			$updateTime = $currentTime;
			while( $updateTime != $DestinationTime){
				$now = new DateTime();
				$updateTime = $now-> format("U");
			}			
			$flag = $tmpFlag[rawurlencode($url)];
			if( $fp= fopen($url, r) ){	
				$sourceCode = '';
				while ($length = fread($fp, 8192)) {
					$sourceCode .= $length;
				}				
				$htmlSha1 = sha1($sourceCode);
				$htmlFolderName= self::$SOURCE_CODE . "\\" .substr($htmlSha1, 0, 2);
				$htmlFileName= $htmlFolderName . '\\' . substr($htmlSha1, 2) . ".html";
				if( $this-> _createFolder($htmlFolderName) ){
					$this-> _createFile($htmlFileName, $sourceCode);
				}
				//$flag = $tmpFlag[rawurlencode($url)];
				for ($index = 0; $index < count($flag); $index += 1) {
					$str = $items[$flag[$index]]->innertext."<sourcecodeGetted>true</sourcecodeGetted>";
					$str .= "<sourcecodeId>" . $htmlSha1 . "</sourcecodeId>";
					$items[$flag[$index]]->innertext = $str;
				}
				fclose($fp);
			} else {
				for ($index = 0; $index < count($flag); $index += 1) {			
					$items[$flag[$index]]->innertext = $items[$flag[$index]]->innertext."<sourcecodeGetted>false</sourcecodeGetted><sourcecodeId/>";
				}
			}			
		}		
		$str = '<root>'. trim(str_replace('<?xml version="1.0"?>', '', trim($resultXMLHTML-> innertext))) . '</root>';		
		$this-> fileManager-> setResultXML($str, $resultId);		
	}
	public function generate($keyword = ""){		
		$headFolders = array_diff(scandir(self::$HEAD), array('.', '..'));
		$heads = array();
		if($keyword != "") {			
			$keywordSha1 = sha1(urlencode($keyword));
			$heads[] = $keywordSha1;						
		} else {
			foreach( $headFolders as $headFolder ) {
				$headFolderPath =  self :: $HEAD . "\\" . $headFolder;
				$headXMLFiles = array_diff(scandir($headFolderPath), array('.', '..'));	
				foreach( $headXMLFiles as $headXMLFile ) {
					$heads[] = $headFolder . subStr($headXMLFile, 0, strlen($headXMLFile) - 4);
				}
			}
		}		
		foreach($heads as $sha1) {
			$headerXml = $this-> fileManager-> getHeadXML($sha1);			
			$searchIdXml = $this-> fileManager-> getXML($headerXml-> searchID);				
			$resultIDXml = $this-> fileManager-> getXML($searchIdXml-> resultID);			
			$this-> fileGenerate($searchIdXml-> resultID, $resultIDXml);
		}		
	}
}
?>