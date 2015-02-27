<?php
	
	/**
	* Permet de sécuriser une variable
	* @param $var Variable à sécuriser
	**/

	function securise($var)
	{
		return htmlentities($var);
	}