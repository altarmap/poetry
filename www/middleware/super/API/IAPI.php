<?php
interface IAPI {
	public function getMethod ();
	public function getParams ();
	public function setSearch ( $searchObj );
	public function setKeyword ( $keyword = "" );
	public function getKeyword ();
	public function setResult ( $reult = "" );
	public function export ();
	public function getURL ();
}
?>