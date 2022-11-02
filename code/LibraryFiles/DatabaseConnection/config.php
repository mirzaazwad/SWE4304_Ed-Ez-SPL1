<?php

    interface Database{
        public function connect();
        public function get_connection();
        public function set_connection($username, $servername, $password,$database);
        public function performQuery($sql);
        public function fetch_results(&$record,$sql);
    }

    class MySQLDatabaseConnector implements Database{
        private $username;
        private $servername;
        private $password;
        private $database;
        private $connection;
        public function __construct($username, $servername, $password,$database){
            $this->username = $username;
            $this->servername = $servername;
            $this->password = $password;
            $this->database = $database;
            $this->connect();
        }

        public function connect(){
            $this->connection=new mysqli($this->servername, $this->username, $this->password, $this->database);
            if ($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            }
        }

        public function get_connection(){
            return $this->connection;
        }

        public function set_connection($username, $servername, $password,$database){
            $this->username = $username;
            $this->servername = $servername;
            $this->password = $password;
            $this->database = $database;
            $this->connect();
        }

        public function performQuery($sql){
            if(is_null($this->connection)){
                die("Connection with database failed");
            }
            return mysqli_query($this->connection,$sql);
        }

        public function fetch_results(&$record,$sql){
            $result=$this->performQuery($sql);
            $record=mysqli_fetch_assoc($result);
        }
        
    }
    
    // Create connection and check Connection
    try{
        $database=new MySQLDatabaseConnector("UserManager","localhost","12345678","user");
    }
    catch(Exception $e){
        die("Connection occurs: ".$e->getMessage());
    }

    
    ?>
