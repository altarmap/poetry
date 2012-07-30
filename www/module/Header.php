<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Modulize.php');
require_once(dirname(__FILE__).'\SearchBox.php');

class Header extends Modulize {
	public function __construct() {
		parent::__construct();

		require_once(dirname(__FILE__).'\..\middleware\supper\MWLib.php');
		$MWLib= MWLib::getInstance();
		$smarty= $MWLib->getPackage("Smarty");
		$SearchBox = new SearchBox();
		$smarty -> assign('SearchBox', $smarty->display('SearchBox.tpl'));
	}
	public function __toString() {
		return "Class::Header";
	}
}
?>