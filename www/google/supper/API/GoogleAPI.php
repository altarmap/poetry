<?php
require_once(dirname(__FILE__) . "/IAPI.php");
class GoogleAPI implements IAPI {
	const URL = "http://ajax.googleapis.com/ajax/services/search/web";
	const METHOD = "GET";
	protected $_params = array(
		"rsz" => "large",
		"v" => "1.0",
		"start" => 0,
		"q" => ""
	);	
	public function parseResult ( $result = "") {
		
	}
	public function isMore ( $result = "" ) {
		$this-> parseResult(parseResult);
	}
	public function setKeyword( $keyword = "" ) {
		$this-> _params["q"] = $keyword;
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
	public function __toString () {
		return "[Class::GoogleAPI]";
	}
}
?>