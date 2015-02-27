<?php

	class Request
	{
		
		public $url; // URL appellé par l'utilisateur
		public $page = 1;
		public $prefix = false;
		public $data = false;

		/**
		* Fonction principale
		**/

		function __construct() 
		{
			$this->url = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;
			if(isset($_GET['page'])) {
				if(is_numeric($_GET['page'])) {
					if($_GET['page'] > 0) {
						$this->page = round($_GET['page']);
					}
				}
			}
			/**
			* Permet de stocker les informations POST dans un nouveau objet
			* @param stdClass L'objet 
			**/
			if(!empty($_POST)) {
				$this->data = new stdClass();
				foreach ($_POST as $k => $v) {
					$this->data->$k = securise($v);
				}
			}
		}

	}