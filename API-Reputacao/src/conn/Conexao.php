<?php

class conexao {

    public static $db;
    private $USERNAME = "root";
    private $PASSWORD = "";

    private $HOST = "localhost";
    private $DB = "reputacao_service";

    //private $USERNAME = "cloudidev";
   // private $PASSWORD = "tcc8522";

    //private $HOST = "localhost";
    //private $DB = "cloudide_liquida";

    public function getConexao() {
        if (!self::$db) {
            self::$db = $this->connect();
        }
        return self::$db;
    }

    private function connect() {
        $username = $this->USERNAME;
        $password = $this->PASSWORD;
        $host = $this->HOST;
        $db = $this->DB;
        try {
            $connection = new PDO("mysql:dbname=$db;host=$host;charset=utf8", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $connection;
    }

    public function fecharConexao() {
        if (self::$db) {
            self::$db = null;
        }
    }

    public function verificarConexao() {
        if (self::$db) {
            return "Conectado<br/>";
        } else {
            return "Conexão encerada<br/>";
        }
    }

}
