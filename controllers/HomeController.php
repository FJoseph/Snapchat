<?php
	
	class HomeController extends Controller
	{

		var $isLogged = 'true';

		/**
		* Fonction principale
		**/

		public function index()
		{
			$this->render();
		}

	}