<?php
require_once(dirname(__FILE__).'\..\..\middleware\supper\MWLib.php');
class Layout{
	public $tpl;
	protected $smarty= null;
	protected $mwLib= null;
	public function __construct(){
		$this-> mwLib= MWLib::getInstance();
		$this -> smarty = $this-> mwLib-> getPackage("Smarty");		
	}
	public function assign($attr, $value){
		$this -> smarty-> assign($attr, $value);
	}
}
?>