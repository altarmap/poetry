<?php
class UrlManager {
	protected static $SOURCE_CODE;
	
	public function __construct(){
		self::$SOURCE_CODE= dirname(__FILE__) . "\\source_code";
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
	public function generate($url){
		$urlSha1= sha1($url);
		$folderName= self::$SOURCE_CODE . "\\" .substr($urlSha1, 0, 2);
		$fileName= substr($urlSha1, 2) . ".html";
		$fileDir= $folderName . "\\" . $fileName;
		if( !file_exists($fileDir) ){
			if( $fp= fopen($url, r) ){
				$sourceCode = '';
				while ($length = fread($fp, 8192)) {
					$sourceCode .= $length;
				}
				fclose($fp);
				//echo $sourceCode;
				if( $this-> _createFolder($folderName) ){
					$this-> _createFile($fileDir, $sourceCode);
				}
			}
		}
	}
}
?>