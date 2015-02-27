<?php

	class GuestController extends Controller
	{

		var $isLogged = 'false';

		/**
		* Page principale
		**/

		public function index()
		{
			$this->render((__FUNCTION__), false);
		}

		/**
		* Page de connexion
		**/

		public function login()
		{
			$this->title = "Connexion";
			$this->render();
		} 

		/**
		* Page d'inscription
		**/

		public function register()
		{
			$this->title = "Inscription";
			$this->render();
		}

		/**
		* Page mot de passe oublié
		**/

		public function forgot()
		{
			$this->title = "Mot de passe oublié";
			$this->render();
		}

	}