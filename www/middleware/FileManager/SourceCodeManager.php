<?php
require_once("FileManager.php");
require_once(dirname(__FILE__) . "/../../lib/server/simplehtmldom_1_5/simple_html_dom.php");
class SourceCodeManager {
	protected $_fileManager = null;
	public function __construct(){
		$this-> _fileManager = new FileManager();
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
		$resultXMLHTML= str_get_html($resultXML-> asXML());
		$tmpFlag = array();
		$items= $resultXMLHTML-> find('item');
		for( $index = 0; $index < count($items); $index += 1){
			$curentItemURL= $items[$index]-> find('url', 0)-> innertext;
			$urlEncodeString= rawurlencode($curentItemURL);
			if( $tmpFlag[$urlEncodeString] ){
				array_push($tmpFlag[$urlEncodeString], $index);
			}else{
				$sourceURL[]= $curentItemURL;
				$tmpFlag[$urlEncodeString]= array($index);
			}
		}
		/*
		if( gettype($paths) == 'string') {
			$urls[] = $paths;
		} else {
			$urls = $paths;
		}
		*/
		foreach( $sourceURL as $url) {
			//echo $url."<br>";
			//echo $tmpFlag[rawurlencode($url)][0];
			set_time_limit(120);
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
				fclose($fp);
				//echo mb_detect_encoding($sourceCode); //"UTF-8"
				$htmlSha1 = sha1($sourceCode);
				$htmlFolderName= $this-> _fileManager-> getSourcePath() . "\\" . $this-> _fileManager-> getDirNameBySha1($htmlSha1);
				$htmlFileName= $htmlFolderName . '\\' . $this-> _fileManager-> getFileNameBysha1($htmlSha1) . ".html";
				if( $this-> _createFolder($htmlFolderName) ){
					$this-> _createFile($htmlFileName, $sourceCode);
				}
				for($index = 0; $index < count($flag); $index += 1){
					$str= $items[$flag[$index]]-> innertext . "<sourcecodeGetted>true</sourcecodeGetted>";
					$str .= "<sourcecodeId>" . $htmlSha1 . "</sourcecodeId>";
					$items[$flag[$index]]-> innertext = $str;
				}
			}else{
				for($index = 0; $index < count($flag); $index += 1){
					$items[$flag[$index]]-> innertext= $items[$flag[$index]]-> innertext . "<sourcecodeGetted>false</sourcecodeGetted><sourcecodeId/>"; 
				}
			}
		}
		$str= "<root>" . trim(str_replace('<?xml version="1.0"?>', '', trim($resultXMLHTML-> innertext))) . "</root>";
		$this-> _fileManager-> setResultXML($str, $resultId);
	}
	public function generate($keyword = ""){
		$headFolders = array_diff(scandir($this-> _fileManager-> getHeadPath()), array('.', '..'));
		$heads = array();
		if($keyword != "") {
			$keywordSha1 = sha1(urlencode($keyword));
			$heads[] = $keywordSha1;
		} else {
			foreach( $headFolders as $headFolder ) {
				$headFolderPath = $this-> _fileManager-> getHeadPath() . "\\" . $headFolder;
				$headXMLFiles = array_diff(scandir($headFolderPath), array('.', '..'));	
				foreach( $headXMLFiles as $headXMLFile ) {
					$heads[] = $headFolder . subStr($headXMLFile, 0, strlen($headXMLFile) - 4);
				}
			}
		}
		foreach($heads as $sha1) {
			$headerXml = $this-> _fileManager-> getHeadXML($sha1);			
			$searchIdXml = $this-> _fileManager-> getXML($headerXml-> searchID);				
			$resultIDXml = $this-> _fileManager-> getXML($searchIdXml-> resultID);
			$this-> fileGenerate($searchIdXml-> resultID, $resultIDXml);
		}
	}
}
?>