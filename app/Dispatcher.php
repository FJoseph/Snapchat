<?php
	
	/**
	* Dispatcher
	* Permet de charger le controller en fonction de la requête utilisateur
	**/

	class Dispatcher
	{

		var $request; // Objet request

		/**
		* Fonction principale du dispatcher
		* Charge le controller en fonction du routing
		**/

		function __construct()
		{
			$this->request = new Request();
			Router::parse($this->request->url, $this->request);
			$controller = $this->loadController();
			$action = $this->request->action;
			if($this->request->prefix) {
				$action = $this->request->prefix.'_'.$action;
			}
			if(!is_array(get_class_methods($controller)) || !in_array($action, array_diff(get_class_methods($controller), 
				get_class_methods('Controller')))) {
				$this->error('Le controller '.$this->request->controller.' n\'a pas de méthode '.$action);
			}
			call_user_func_array(array($controller, $action), $this->request->params);
		}

		/**
		* Permet de générer une page d'erreur en cas de problème au niveau du routing (page 404)
		**/

		function error($message)
		{
			$controller = new Controller($this->request);
			$controller->e404($message);
		}

		/**
		* Permet de charger le controller en fonction de la requête utilisateur
		**/

		function loadController()
		{
			$name = ucfirst($this->request->controller).'Controller';
			$file = ROOT.DS.'controllers'.DS.$name.'.php';
			if(file_exists($file)) {
				require $file;
				$controller = new $name($this->request);
				return $controller;
			} else {
				$this->error('Le controller '.$this->request->controller.' n\'existe pas.');
			}
		}

	}