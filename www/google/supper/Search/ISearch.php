<?php
interface ISearch {
	function setKeyword ( $keyword = "" );
	function getKeyword ();
	function query ();	
}