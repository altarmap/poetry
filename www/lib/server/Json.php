<?php
/**
 *  Copyright (C) 2010  Kai Dorschner
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Kai Dorschner <the-hide@address.com>
 * @copyright Copyright 2010, Kai Dorschner
 * @license http://www.gnu.org/licenses/gpl.html GPLv3
 * @package mocovi
 */

class Json extends DomDocument
{
	public function load($file)
	{
		$this->loadJson(file_get_contents($file));
	}

	public function loadJson($string)
	{
		$keys = array_keys($json = json_decode($string, true));
		$this->appendChild($this->createElement($keys[0]));
		$this->arrayToXmlNode($json[$keys[0]], $this->documentElement);
		echo $this->saveXML();
	}

	private function arrayToXmlNode(Array $array, DomNode $node)
	{
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$new = $this->createElement($key);
				$node->appendChild($new);
				$this->arrayToXmlNode($val, $new);
			}
			else
				$node->setAttribute($key, $val);
		}
	}
}

function debug($string)
{
	echo '<h3>debug</h3>';
	echo '<pre>';
	print_r($string);
	echo '</pre>';
}