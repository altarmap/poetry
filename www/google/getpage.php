<?php
$keyword= $_POST['searchquery'];
$keyword_sha1 = sha1($keyword);
$keyword_folder_name = "head";
$keyword_file_name = $keyword_sha1;
$xml_folder_name = '';
$xml_file_name = '';
$searchinfo_folder_name = '';
$searchinfo_file_name = '';
$urlXML = new SimpleXMLElement('<root></root>');
$repeater = $urlXML -> addChild('repeater');


if(file_exists($keyword_folder_name.'/'.$keyword_sha1.'.xml') == true) {
	$keyword_xml = simplexml_load_file($keyword_folder_name.'/'.$keyword_sha1.'.xml');	
	$children = $keyword_xml -> children();

	foreach($children as $key => $value) {
		if($key == 'lastsearchinfo') {
			$searchinfo_sha1 = $value;
			$searchinfo_folder_name = substr($searchinfo_sha1, 0, 2);
			$searchinfo_file_name = substr($searchinfo_sha1, 2);
		}
	}
	
	if(file_exists($searchinfo_folder_name.'/'.$searchinfo_file_name.'.xml') == true) {
		$searchinfo_xml = simplexml_load_file($searchinfo_folder_name.'/'.$searchinfo_file_name.'.xml');		
		$children_ = $searchinfo_xml -> children();
		
		foreach($children_ as $key => $value) {
			if($key == 'resultsha1') {
				$xml_sha1 = $value;
				$xml_folder_name = substr($xml_sha1, 0, 2);
				$xml_file_name = substr($xml_sha1, 2);
				//echo $xml_folder_name. "/". $xml_file_name. ".xml";
			}
		}
	}
}


if(file_exists($xml_folder_name.'/'.$xml_file_name.'.xml') == true) {
	$xml = simplexml_load_file($xml_folder_name.'/'.$xml_file_name.'.xml');
	$children = $xml -> children();
	
	foreach($children as $key => $value) {
		if($key == 'repeater') {
			$items = $value;
			foreach($items as $key => $value) {
				echo $key. ' ==> '. $value. '<br>';
				$item = $repeater -> addChild($key);
				$element = $value;
				foreach($element as $key => $value) {
					if($key == 'url') {
						$url = $value;
						//echo $url. '<br>';
						
						// 取得url 的 content
						try {
							/*$ch = curl_init();
							curl_setopt ($ch, CURLOPT_URL, $url);
							curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
							curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
							$cotnent = curl_exec($ch); 
							curl_close($ch); */

							$cotnent = file_get_contents($url);
							$content_sha1 = sha1($cotnent);
							//$item = $repeater -> addChild('item');
							$item -> addChild('url', $url);
							$item -> addChild('contentsha1', $content_sha1);
							//echo $content_sha1. '<br>';
							
							// 產生url content sha1檔案
							$url_folder_name = substr($content_sha1, 0, 2);
							$url_file_name = substr($content_sha1, 2);
					
							$filepath = $url_folder_name. '/'. $url_file_name. '.html';

							if(!file_exists($filepath)) {
								if(!is_dir($url_folder_name)) {
									if (!mkdir($url_folder_name, 0, true)) {
										die('Failed to create folders...');
										return;
									}
								}
								$fp = fopen($filepath, 'w') or die("can't open file");
								fwrite($fp, $cotnent);
								fclose($fp);
							}
						}						
						catch (Exception $exception){
							//$exception->Display();
						}
					}
				}
				$urlXNL_sha1 = sha1($urlXML);
				$urlXNL_folder_name = substr($urlXNL_sha1, 0, 2);
				$urlXNL_file_name = substr($urlXNL_sha1, 2);
				
				if(!file_exists($urlXNL_folder_name. '/'. $urlXNL_file_name. '.xml')) {
					if(!is_dir($urlXNL_folder_name)) {
						if (!mkdir($urlXNL_folder_name, 0, true)) {
							die('Failed to create folders...');
							return;
						}
					}
					$urlXML -> asXML($urlXNL_folder_name. '/'. $urlXNL_file_name. '.xml');
					echo '<br>'.$urlXNL_folder_name. '/'. $urlXNL_file_name. '.xml';
				}
			}
		}
	}
}








/*$repeater = $xml -> getName("root") -> getName("repeater");
echo $repeater;
foreach($repeater as $key => $value) {
	echo $key. "=====". $value. "<br>";
}*/



?>