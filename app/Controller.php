<?php

	class Controller
	{
		
		public $layout = 'default'; // Template à utiliser pour rendre la vue
		public $request; // Objet request
		public $models; // Tableau pour ajouter plusieurs models
		public $Session;
		public $title = ''; // Titre de la page 
		public $isLogged;
		private $vars = []; // Variables à passer à la vue

		/**
		* Fonction principale
		* @param $request Objet Request de notre application
		**/

		function __construct($request = null)
		{
			$this->Session = new Session();
			if(isset($request)) {
				$this->request = $request; // On stock la requête dans l'instance
				require CONFIG.DS.'hook.php';
			}

			// Gestion des models 
			$models = $this->models;
			if(isset($models)) {
				if(is_string($models)) {
					$this->loadModel($models);
				} else {
					foreach($models as $model) {
						$this->loadModel($model);
					}
				}
			}

			// Verification de la session
			if(isset($this->isLogged)) {
				$this->isLogged($this->isLogged);
			}

		}

		/** 
		* Permet de charger une vue
		* @param $name Nom de la vue
		* @param $layout Permet de charger ou non un template
		**/

		function render($name = null, $layout = null)
		{
			if(!isset($name)) {
				$name = $this->request->action;
			}
			if(!$this->Session->isLogged()) {
				$this->layout = "guest";
			}
			extract($this->vars);
			ob_start();
			if(strpos($name, DS) === 0) {
				require ROOT.DS.'views'.$name.'.php';
			} else {
				require ROOT.DS.'views'.DS.strtolower($this->request->controller).DS.$name.'.php';
			}
			
			$content_for_layout = ob_get_clean();
			if($layout == true || !isset($layout)) {
				$title_for_layout = $this->title;
				require ROOT.DS.'views'.DS.'layout'.DS.$this->layout.'.php';
			} else {
				echo $content_for_layout;
			}
		}

		/**
		* Permet d'envoyer des variables à la vue
		* @param $key Clé du tableau
		* @param $value Valeur du tableau
		**/

		function set($key, $value = null)
		{
			if(is_array($key)) {
				$this->vars += $key;
			} else {
				$this->vars[$key] = $value;
			}
		}

		/**
		* Permet de charger un model
		* @param $name Nom du model
		**/

		function loadModel($name)
		{
			$file = ROOT.DS.'models'.DS.ucfirst($name).'.php';
			require_once($file);
			if(!isset($this->$name)) {
				$this->$name = new $name();
				$this->$name->Session = $this->Session;
				return $this->$name;
			}
		}

		/**
		* Permet de gérer les erreurs 404
		* @param $message Message d'erreur
		*/

		function e404($message = null)
		{
			header("HTTP/1.0 404 Not Found");
			if(!isset($message)) {
				$message = "Aucun";
			}
			$this->set('message', $message);
			$this->render(DS.(__FUNCTION__));
			die;
		}

		/**
		* Permet de charger un controller depuis une vue
		* Permet de charger une methode depuis une vue
		* @param $controller Nom du controller
		* @param $action Nom de la methode
		* @return Retourne l'action
		**/

		function request($controller, $action)
		{
			$controller = $controller.'Controller';
			require_once(ROOT.DS.'controllers'.DS.$controller.'.php');
			$c = new $controller();
			return $c->$action();
		}


		/**
		* Permet de faire une redirection si l'utilisateur est connecté ou non
		**/

		function isLogged($param) 
		{
			switch ($param) {
				case 'true':
					if(!$this->Session->isLogged()) {
						$this->redirect("guest");
					}
				break;		
				case 'false':
					if($this->Session->isLogged()) {
						$this->redirect("home");
					}
				break;
				default:
					return false;
				break;
			}
		}
		/**
		* Permet d'effectuer une redirection
		* @param $url Lien de redirection
		* @param $code Code http protocole
		**/

		function redirect($url, $code = null)
		{
			if($code == 301) {
				header("HTTP/1.1 301 Moved Permanently");
			}
			header("Location: ".BASE_URL.DS.$url);
		}

	}