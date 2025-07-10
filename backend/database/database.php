<?php

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $dbname = getenv('DB_NAME') ?: 'sistema_reservas';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: 'root';
        $port = getenv('DB_PORT') ?: '3308';
        $charset = 'utf8mb4';
        
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;port=$port";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}