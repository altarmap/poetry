<?php
class SourceCodeManager {
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
				}
			}
		}catch( Exception $e){
			throw $e;
		}
	}
	protected function _createFile($file, $content){
		try{
			if( !file_exists($file) ){
				if( !$fp= fopen($file, w) ){
					$type = ErrorMessages::CREATE_FILE_DENIED;
					$msg = ErrorMessages::getErrorMsg ( $type );
					$code = ErrorMessages::getErrorCode( $type );
					throw Exception( $msg, $code);
				}else{
					fwrite($fp, $content);
					fclose($fp);
				}
			}
		}catch( Exception $e){
			throw $e;
		}
	}
	public function generate($url){
		$fp= fopen($url, r);
        $sourceCode = '';
        while ($length = fread($fp, 8192)) {
			$sourceCode .= $length;
        }
		fclose($fp);
        //echo $content;
		$socoSha1= sha1($sourceCode);
		$folderName= self::$SOURCE_CODE . "\\" .substr($socoSha1, 0, 2);
		$fileName= substr($socoSha1, 2) . ".html";
		$fileDir= $folderName . "\\" . $fileName
		$this-> _createFolder($folderName);
		$this-> _createFile($fileDir, $sourceCode);
	}	
}
$source= new SourceCodeManager();
$source-> generate("http://www.bblook.com/business/strategy/jingzh/200905/8052_2.html");
//$source-> generate("C:\AppServ\www\lib\server\simplehtmldom_1_5\example\ModifyHtmlContents.php");

?>