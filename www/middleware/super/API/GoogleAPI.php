<?php
require_once(dirname(__FILE__) . "/IAPI.php");
class GoogleAPI implements IAPI {
	const URL = "http://ajax.googleapis.com/ajax/services/search/web";
	const METHOD = "GET";
	protected $_params = array(
		"rsz" => "large",
		"v" => "2.0",
		"start" => 0,
		"q" => ""
	);
	protected $_searchObj = null;
	protected $_storageResult = array();
	protected $_exportXML = null;
	protected $_tmpResultXMLString = "";
	protected $_tmpResultXML = null;
	protected $_exportResult = null;	
	protected $_currentItem= 0;
	public $limit = 10;
	public function setSearch ( $searchObj ) {
		$this-> _searchObj = $searchObj;
	}
	public function setResult ( $result = "" ) {
		$this-> _tmpResultXML = $this-> _convert2XML($result);
		array_push($this-> _storageResult, $this-> _tmpResultXML);		
		if($this-> _isMoreSearch() === true) {
			$this-> _searchObj-> query();
		}
	}
	public function setKeyword( $keyword = "" ) {
		$this-> _params["q"] = $keyword;
	}
	public function getKeyword () {
		return $this-> _params["q"];
	}
	public function getParams (){
		return $this-> _params;
	}
	public function getURL () {
		return self::URL;
	}
	public function getMethod(){
		return self::METHOD;
	}
	public function export() {
		$count = count($this-> _storageResult);		
		$this-> _exportXML = new SimpleXMLElement('<root></root>');
		//echo $this-> _tmpResultXML-> asXML();			
		for( $i =0; $i < $count; $i += 1) {
			$item = $this-> _storageResult[$i]-> responseData -> results -> item;
			$itemCount = count($item);
			for ($in = 0; $in < $itemCount; $in += 1) {
				$itemNode = $this-> _exportXML -> addChild('item');
				$itemNode -> addChild('url', rawurldecode($item[$in] -> url));
				$itemNode -> addChild('visibleUrl', rawurldecode($item[$in] -> visibleUrl));	
				$itemNode -> addChild('cacheUrl', rawurldecode($item[$in] -> cacheUrl));
			}
		}				
		return $this-> _exportXML;		
	}
	protected function _convert2XML ( $result = "") {
		$this-> _tmpResultXMLString = '<?xml version="1.0"?><root>';
		$collection = json_decode($result, true);
		$this-> _json2XML($collection);
		$this-> _tmpResultXMLString .= '</root>';	
		return new SimpleXMLElement($this-> _tmpResultXMLString);
	}
	protected function _json2XML ( $collection = array() ) {
		foreach($collection as $key => $value) {
			if(is_array($value)){
				if(preg_match('/[^0-9]/i',$key, $arrayMatch)){
					$this-> _tmpResultXMLString .= '<'.$key.'>';
					$this-> _json2XML($value);
					$this-> _tmpResultXMLString .= '</'.$key.'>';
				}else{				
					$this-> _tmpResultXMLString .= '<item>';
					$this-> _json2XML($value);
					$this-> _tmpResultXMLString .= '</item>';				
				}            
			} else {
				if(preg_match('/[^0-9]/i',$key, $arrayMatch)){
					//echo $key . "===". $value . "<br>";
					$this-> _tmpResultXMLString .= '<'.$key.'>'.urlencode($value).'</'.$key.'>';
				}
			}
		}
	}
	protected function _isMoreSearch () {
		$cursorPage = $this->_tmpResultXML-> responseData -> cursor -> pages;
		if($cursorPage) {			
			$this-> _currentItem += 1;
			$nextItem = $cursorPage-> item[$this-> _currentItem];			
			if($this-> _currentItem < $this-> limit && $nextItem) {
				$this-> _params['start'] = $nextItem-> start;
				return true;
			} else {
				return false;
			}
		}
	}
	public function __toString () {
		return "[Class::GoogleAPI]";
	}
}
?>