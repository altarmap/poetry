<?php
include('../simple_html_dom.php');
class ModifyHtmlContents {
	protected $_remove_tag= array("iframe", "img", "comment", "input", "script", "link", "button", "style", "object", "svg", "select", "textarea", "embed");
	protected $_folder= '../../../../../middleware/FileManager/source_code'; 
	protected $_dir_folder;
	
	function __construct(){
		$this-> getDirFolder();
	}
	public function getDirFolder(){
		return $this-> _dir_folder= dirname(__FILE__).$this-> _folder;
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
		$dir_folder = opendir($this-> _dir_folder); 
		while (($file = readdir($dir_folder)) !== false){
			if($file != "." && $file != ".."){
				$html= file_get_html($this-> _dir_folder."/".$file);				
				for ($i=0; $i< count($remove_tag); $i++){
					foreach ($html-> find($remove_tag[$i]) as $dom){
						$dom-> outertext= "";
					}
				}				
				/*for ($i=0; $i< count($keywords); $i++){
					foreach ($html-> find("[id*=".$keywords[$i]."]") as $dom) {
						$dom-> outertext = "";
					}
					foreach ($html-> find("[class*=".$keywords[$i]."]") as $dom) {
						$dom-> outertext = "";
					}
				}*/
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
					$folder_name = substr($html_sha1, 0, 2);
					$file_name = substr($html_sha1, 2);
					if(!is_dir($folder_name)){
						if (!mkdir($folder_name)) {
							die('Failed to create folders...');
							return;
						}
					}
					if(!file_exists($folder_name."/".$file_name.".html")) {
						$file= fopen($folder_name."/".$file_name.".html", "w");
						fwrite($file, $exportHtml);
						fclose($file); 
					}
				}
			}
		}
		closedir($dir_folder);		
	}
}
$modify= new ModifyHtmlContents();
$modify-> parse();

?>