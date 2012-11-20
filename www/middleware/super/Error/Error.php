<?php
class Error extends Exception{
	static $TYPE_INVALID = array( "message" => "Type Invalid", "code"=> 201);
	static $API_INAVAILABLE = array( "message" => "Api Inavailable", "code"=> 202);
	
	
	public function __construct ($errorType, $extraMessage = "") {		
		parent::__construct($errorType["message"] . " :: " . $extraMessage);
	}
}