<?php
//if ( defined('__DIR__') ) {	$__DIR__ = __DIR__;} else {	$__DIR__ = dirname(__FILE__);}
require_once(dirname(__FILE__) . "/ISearch.php");
require_once(dirname(__FILE__). "/../Error/ErrorMessages.php");
//require_once(dirname(__FILE__). "/../Error/Error.php");
//require_once(dirname(__FILE__) . "/../Json/Json.php");
class Search implements ISearch {
	protected $_keyword = "";
	protected $_result = "";
	protected $_api = null;
	protected $_cursor = 0;
	//protected $_url = "";
	protected $_limit = 10;//default
	public function __construct ( $keyword = "" ) {
		$this-> setKeyword($keyword);
	}
	public function getKeyword() {
		return $this-> _keyword;
	}
	public function setKeyword( $keyword = "" ) {
		try {
			switch ( gettype($keyword) ) {
			case "string": case "integer": case "double":
				$this -> _keyword = $keyword;
				break;
			default:
				$type = ErrorMessages::TYPE_INVALID;
				$msg = ErrorMessages::getErrorMsg ( $type );
				$code = ErrorMessages::getErrorCode( $type );				
				throw new Exception($msg . " = " . $keyword, $code);
				break;
			}
		} catch(Exception $e) {			
			throw $e;
		}
	}
	public function setAPI ( $api = null ) {
		try {
			if($api instanceof IAPI) {  //判別類別間繼承關係之用
				$this-> _api = $api;
				$this-> _api-> setSearch($this);
			} else {
				$type = ErrorMessages::API_INAVAILABLE;
				$msg = ErrorMessages::getErrorMsg ( $type );
				$code = ErrorMessages::getErrorCode( $type );
				throw Exception( $msg, $code);
			}
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	public function query() {
		if ( $this->_api === null ) {
			return false;
		} else {
			$this-> _api-> setKeyword(urlencode($this-> _keyword));
			$method = strtoupper($this->_api-> getMethod());
			switch ( $method ) {
			case "GET":
				$url = $this-> _api-> getURL();
				$params = array();
				foreach ($this-> _api-> getParams() as $key => $value) {
					array_push($params, $key . "=" . $value);
				}
				//echo $url . "?" . implode ("&", $params). "<br>";
				$handle = fopen($url . "?" . implode ("&", $params), 'rb');				
				$body = "";
				while (!feof($handle)) {
					$body .= fread($handle, 8192);
				}
				fclose($handle);
				$this-> _result = $body;
				echo $this-> _result;
				$this-> _api-> setResult($this-> _result);
				break;
			case "POST":
				break;
			}
		}
	}
}