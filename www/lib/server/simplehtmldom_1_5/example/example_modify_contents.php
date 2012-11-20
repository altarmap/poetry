<?php
// example of how to modify HTML contents
include('../simple_html_dom.php');

// get DOM from URL or file
//$html = file_get_html('http://www.google.com/');
$html = file_get_html('img.php');
//echo $html."<br/><br/><br/>";

/**
* $e is a object of dataType.
* outertext is attribute of $e
* outertext is print text of tag
*/
// remove all image
foreach($html-> find('img') as $e){  
    //echo $e->src //"1.jpg"
    //$e-> outertext = '';  //take off <tag>
    echo $e-> innertext;  //null
}
// replace all input
foreach($html-> find('input') as $e=> $value){
	//echo $e."==".$value;
	//echo gettype($value); //object
	//echo $value-> outertext; //<input type="text" value="test">input a  <input type="text">input b
    $value-> outertext = '<img src="1.jpg">';   //change <tag>
}
foreach($html-> find('div div') as $e){  
	//$e-> outertext= ""; //null
	$e-> innertext;	//<div id="first"></div>
}


foreach($html-> find('comment') as $e){
    //$e-> outertext= '';
}


/*
$a= "123 4567";
var_dump($a);

$a= array(1,2,3,4);
foreach($a as $value){
	echo $value."<br>";
}
*/
// dump contents
echo $html;
?>
