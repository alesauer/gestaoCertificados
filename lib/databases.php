<?php

class Database
{
    private $_host = '127.0.0.1';
    private $_user = 'root';
    private $_password = '';
    private $_database = 'certificados';
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->_host, $this->_user, $this->_password, $this->_database);

        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }

        $this->conn->set_charset('utf8mb4');
    }

    public function query($sql)
    {
        $result = $this->conn->query($sql);

        if ($result === false) {
            die("Erro na query: " . $this->conn->error);
        }

        return $result;
    }

    public function escapeString($string)
    {
        return $this->conn->real_escape_string($string);
    }

    public function close()
    {
        $this->conn->close();
    }

    // Add this new method to get the last inserted ID
    public function getLastInsertId()
    {
        return $this->conn->insert_id;
    }
}