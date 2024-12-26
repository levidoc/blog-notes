<?php

class Database_Services {

    private $host;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($host, $username, $password, $dbname) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
    }

    private function prevent_sql_injection($statement){
      #This Private Function will be used to prevent SQL injections in the system database 
    }

    private function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for error handling
            return true;
        } catch(PDOException $e) {
            // Log the error or handle it appropriately for your application
            error_log("Database connection failed: " . $e->getMessage()); 
            return false;
        }
    }

    public function create($table, $data) {
        if (!$this->connect()) return false; // Ensure connection

        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        try {
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Create operation failed: " . $e->getMessage());
            return false;
        }
    }


    public function read($table, $where = null) {
        if (!$this->connect()) return false;

        $sql = "SELECT * FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $where) {
        if (!$this->connect()) return false;

        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", "); // Remove trailing comma

        $sql = "UPDATE $table SET $setClause WHERE $where";
        $stmt = $this->conn->prepare($sql);

        try {
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Update operation failed: " . $e->getMessage());
            return false;
        }
    }


    public function delete($table, $where) {
        if (!$this->connect()) return false;

        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->conn->prepare($sql);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Delete operation failed: " . $e->getMessage());
            return false;
        }
    }


}
?>
