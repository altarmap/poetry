<?php
 /**
 * Example Application
 */
 //require(dirname(__FILE__).'\..\lib\server\smarty\Smarty.class.php');
 class SearchBox extends Modulize {
	protected static $instance= null;
	protected static $package= array();
	protected function __construct() {}
	public function getPackage($name) {
		if(self::$package[$name] == null){
			self::$package[$name]= new $name();
		}
		switch($name){
			case "Smarty":
				self::$package[$name]-> template_dir= dirname(__FILE__).'\..\templates';
				self::$package[$name]-> compile_dir= dirname(__FILE__).'\..\templates_c';
			break;		
		}
		return self::$package[$name];
	}
	public static function getInstance() {
		if(self::$instance == null){
			self::$instance= new SearchBox;
		}
		return self::$instance;
	}
 }

?>