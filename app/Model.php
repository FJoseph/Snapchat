<?php

	class Model
	{
	
		public $conf = 'default'; // Base de données par défaut
		public $table = false; // Table à affecter
		public $db; // Instance base de données
		public $primaryKey = 'id'; // Clé primaire
		public $id; // Clé primaire after Update or Save
		public $errors = []; // Les erreurs stocké
		static $connections = array();

		/**
		* Fonction principale
		* Permet la connexion à la base de données
		**/

		public function __construct()
		{
			if($this->table === false) {
				$this->table = strtolower(get_class($this)).'s';
			}
			$conf = Conf::$databases[$this->conf]; // Récupére les informations dans le fichier conf
			if(isset(self::$connections[$this->conf])) {
				$this->db = self::$connections[$this->conf];
				return true;
			}
				try {
					$pdo = new PDO("mysql:host={$conf['host']};dbname={$conf['database']}", 
						$conf['username'], 
						$conf['password'], 
						array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
					self::$connections[$this->conf] = $pdo;
					$this->db = $pdo;
				} catch(PDOException $e) {
					die("Erreur MySQL: ".$e->getMessage());
				}
		}

		/**
		* Permet de valider les données 
		* @param $data Les données à verifier
		**/

		public function validates($data)
		{
			$errors = [];
			foreach($this->validate as $k => $v) {
				if(!isset($data->$k)) {
					$errors[$k] = $v['message'];
				} else {
					if($v['rule'] == 'notEmpty') {
						if(empty($data->$k)) {
							$errors[$k] = $v['message'];
						}
					}
				}
			}
			$this->errors = $errors;
			if(empty($errors)) {
				return true;
			} 
			return false;
		}

		/** 
		* Permet d'insérer ou de modifier un élément dans la base de données
		**/

		public function save($data)
		{
			$key = $this->primaryKey;
			$fields = array();
			$d = [];
			foreach ($data as $k => $v) {
				if($k != $key) {
					$fields[] .= $k.'=:'.$k;
					$d[":$k"] = $v;
				} else if(!empty($v)) {
					$d[":$k"] = $v;
				}
				
			}
			if(isset($data->$key) && !empty($data->$key)) {
				$sql = 'UPDATE '.$this->table.' SET '.implode(',', $fields).' WHERE '.$key.' =:'.$key;
				$this->id = $data->$key;
				$action = 'update';
			} else {
				$sql = 'INSERT INTO '.$this->table.' SET '.implode(',', $fields);
				$action = 'insert';
			}
			$pre = $this->db->prepare($sql);
			$pre->execute($d);
			if($action === 'insert') {
				$this->id = $this->db->lastInsertId();
			}
			return true;
		}

		/**
		* Permet de sélectionner des éléments dans la base de données
		* @param $req Requête SQL
		**/

		public function find($req = null)
		{
			$sql = 'SELECT ';
			if(isset($req['fields'])) {
				if(is_array($req['fields'])) {
					$sql .= implode(', ', $req['fields']);
				} else {
					$sql .= $req['fields'];
				}
			} else {
				$sql .= '*';
			}

			$sql .= ' FROM '.$this->table.' as '.get_class($this).' ';

			if(isset($req['conditions'])) {
				$sql .= 'WHERE ';
				if(!is_array($req['conditions'])) {
					$sql .= $req['conditions'];
				} else {
					$cond = array();
					foreach($req['conditions'] as $k => $v) {
						if(!is_numeric($v)) {
							$v = '"'.mysql_escape_string($v).'"';
						}
						$cond[] = "$k = $v";
					}
					$sql .= implode(' AND ', $cond);
				}
			}

			if(isset($req['order'])){
				$sql .= ' ORDER BY '.$req['order'];
			}

			if(isset($req['limit'])) {
				$sql .= 'LIMIT '.$req['limit'];
			}
			$pre = $this->db->prepare($sql);
			$pre->execute();
			return $pre->fetchAll(PDO::FETCH_OBJ);
		}

		/**
		* Permet de sélectionner le premier élément dans la base de données
		* @param $req Requête SQL
		**/

		public function findFirst($req = null)
		{
			return current($this->find($req));
		}

		/**
		* Permet de sélectionner le dernier élément dans la base de données
		* @param $req Requête SQL
		**/

		public function findLast($req = null)
		{
			return end($this->find($req));
		}

		/**
		* Permet de compter des éléments dans la base de données
		* @param $conditions Conditions SQL
		**/

		public function findCount($conditions = null) 
		{
			$res = $this->findFirst(array(
				'fields' => 'COUNT('.$this->primaryKey.') as count',
				'conditions' => $conditions
			));
			return $res->count;
		}

		/**
		* Permet de supprimer des éléments dans la base de données 
		* @param $conditions Conditions SQL
		*/

		public function delete($req)
		{
			$sql = 'DELETE FROM '.$this->table.' ';
			if(isset($req['conditions'])) {
				$sql .= 'WHERE ';
				if(!is_array($req['conditions'])) {
					$sql .= $req['conditions'];
				} else {
					$cond = array();
					foreach($req['conditions'] as $k => $v) {
						if(!is_numeric($v)) {
							$v = '"'.mysql_escape_string($v).'"';
						}
						$cond[] = "$k = $v";
					}
					$sql .= implode(' AND ', $cond);
				}
			}
			$pre = $this->db->prepare($sql);
			$pre->execute();
		}

	}