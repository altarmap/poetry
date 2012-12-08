<?php
require_once("../FileManager/FileManager.php");
require_once("../../lib/server/simplehtmldom_1_5/simple_html_dom.php");
header("Content-Type:text/html; charset=utf-8");
class ContentFilter {
	protected $_remove_tag= array("iframe", "img", "comment", "input", "script", "link", "button", "style", "object", "svg", "select", "embed");
	protected $_id_class_name= array(); 
	protected $_fileManager= null;
	
	function __construct(){
		$this-> _fileManager = new FileManager();
	}
	private function _checkChinese ($html) {		
		$matas = $html-> find('meta');
		$charset = '';
		if(count($matas) > 0) {
			foreach($matas as $key => $value) {					
				$fullvalue = $value -> content;
				if($fullvalue) {
					preg_match('/charset=(.+)/', $fullvalue, $matches);
					if ($matches[1] != '' && $charset == '') {							
						$charset = $matches[1];														
					}						
				} else {
					if ($value -> charset != '' && $charset == '') {							
						$charset = $value -> charset;							
					}												
				}					
			}
		}		
		if($charset != '') {
			$charset = strtolower($charset);
			//echo $charset.'<br>';
			switch($charset) {
			case 'utf-8': case 'big5':
				break;
			default:
				$html = str_get_html('');
				break;
			}
		} else {
			$charset  = $html->find('html', 0)-> lang;
			if($charset != '') {
				$charset = strtolower($charset);
				switch($charset){
				case 'zh-tw':
					break;
				default:
					$html = str_get_html('');
					break;
				}
			} else {
				$html = str_get_html('');
			}
		}		
		
		/*$test1 = iconv("UTF-8", "big5//TRANSLIT", $str);
		$test2 = iconv("UTF-8", "big5//IGNORE", $str);
		if ($test1 == $test2) {
		   echo 'traditional';
		} else {
		   $test3 = iconv("UTF-8", "gb2312//TRANSLIT", $str);
		   $test4 = iconv("UTF-8", "gb2312//IGNORE", $str);
		   if ($test3 == $test4) {
			  echo 'simplified';
		   } else {
			  echo 'Failed to match either traditional or simplified';
		   }
		}*/
		return $html;
	}
	public function getSourceIdByKeyword($keyword){
		$sha1 = sha1(urlencode($keyword));
		return $this-> getSourceIdByKeywordSha1($sha1);
	}
	public function getSourceIdByKeywordSha1($sha1){
		$sourceIds= array();
		$headXml= $this-> _fileManager-> getHeadXML($sha1);
		if($headXml){
			$searchIDXml= $this-> _fileManager-> getXML($headXml-> searchID);
			$resultIDXml= $this-> _fileManager-> getXML($searchIDXml-> resultID);
			foreach($resultIDXml-> item as $value){
				if($value-> sourcecodeGetted){
					if($value-> sourcecodeId != ''){
						$sourceIds[]= (string)$value-> sourcecodeId;  //change object type to string type
					}
				}
			}
		}
		return $sourceIds;
	}
	public function getSourceId($target){
		$sourceIds= array();
		switch (gettype($target)){
		case 'string':
			if (strlen($target) == 40){
				if (preg_match('/[^0-9A-Fa-f]/i', $target)){
					$sourceIds= $this-> getSourceIdByKeyword($target);
				} else {
					$sourceIds= $this-> getSourceIdByKeywordSha1($target);
					if( count($sourceIds) == 0){
						$sourceIds[]= $target;
					}
				}
			} else {
				$sourceIds= $this-> getSourceIdByKeyword($target);
			}
			break;
		case 'array':
			if(count($target) > 0){
				foreach($target as $value){
					$sourceIds[$value]= $this-> getSourceId($value);
				}
			}
			break;
		}
		return $sourceIds;
	}
	public function getHTML($sourceId, $content= ''){		
		if($sourceId){
			$fileManager= $this-> _fileManager;
			$filePath= $fileManager-> getSourcePath() . '\\' . $fileManager-> getDirNameBySha1($sourceId) . '\\' . $fileManager-> getFileNameBySha1($sourceId) . '.html';
			$html= file_get_html($filePath);			
			if( !$html ){
				if( $fp= fopen($filePath, r) ){
					while( $length= fread($fp, 8192) ){
						$sourceCode .= $length;
					}
					fclose($fp);
					$html= $this-> getHTML(null, $sourceCode);
				}
			}
		} else {
			$html= str_get_html($content);
		}		
		$html = $this-> _checkChinese($html);
		return $html;
	}
	public function filterStrLength($domArray){
		$htmlArray= array();
		foreach($domArray as $item){
			$tmpStr = preg_replace('/\s+/', '', trim($item-> innertext));
			if(mb_strlen($tmpStr, 'utf-8') > 20) {
				$htmlArray[] = $item-> innertext;
			}
		}
		return $htmlArray;
	}
	public function delInsideIdClass($dom){
		foreach($dom-> find('[id*=content]') as $subDom) {
			$subDom-> outertext = "";
		}
		foreach($dom-> find('[class*=content]') as $subDom) {
			$subDom-> outertext = "";
		}
	}
	public function filterRepeatIdClass($htmlArray, $attriName){
		foreach($htmlArray as $item) {
			$tmpHtmlDOM = str_get_html($item);
			$this-> delInsideIdClass($tmpHtmlDOM);
			$tmpHtmlDOM2 = str_get_html($tmpHtmlDOM-> innertext); 
			$tmpStr = preg_replace('/\s+/', '', trim($tmpHtmlDOM2-> plaintext));
			if(mb_strlen($tmpStr, 'utf-8') > 20) {
				$tmpStr= preg_replace('/[~|!|@|$|%|\^|_|\*|\(|\)|\.|-|\+]+/', '', $tmpStr);
				$tmpStr= preg_replace('/&nbsp;/', '', $tmpStr);	
				if(mb_strlen($tmpStr, 'utf-8') > 20) {
					$htmlString .= '<div ' . $attriName . '="content">'. $tmpHtmlDOM2-> innertext . '</div>';
				}
				$tmpHtmlDOM2-> clear();
			}
			$tmpHtmlDOM-> clear();
		}
		return $htmlString;
	}
	public function filterHTML($html){
		$remove_tag= $this-> _remove_tag;
		$keywords= $this-> _id_class_name;
		if( gettype($html) == "object"){
			for ($i=0; $i< count($remove_tag); $i++){
				foreach ($html-> find($remove_tag[$i]) as $dom){
					$dom-> outertext= "";
				}
			}
			$idDoms = $html-> find('[id*=content]');
			$classDoms = $html-> find('[class*=content]');
			$htmlIdArray = $this-> filterStrLength($idDoms);
			$htmlClassArray = $this-> filterStrLength($classDoms);
			$html-> clear();
			$htmlString = '';		
			$htmlString= $this-> filterRepeatIdClass($htmlIdArray, 'id');
			$htmlString .= $this-> filterRepeatIdClass($htmlClassArray, 'class');
			return $exportHtml = str_get_html($htmlString);
		}
	}
	public function exportFile($html){
		if($html) {
			$html_sha1 = sha1($html);
			$folder_name = $this-> _fileManager-> getParsedPath() ."\\". substr($html_sha1, 0, 2);
			$file_name = $folder_name . "\\". substr($html_sha1, 2);
			if(!is_dir($folder_name)){
				if (!mkdir($folder_name)) {
					die('Failed to create folders...');
					return;
				}
			}
			if(!file_exists($file_name.".html")) {
				$file= fopen($file_name.".html", "w");
				fwrite($file, $html);
				fclose($file); 
			}
		}
	}
	public function parse($target= ''){
		if($target){
			$sourceIds= $this-> getSourceId($target);
			foreach($sourceIds as $value){
				switch (gettype($value)){
				case 'string':
					echo '==============================================================<br>';
					$html= $this-> getHTML($value);					
					echo $value;
					if ($html != '') {						
						//echo ':'. mb_detect_encoding($html) . '<br>';
						//echo $html-> plaintext.'<br>';
					}
					$filteredHTML= $this-> filterHTML($html);
					//echo $filteredHTML-> plaintext;
					$str = preg_replace('/\s\s+/', '',$filteredHTML-> plaintext);
					//$str = preg_replace("/&#?[a-z0-9]{2,8};/i","",$str);
					//echo $str;
					//echo preg_replace('/[^\x4E00-\x9FFF]+/', '', $str);
					echo preg_replace('/[^\x{4e00}-\x{9fff}]+/u','', $str);
					//$this-> exportFile($filteredHTML);
					break;
				case 'array':
					foreach($value as $sourceId){
						$html= $this-> getHTML($sourceId);
						$filteredHTML= $this-> filterHTML($html);
						//$this-> exportFile($filteredHTML);
					}
					break;
				}
			}
		} else {
			$dir_folders = array_diff(scandir( $this-> _fileManager-> getSourcePath() ), array('.', '..'));
			foreach($dir_folders as $sourceFolder){
				$dir_files = array_diff(scandir($this-> _fileManager-> getSourcePath() . "\\". $sourceFolder), array('.', '..'));
				foreach($dir_files as $sourceFile){
					$filePath= $this-> _fileManager-> getSourcePath() . "\\". $sourceFolder . "\\" . $sourceFile;
					$html= file_get_html($filePath);
					$filteredHTML= $this-> filterHTML($html);
					$this-> exportFile($filteredHTML);
				}
			}
		}
	}
}
$modify= new ContentFilter();
$modify-> parse("5a2ded88089c64c5f7ffdaaf54db853a30b393c7");

?>