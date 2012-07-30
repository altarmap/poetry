<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Modulize.php');
require_once(dirname(__FILE__).'\..\module\Login.php');
require_once(dirname(__FILE__).'\..\module\SearchBox.php');

class Header extends Modulize {
	public function __construct() {
		parent::__construct();
		$login= new Login;
		$SearchBox= new SearchBox;
		$this-> assign("Login", $login-> display("login.tpl"));
		$this-> assign("SearchBox", $SearchBox-> display("SearchBox.tpl"));
	}
	public function __toString() {
		return "Class::Header";
	}
}
?>