<?php
require_once "iModulize.php";
require_once(dirname(__FILE__).'\..\..\middleware\supper\MWLib.php');
class Modulize implements IModulize{
	protected $_mwLib;
	protected $_smarty;
	protected $_fetch;
	protected $tplURL;
	function __construct(){		
		$this-> _mwLib= MWLib::getInstance();
		$this -> smarty = $this-> _mwLib-> getPackage("Smarty");
		$this -> smarty -> addTemplateDir(dirname(__FILE__)."\..\..\module_templates");	
	}
	public function display($tpl){		
		$this -> _fetch = $this -> smarty -> fetch($tpl);
		return $this -> _fetch;
	}
	public function assign($parameter, $value){
		$this -> smarty -> assign($parameter, $value);
	}
}
?>
