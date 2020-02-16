<?php

	class Database {

    private static $db;
    private $connection;

		const FETCH_OBJ = \PDO::FETCH_OBJ;
		const FETCH_COLUMN = \PDO::FETCH_COLUMN;
		const FETCH_ASSOC = \PDO::FETCH_ASSOC;

	    private function __construct() {
			if ( IS_LOCAL ) {
				$this->connection = new \PDO(
					"mysql:dbname=formulario;host=localhost;port=3306;charset=UTF8",
					'root',
					''
				);
			}
			else {
				$this->connection = new \PDO(
					"mysql:dbname=formulario;host=192.185.176.210;port=3306;charset=UTF8",
					'Banco',
					'Senha'
				);
			}
	    }

	    function __destruct() {}

	    public static function getConnection() {
	        if (self::$db == null) {
	            self::$db = new Database();
	        }
	        return self::$db->connection;
	    }
	}
