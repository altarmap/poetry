<?php
include('../simple_html_dom.php');
class ModifyHtmlContents {
	protected $_remove_tag= array("iframe", "img", "comment", "input", "script", "link", "button", "style", "object", "svg", "select", "textarea", "embed");
	protected static $_dir_folder;
	protected static $_repo_folder;
	
	function __construct(){
		$this-> getDirFolder();
		$this-> repoDirFolder();
	}
	public function getDirFolder(){
		return self::$_dir_folder = dirname(__FILE__). '../../../../../middleware/FileManager/source_code';
	}
	public function repoDirFolder(){
		return self::$_repo_folder= dirname(__FILE__). '/repo_code';
	}
	public function delInsideIdClass($dom){
		foreach($dom-> find('[id*=content]') as $subDom) {
			$subDom-> outertext = "";
		}
		foreach($dom-> find('[class*=content]') as $subDom) {
			$subDom-> outertext = "";
		}
	}
	public function parse(){
		$remove_tag = $this-> _remove_tag;
		$dir_folders = array_diff(scandir(self::$_dir_folder), array('.', '..'));
		foreach($dir_folders as $sourceFolder){
			$dir_files = array_diff(scandir(self::$_dir_folder . "/". $sourceFolder), array('.', '..'));
			foreach($dir_files as $sourceFile){
				$filePath= self::$_dir_folder . "/". $sourceFolder . "/" . $sourceFile;
				$html= file_get_html($filePath);				
				for ($i=0; $i< count($remove_tag); $i++){
					foreach ($html-> find($remove_tag[$i]) as $dom){
						$dom-> outertext= "";
					}
				}				
				for ($i=0; $i< count($keywords); $i++){
					foreach ($html-> find("[id*=".$keywords[$i]."]") as $dom) {
						$dom-> outertext = "";
					}
					foreach ($html-> find("[class*=".$keywords[$i]."]") as $dom) {
						$dom-> outertext = "";
					}
				}
				$idDoms = $html-> find('[id*=content]');
				$classDoms = $html-> find('[class*=content]');
				$htmlIdArray = array();
				foreach($idDoms as $item){
					$tmpStr = preg_replace('/\s+/', '', trim($item-> innertext));
					if(mb_strlen($tmpStr, 'utf-8') > 20) { 
						$htmlIdArray[] = $item-> innertext;
					}
				}
				$htmlClassArray = array();
				foreach($classDoms as $item){
					$tmpStr = preg_replace('/\s+/', '', trim($item-> innertext));
					if(mb_strlen($tmpStr, 'utf-8') > 20) {						
						$htmlClassArray[] = $item-> innertext;
					}					
				}
				$html-> clear();
				$htmlString = '';							
				foreach($htmlIdArray as $item) {
					$tmpHtmlDOM = str_get_html($item); 
					$this-> delInsideIdClass($tmpHtmlDOM);
					$tmpHtmlDOM2 = str_get_html($tmpHtmlDOM-> innertext); 
					$tmpStr = preg_replace('/\s+/', '', trim($tmpHtmlDOM2-> plaintext));
					if(mb_strlen($tmpStr, 'utf-8') > 20) {
						$tmpStr= preg_replace('/[~|!|@|$|%|\^|_|\*|\(|\)|\.|-|\+]+/', '', $tmpStr);
						$tmpStr= preg_replace('/&nbsp;/', '', $tmpStr);	
						if(mb_strlen($tmpStr, 'utf-8') > 20) {
							$htmlString .= '<div id="content">'. $tmpHtmlDOM2-> innertext . '</div>';
						}
						$tmpHtmlDOM2-> clear();
					}
					$tmpHtmlDOM-> clear();
				}				
				foreach($htmlClassArray as $item) {
					$tmpHtmlDOM = str_get_html($item); 
					$this-> delInsideIdClass($tmpHtmlDOM);
					$tmpHtmlDOM2 = str_get_html($tmpHtmlDOM-> innertext); 
					$tmpStr = preg_replace('/\s+/', '', trim($tmpHtmlDOM2-> plaintext));
					if(mb_strlen($tmpStr, 'utf-8') > 20) {
						$tmpStr= preg_replace('/[~|!|@|$|%|\^|_|\*|\(|\)|\.|-|\+]+/', '', $tmpStr);
						$tmpStr= preg_replace('/&nbsp;/', '', $tmpStr);	
						if(mb_strlen($tmpStr, 'utf-8') > 20) {
							$htmlString .= '<div class="content">'. $tmpHtmlDOM2-> innertext . '</div>';
						}
						$tmpHtmlDOM2-> clear();
					}
					$tmpHtmlDOM-> clear();
				}
				$exportHtml = str_get_html($htmlString);
				if($exportHtml) {
					$html_sha1 = sha1($exportHtml);
					$folder_name = self::$_repo_folder ."/". substr($html_sha1, 0, 2);
					$file_name = $folder_name . "/". substr($html_sha1, 2);
					if(!is_dir($folder_name)){
						if (!mkdir($folder_name)) {
							die('Failed to create folders...');
							return;
						}
					}
					if(!file_exists($file_name.".html")) {
						$file= fopen($file_name.".html", "w");
						fwrite($file, $exportHtml);
						fclose($file); 
					}
				}
			}
		}
	}
}
$modify= new ModifyHtmlContents();
$modify-> parse();

?>