<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Modulize.php');
class SearchBox  extends Modulize {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function __toString() {
		return "Class::SearchBox";
	}
}
?>