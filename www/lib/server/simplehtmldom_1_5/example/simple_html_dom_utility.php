<?php
include_once('../simple_html_dom.php');

// -----------------------------------------------------------------------------
// remove HTML comments
function html_no_comment($url) {
    // create HTML DOM
    $html = file_get_html($url);

    // remove all comment elements
    foreach($html->find('comment') as $e)
        $e-> outertext= '';

    $ret = $html->save();

    // clean up memory
    $html->clear();
    unset($html);

    return $ret;
}

// -----------------------------------------------------------------------------
// search elements that contains an specific text
function find_contains($html, $selector, $keyword, $index=-1) {
    $ret = array();
    foreach ($html->find($selector) as $e) {
        if (strpos($e->innertext, $keyword)!==false)
            $ret[] = $e;
    }

    if ($index<0) return $ret;
    return (isset($ret[$index])) ? $ret[$index] : null;
}
function find_dom_byClassName ($html, $keyword) {
	$ret = array();
	foreach ($html-> find('span[class*='.$keyword.']') as $dom) {
		$dom-> outertext = "";
	}
}

$str = <<<HTML
<div id="mainheader1">
    <li>item:<span>1</span></li>
    <li>item:<span>2</span></li>
</div>
<div id="ul2">
    <li>item:<div id="header">3</div></li>
    <li>item:<span class="ewfgqwegewgtestegeqge">4</span></li>
</div>
HTML;

$html = str_get_html($str);
find_dom_byClassName($html, 'test');
echo $html;
?>