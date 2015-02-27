<?php

	class Session
	{

		/**
		* Fonction principale
		* Permet d'initialiser la session si la session n'existe pas
		**/

		public function __construct()
		{
			if(!isset($_SESSION)) {
				session_start();
			}
		}

		/**
		* Permet de crée une variable session
		* @param $key La clé de la session
		* @param $value La valeur de la session
		**/

		public function set($key, $value = null) 
		{
			if(is_array($key)) {
				foreach ($key as $k => $value) {
					$_SESSION[$k] = $value;
				}
			} else {
				$_SESSION[$key] = $value;
			}
		}

		/**
		* Permet de récuperer le contenu d'une session
		* @param $key La clé de la session
		**/

		public function get($key = null)
		{
			if($key) {
				if(isset($_SESSION[$key])) {
					return $_SESSION[$key];
				} else {
					return false;
				}
			} else {
				return $_SESSION;
			}			
		}

		/**
		* Permet de savoir si un utilisateur es connecté
		**/

		public function isLogged()
		{
			return isset($_SESSION['User']->id);
		}

		/**
		* Permet de supprimer une session
		* @param $key La clé de la session
		*/

		public function delete($key =  null)
		{
			if($key) {
				if(is_array($key)) {
					foreach($key as $k) {
						unset($_SESSION[$k]);
					}
				} else {
					unset($_SESSION[$key]);
				}
			} else {
				session_destroy();
			}
		}

		/**
		* Permet de récupérer des informations utilisateur
		* @param $key La clé de la session
		**/

		public function user($key)
		{
			if($this->get('User')) {
				if(isset($this->get('User')->$key)) {
					return $this->get('User')->$key;
				} else {
					return false;
				}
			}
			return false;
		}

		/**
		* Permet d'envoyer un message flash
		* @param $message Message
		* @param $type Type du message (success, error)
		**/

		public function setFlash($message, $type = 'success')
		{
			$_SESSION['flash'] = array(
				'message' => $message,
				'type' => $type
			);
		}

		/**
		* Permet d'afficher le message si il existe
		**/

		public function flash()
		{
			if(isset($_SESSION['flash']['message'])) {
				$html = '<p class="'.$_SESSION['flash']['type'].'">'.$_SESSION['flash']['message'].'</p>';
				$_SESSION['flash'] = [];
				return $html;
			}
		}

	}