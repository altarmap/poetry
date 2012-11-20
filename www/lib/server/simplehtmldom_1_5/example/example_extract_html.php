<?php
include_once('../simple_html_dom.php');

// extract text from HTML
echo file_get_html('crown2012.html')-> plaintext; //從網頁中，將"純文字"截取出來，沒有任何<tag>
?>