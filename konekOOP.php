<?php

class DatabaseConnection
{
    private $host;
    private $database;
    private $username;
    private $password;
    private $connection;

    public function __construct($host = "LAPTOP-1587EARF" , $database = "bebastanggungan", $username = "", $password = "")
    {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect()
    {
        $connectionInfo = array(
            "Database" => $this->database,
            "UID" => $this->username,
            "PWD" => $this->password
        );

        $this->connection = sqlsrv_connect($this->host, $connectionInfo);

        if ($this->connection === false) {
            throw new Exception("Koneksi gagal: " . print_r(sqlsrv_errors(), true));
        }

        return $this->connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function close()
    {
        if ($this->connection) {
            sqlsrv_close($this->connection);
        }
    }
}

// Contoh penggunaan
// try {
//     $db = new DatabaseConnection("LAPTOP-1587EARF", "bebastanggungan", "", "");
//     $conn = $db->connect();
//     echo "Koneksi berhasil.<br />";

//     // Gunakan koneksi $conn untuk query SQL, dsb.

//     $db->close();
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

?>
