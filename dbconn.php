<?php

class dbconn{
    public $conn;
    function __construct(){
        $this->conn= new mysqli('localhost','root','','cab');
        if ($this->conn->connect_error) {
            die("Connection Failed" . $this->conn->connect_error);
        }
    }
}