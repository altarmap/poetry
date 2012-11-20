<?php
interface ISearch {
	function setKeyword ( $keyword = "" );
	function getKeyword ();
	function setAPI ( $api = null );
	function query ();
}