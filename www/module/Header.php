<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Modulize.php');
require_once(dirname(__FILE__).'\SearchBox.php');

class Header extends Modulize {
	public function __construct() {
		parent::__construct();

		$SearchBox = new SearchBox();
		//$SearchBox= SearchBox::getInstance();
		$smarty= $SearchBox->getPackage('Smarty');
		$smarty -> assign('logoTip', 'Header');
		$smarty -> assign('SearchBox', $smarty->display('SearchBox.tpl'));
	}
	public function __toString() {
		return "Class::Header";
	}
}
?>