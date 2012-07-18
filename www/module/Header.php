<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Modulize.php');

class Header extends Modulize {
	public function __construct() {
		parent::__construct();
		//$searchBox= new SearchBox;
		$this-> assign("SearchBox", "afewwf");
	}
	public function __toString() {
		return "Class::Header";
	}
}
?>