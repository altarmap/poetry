<?php
class Sha1Manage {
	protected $_xml_sha1;
	protected $_keyword_sha1;
	protected $_keyword_folder_name= "head";
	protected $_searchInfoXML;
	protected $_keywordXML;
	
	function __construct(){
	}
	function xmlSha1($xml= null){
		$xml_sha1= sha1($xml);
		$this-> _xml_sha1= $xml_sha1;
		$folder_name= substr($xml_sha1, 0, 2);
		$file_name= substr($xml_sha1, 2);
		if( !is_dir($folder_name) ){
			if( !mkdir($folder_name) ){
				die('Failed to create folders...');
				return;
			}
		}
		if( !file_exists($folder_name."/".$file_name.".xml") ) {
			$file= fopen($folder_name."/".$file_name.".xml", "w");
			if( !$file ){
				die('Failed to create files...');
				return;
			}
			fwrite($file, $xml);
			fclose($file); 
		}
	}
	function getKeywordSha1(){
		return $this-> _keyword_sha1;
	}
	function createKeywordFolder($keyword_folder_name= null){
		if( isset($keyword_folder_name) ){
			$this-> _keyword_folder_name= $keyword_folder_name;
		}
		if ( !mkdir($this-> _keyword_folder_name) ) {
			die('Failed to create folders...');
		}
	}
	function keywordSha1($keyword= null, $data= null, $keyword_folder_name= null){
		if( !is_dir($this-> _keyword_folder_name) ){
			$this-> createKeywordFolder($keyword_folder_name);
		}
		$this-> _keyword_sha1= sha1($keyword);
		$keyword_dir= $this-> _keyword_folder_name."/". $this-> _keyword_sha1.".xml";
		echo $keyword_dir."<br>";
		$searchInfoXML= new SimpleXMLElement('<root></root>');
		$this-> _searchInfoXML= $searchInfoXML;
		if( file_exists( $keyword_dir ) ){
			$xmlDOM= simplexml_load_file( $keyword_dir );
			$children= $xmlDOM-> children();
			//print_r($children); //"object"
			foreach($children as $key=> $value){
				//echo $item."===".$value;
				if($key == "searchID"){
					$searchInfoXML-> addChild("previousID", $value);
					$folder_name= substr($value, 0, 2);
					$file_name= substr($value, 2);
					$prSearch= simplexml_load_file($folder_name."/".$file_name.".xml");
					$resultSha1= $prSearch-> resultID;
				}
			}
			unlink($keyword_dir); //just delete file, folder still exist
		}/*
		if(!is_dir($this-> _keyword_folder_name)){
			echo "keywordfolder is non";
		}else{
			echo "keywordfolder is here";
		}*/
		
		$this-> xmlSha1($xml);
		//echo $this-> _data_sha1."<br>";
		if( $resultSha1 != $this-> _xml_sha1){
			$searchInfoXML-> addChild("resultID", $this-> _xml_sha1);
			//echo $searchInfoXML-> asXML();
			$this-> xmlSha1($searchInfoXML-> asXML());
			$searchInfoSha1= $this-> _xml_sha1;
			
			$keywordXML= new SimpleXMLElement('<root></root>');
			$this-> _keywordXML= $keywordXML;
			$keywordXML-> addChild("searchID", $searchInfoSha1);
			if( !file_exists($keyword_dir) ){
				$file= fopen($keyword_dir, "w");
				if( !$file ){
					die('Failed to create files...');
					return;
				}
				fwrite($file, $keywordXML-> asXML());
				fclose($file);
			}
		}
		
	}
}

$sha1Manage= new Sha1Manage();
$data= '<?xml version="1.0"?><root><item>bb</item><item>cc</item><</root>';
$keyword= "IBM";
//$sha1Manage-> dataSha1($data);
$sha1Manage-> keywordSha1($keyword, $data);

?>