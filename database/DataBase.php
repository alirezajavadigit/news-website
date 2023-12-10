<?php

namespace database;

use PDO;
use PDOException;

class DataBase{
    private $connection;
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

    private $dbHost = DB_HOST;
    private $dbUserName = DB_USERNAME;
    private $dbName = DB_NAME;
    private $dbPassword = DB_PASSWORD;

    function __construct(){
        try {
            $this->connection = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName, $this->dbUserName, $this->dbPassword, $this->options);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function select($sql, $values = null){
        try{
            $stmt = $this->connection->prepare($sql);
            if($values === null){
                $stmt->execute();
                $result = $stmt;
                return  $result;
            }else{
                $stmt->execute($values);
                $result = $stmt->fetchAll();
                return sizeof($result) === 1 ? $result[0] : $result;
            }
        }catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function insert($tableName, $fields, $values){
        try{
            $stmt = $this->connection->prepare("INSERT INTO " . $tableName . "(" . implode(", ", $fields) . " , created_at) VALUES (:" . implode(", :", $fields) . ", NOW() );");
            $stmt->execute(array_combine($fields, $values));
            return true;
        }catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
        // "UPDATE USERS SET name = ?, "
    public function update($tableName, $id, $fields, $values){
        try{
            $sql = "UPDATE " . $tableName . " SET ";
            foreach(array_combine($fields, $values) as $field => $value){
                if($value){
                    $sql .= " `" . $field . "` = ? ,";
                }else{
                    $sql .= " `" . $field . "` = NULL ,";
                }
            }

            $sql .= " updated_at = NOW()";
            $sql .= " WHERE id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(array_merge(array_filter(array_values($values)), [$id]));
            return true;
        }catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }


    }

    public function delete($tableName, $id){
        $sql = "DELETE FROM " . $tableName . " WHERE id = ?;";
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$id]);
            return true;
        }catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    function __destruct(){
        $this->connection = null;
        exit;
    }

}

