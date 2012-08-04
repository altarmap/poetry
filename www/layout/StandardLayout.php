<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Layout.php');
require_once(dirname(__FILE__).'\..\module\Header.php');
class StandardLayout extends Layout{
	public function __construct(){
		parent::__construct();
		$this-> tpl= "StandardLayout.tpl";
		$header= new Header;
		$this-> assign('_Module_Header', $header-> display('Header.tpl'));		
	}
}
?>