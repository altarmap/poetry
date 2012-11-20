<?php
require_once(dirname(__FILE__).'\..\..\middleware\supper\MWLib.php');
class Altarmap{
	protected static $instance= null;
	protected $smarty= null;
	protected $mwLib= null;
	protected $content= null;
	public $layout= null;	
	protected function __construct() {
		$this-> mwLib= MWLib::getInstance();
		$this-> smarty= $this-> mwLib-> getPackage("Smarty");
	}
	public function setContent($tpl){	
		$this-> content= $tpl;		
	}
	public function assign($attr, $value) {
		$this-> smarty-> assign($attr, $value);
	}
	public static function getInstance(){
		if(self::$instance == null){
			self::$instance= new Altarmap;
		}
		return self::$instance;
	}
	public function display(){
		$this-> assign('placeHolder', $this-> smarty-> fetch($this-> content));
		$this-> smarty-> addTemplateDir(dirname(__FILE__).'\..\..\layout_templates');
		$this-> smarty-> display($this-> layout-> tpl);
	}
}
?>