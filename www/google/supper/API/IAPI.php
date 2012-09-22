<?php
interface IAPI {
	public function parseResult( $result = "" );	
	public function getMethod ();
	public function getParams ();
	public function setKeyword ( $keyword = "" );
	public function getURL ();
	public function isMore ( $result = "" );
}
?>