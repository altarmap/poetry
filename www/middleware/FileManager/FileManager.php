<?php
class FileManager {
	protected static $REPO;
	protected static $HEAD_PATH;
	protected $_api;
	protected $_resultSha1;
	protected $_resultXML;
	protected $_searchInfoSha1;
	protected $_searchInfoXML;
	protected $_keywordSha1;
	protected $_keywordXML;	
	public function __construct($api){		
		try {
			if($api && $api instanceof IAPI) {  //判別類別間繼承關係之用
				$this-> _api = $api;
				self :: $REPO = dirname(__FILE__) . "\\repo";
				self :: $HEAD_PATH = self :: $REPO . "\\head";				
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
	protected function _createFolder($folder){
		try{
			if( !is_dir( $folder ) ) {
				if( !mkdir( $folder ) ) {
					$type = ErrorMessages::CREATE_DIR_DENIED;
					$msg = ErrorMessages::getErrorMsg ( $type );
					$code = ErrorMessages::getErrorCode( $type );
					throw Exception( $msg, $code);
				} else{
					return true; 
				}
			} else {
				return true;
			}
		} catch ( Exception $e ) {
			throw $e;
		}
		return false;		
	}	
	protected function _setResultXML ($result) {		
		$this-> _resultXML = new SimpleXMLElement($result);
		$this-> _resultSha1 = $this-> getSha1($result);
		$folderName = self :: $REPO . "\\" . substr($this-> _resultSha1, 0, 2);
		$file_name = substr($this-> _resultSha1, 2) . ".xml";
		if($this-> _createFolder($folderName)) {
			if( !file_exists($folderName . "\\". $file_name) ) {
				$this-> _resultXML-> asXML( $folderName . "\\". $file_name );
			}
		}	
	}
	protected function _setSearchInfoXML ($resultSha1, $prevResultSha1 = null) {		
		$searchInfoXML = new SimpleXMLElement('<root></root>');
		$searchInfoXML-> addChild("resultID", $resultSha1);
		if($prevResultSha1) {
			$searchInfoXML-> addChild("previousID", $prevResultSha1);
		} else {
			$searchInfoXML-> addChild("previousID");
		}
		$this-> _searchInfoSha1 = $this-> getSha1($searchInfoXML-> asXML());
		$folderName = self :: $REPO . "\\" . substr($this-> _searchInfoSha1, 0, 2);
		$file_name = substr($this-> _searchInfoSha1, 2) . ".xml";
		if($this-> _createFolder($folderName)) {
			$searchInfoXML-> asXML( $folderName . "\\". $file_name );
		}
	}
	protected function _setHeadXML ($searchInfoSha1) {		
		$this-> _keywordXML = new SimpleXMLElement('<root></root>');
		$this-> _keywordXML-> addChild("searchID", $searchInfoSha1);		
		$this-> _keywordSha1 = $this-> getSha1($this-> _api-> getKeyword());
		$folderName = self :: $HEAD_PATH . "\\" . substr($this-> _keywordSha1, 0, 2);
		$file_name = substr($this-> _keywordSha1, 2) . ".xml";
		if($this-> _createFolder($folderName)) {
			$this-> _keywordXML-> asXML( $folderName . "\\". $file_name ); 
		}	
	}
	
	public function getSha1 ($content) {
		return sha1($content);
	}
	public function getXML ($sha1) {
		$folderName= self::$REPO . "\\" . substr($sha1, 0, 2);
		$file_name= substr($sha1, 2) . ".xml";
		return simplexml_load_file($folderName . "\\". $file_name);
		
	}
	public function getHeadXML ($sha1) {		
		$folderName = self :: $HEAD_PATH . "\\" . substr($sha1, 0, 2);	
		$file_name = substr($sha1, 2) . ".xml";
		if( file_exists( $folderName . "\\". $file_name) ) {
			return simplexml_load_file( $folderName . "\\". $file_name );
		} else {
			return null;
		}
	}
	public function generate () {		
		$this-> _setResultXML($this-> _api-> export()-> asXML());
		$this-> _keywordSha1 = $this-> getSha1($this-> _api-> getKeyword());
		$this-> _keywordXML = $this-> getHeadXML($this-> _keywordSha1);
		if( $this-> _keywordXML ) {
			$this-> _setSearchInfoXML($this-> _resultSha1, $this-> _keywordXML-> searchID);
		} else {
			$this-> _setSearchInfoXML($this-> _resultSha1);
		}
		$this-> _setHeadXML($this-> _searchInfoSha1);		
	}
}
?>