<?php 
	class Db 
	{

		// Properties
		private $dbhost = 'localhost';
		private $dbuser = 'root';
		private $dbpass = '';
		private $dbname = 'Registration';
		private $charset = 'utf8';

		// Connect
		public function connect() 
		{
			$mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=$this->charset";
			$opt = [
        		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        		PDO::ATTR_EMULATE_PREPARES   => false,
    		];
			$dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass, $opt);

			return $dbConnection;
		}
	}
?>