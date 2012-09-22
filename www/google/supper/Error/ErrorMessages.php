<?php
class ErrorMessages {
	const TYPE_INVALID = "TYPE_INVALID";
	const API_INAVAILABLE = "API_INAVAILABLE";
	static protected $errorCodes = array(
		ErrorMessages::TYPE_INVALID => 101,
		ErrorMessages::API_INAVAILABLE => 102
	);
	static protected $errorMessages = array(
		ErrorMessages::TYPE_INVALID => "Type Invalid",
		ErrorMessages::API_INAVAILABLE => "Api Inavailable"
	);
	private function __construct () {}
	static public function getErrorCode($errorType) {
		return self::$errorCodes[$errorType];
	}
	static public function getErrorMsg($errorType) {
		return self::$errorMessages[$errorType];
	}
}
?>