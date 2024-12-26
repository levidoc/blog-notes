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

        @$this->connect()
    }

    private function error_log($error_)
    {
        try {
            $FileHandle = fopen($this->error_file, 'a+');
            $date = date("m/d/Y");
            $errorMessage = $error_;
            $curlError = $date . ' Error: ' . $errorMessage . "\n\n";
            fwrite($FileHandle, $curlError);
            return fclose($FileHandle);
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    private function prevent_sql_injection($statement){
      #This Private Function will be used to prevent SQL injections in the system database 
           try {
                $username = $this->username; 
                $password = $this->password ; 
                $host = $this->host; 
                $dbname = $this->dbname; 
                $pdo = new PDO('mysql:host=$host;dbname=$dbname',$username,$password); 
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
                $connection = $pdo; 
                return $connection->quote($statement);
            } catch (\Throwable $th) {
                $this->error_log($th); 
                return null; 
            }
    }

function detect_sql_injection($statement){
    try {
        $username = $this->username; $password = $this->password ; $host = $this->host; $dbname = $this->dbname;
        $pdo = new PDO('mysql:host=$host;dbname=$dbname',$username,$password); 
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $connection = $pdo; 
        $safe_code = $connection->quote($statement);
        if (strcmp($safe_code,$statement)){
            return TRUE; 
        }
    } catch (\Throwable $th) {
        $this->error_log($th); 
        return null; 
    }
    return FALSE; 
}

    private function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for error handling
            return true;
        } catch(PDOException $e) {
            // Log the error or handle it appropriately for your application
            $this->error_log("Database connection failed: " . $e->getMessage()); 
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
            $this->error_log("Create operation failed: " . $e->getMessage());
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
            $this->error_log("Update operation failed: " . $e->getMessage());
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
            $this->error_log("Delete operation failed: " . $e->getMessage());
            return false;
        }
    }


}
?>
