<?php
require_once(dirname(__FILE__). "/../Error/Error.php");
require_once(dirname(__FILE__). "/Search.php");
class GoogleSearch extends Search{
	const GOOGLE_MAP = "GOOGLE_MAP";
	const GOOGLE_WEB = "GOOGLE_WEB";	
	private $APIS_PATH = array(
		"GOOGLE_MAP"=> "http://ajax.googleapis.com/ajax/services/search/map",
		"GOOGLE_WEB"=> "http://ajax.googleapis.com/ajax/services/search/web"
	);
	
	private $_params = "rsz=large&v=1.0";
	public function __construct ($keyword = "") {
		parent::__construct($keyword);
	}
	public function getAPI() {
		return $this-> _API;
	}
	public function setAPI($api) {
		$api = strtoupper($api);
		if($this-> APIS_PATH[$api]){
			$this-> _api = $api;			
		} else {
			$this-> throwError(new Error(Error::$API_INAVAILABLE, $api));
		}
	}
	public function setParams($params) {
		$this-> _params = $params;
	}
	public function getParams() {
		return $this-> _params;
	}
	public function query() {
		$this-> _url = $this->APIS_PATH[$this-> _api] . "?" . $this-> _params . "&start=" . $this-> _start. "&q=" . urlencode($this-> _keyword);
		parent::query();
		if(
	}
	private function checkMore() {
	
	}

	//rsz=large&v=1.0&start='.$start.'&q='. urlencode($_POST['searchquery']);
}