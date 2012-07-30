<?php
require_once(dirname(__FILE__).'\..\middleware\supper\Modulize.php');
class SearchBox  extends Modulize {
	protected static $package= array();
	
	public function __construct() {
		parent::__construct();
		//$this-> assign("SearchBox", "afewwf");
	}
	
	public function getPackage($name) 
	{
		if(self::$package[$name] == null)
		{
			self::$package[$name]= new $name();
		}
		switch($name)
		{
			case "Smarty":
				self::$package[$name]-> template_dir= dirname(__FILE__).'\..\module_templates';
				self::$package[$name]-> compile_dir= dirname(__FILE__).'\..\templates_c';
			break;		
		}
		return self::$package[$name];
	}
}

?>