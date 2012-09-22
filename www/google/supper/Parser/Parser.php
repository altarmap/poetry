<?php
class Parser{
	//$json = new Json();		
		//$json-> loadJson( $result );		
		
		$collection = json_decode($result, true);
		$this-> _resultXMLString .= '<?xml version="1.0"?><root>';
		$this-> json2xml( $collection );
		$this-> _resultXMLString .= '</root>';
		echo $this-> _resultXMLString;
		if($this-> _parseRule) {
			$this-> _parseRule ($data);
		}
}
?>
