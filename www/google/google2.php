<?php
// Here's the Google AJAX Search API url for curl. It uses Google Search's site:www.yourdomain.com syntax to search in a specific site. I used $_SERVER['HTTP_HOST'] to find my domain automatically. Change $_POST['searchquery'] to your posted search query

$url = 'http://ajax.googleapis.com/ajax/services/search/web?rsz=large&v=1.0&start=20&q=' . urlencode('' . $_POST['searchquery']); 

// use fopen and fread to pull Google's search results
function queryGoogle($url){
	$handle = fopen($url, 'rb');
	$body = '';
	while (!feof($handle)) {
	$body .= fread($handle, 8192);
	}
	fclose($handle);
	return $body;
}

// 剖析陣列  
function arrayParser($array) {  
    $xml = null;  
    if(is_array($array)){  
        foreach($array as $key => $value) { 
			/*
			if(is_array($value)){  
                $xml .= '<'.$key.'>'."\n".arrayParser($value).'</'.$key.'>'."\n";  
            } else {  
                $xml .= ' <'.$key.'>'.$value.'</'.$key.'>'."\n";  
            }*/
			// 沒有空白和折行
			if(is_array($value)){  
                $xml .= '<'.$key.'>'.arrayParser($value).'</'.$key.'>';  
            } else {  
                $xml .= ' <'.$key.'>'.$value.'</'.$key.'>';  
            }
        }  
    }  
    return $xml;  
}


$queryResult = queryGoogle($url);
$array = json_decode($queryResult, true);

echo "[取得此次搜尋的結果之 sha1]<br>";
$xml = arrayParser($array);
$xml_sha1 = sha1($xml);
echo sha1($xml)."<br><br>";


echo "[取得xml_sha1的前兩位元]<br>";
$folder_name = substr($xml_sha1, 0, 2);
echo $folder_name."<br><br>";

if(!is_dir($folder_name)){
	if (mkdir($folder_name, 0, true)) {
		echo "建立folder_name資料夾: ". $folder_name. "<br>";
	}
	else {
		die('Failed to create folders...');
	}
}

echo "建立檔案: ". $folder_name.'/'.$xml_sha1.".txt<br>";
$xml_content = arrayParser($array);

$fp = fopen($folder_name.'/'.$xml_sha1.'.txt', 'w');
fwrite($fp, $xml_content);
fclose($fp);



exit;
?>