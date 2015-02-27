<?php

	class Router
	{

		static $prefixes = [];

		static function prefix($url, $prefix)
		{
			self::$prefixes[$url] = $prefix;
		}

		/**
		* Permet de parser une url
		* @param $url URL à parser
		* @return tableau contenant les paramètres
		**/
		
		static function parse($url, $request)
		{
			$url = trim($url, '/');
			$params = explode('/', $url);
			if(in_array($params[0], array_keys(self::$prefixes))) {
				$request->prefix = self::$prefixes[$params[0]];
				array_shift($params);
			}
			$session = new Session();
			if($session->isLogged()) {
				$controller_default = 'Home';
			} else {
				$controller_default = 'Guest';
			}
			$request->controller = !empty($params[0]) ? $params[0] : $controller_default;
			$request->action = isset($params[1]) ? $params[1] : 'index';
			foreach (self::$prefixes as $k => $v) {
				if(strpos($request->action, $v.'_') === 0) {
					$request->prefix = $v;
					$request->action = str_replace($v.'_', '', $request->action);
				}
			}
			$request->params = array_slice($params, 2);
			return $request;
		}

	}