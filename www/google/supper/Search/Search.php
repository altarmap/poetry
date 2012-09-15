<?php
//if ( defined('__DIR__') ) {	$__DIR__ = __DIR__;} else {	$__DIR__ = dirname(__FILE__);}
require_once(dirname(__FILE__) . "/ISearch.php");
require_once(dirname(__FILE__). "/../Error/Error.php");
require_once(dirname(__FILE__) . "/../Json/Json.php");
class Search implements ISearch {
	private $_keyword;
	private $_result;
	public function __construct ( $keyword = "" ) {
		$this-> setKeyword($keyword);
	}
	public function getKeyword() {
		return $this-> _keyword;
	}
	public function setKeyword( $keyword = "" ) {		
		$validateType = array("string" => true, "integer" => true, "double" => true);		
		if(!$validateType[gettype($keyword)]){			
			throw new exception(Error::TYPE_INVALID);
		}
		$this -> _keyword = $keyword;		
	}
	public function query() {
		$this-> _result = $this-> queryWebservice(/*$this-> _apiTypes*/);
		if($this-> _result != "") {
			$this-> parseData($this-> _result);
		}		
	}
	private function json2xml($array) {   
		foreach($array as $key => $value) {			
			// 沒有空白和折行
			if(is_array($value)){
				if(preg_match('/[^0-9]/i',$key, $arrayMatch)){
					$this-> _resultXMLString .= '<'.$key.'>';
					$this-> json2xml($value);
					$this-> _resultXMLString .= '</'.$key.'>';
				}else{				
					$this-> _resultXMLString .= '<item>';
					$this-> json2xml($value);
					$this-> _resultXMLString .= '</item>';				
				}            
			} else {						
				if(preg_match('/[^0-9]/i',$key, $arrayMatch)){
					$this-> _resultXMLString .= '<'.$key.'>'.rawurlencode($value).'</'.$key.'>';				
				}
			}
		}
	}
	private function queryWebservice( /*$apiTypes*/ ) {
		$url = 'http://ajax.googleapis.com/ajax/services/search/web?rsz=large&v=1.0&start=0&q='. urlencode($this->_keyword);
		$body = '';
		try { 
			$handle = fopen($url, 'rb');
			if ($handle) {
				while (!feof($handle)) {
					$body .= fread($handle, 8192);
				}
				fclose($handle);
			} else {				
			}
		} catch (Exception $e) {		
		}
		return $body;
	}
	private function parseData ( $result ) {
		//$json = new Json();		
		//$json-> loadJson( $result );		
		
		$collection = json_decode($result, true);
		$this-> _resultXMLString .= '<?xml version="1.0"?><root>';
		$this-> json2xml( $collection );
		$this-> _resultXMLString .= '</root>';
		echo $this-> _resultXMLString;
		if($this-> _parseRule) {
			$this-> _parseRule ($data);
		}
	}
	
}