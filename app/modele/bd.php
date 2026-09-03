<?php

class PDO_Connexion
{
    private string $host;
    private string $dbname;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function getConnection(): PDO
    {
        if ($this->conn === null) {

            // Initialisation ici au lieu du constructeur
            $this->host = $_ENV['DB_HOST'];
            $this->dbname = $_ENV['DB_NAME'];
            $this->username = $_ENV['DB_USER'];
            $this->password = $_ENV['DB_PASS'];

            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
                $this->conn = new PDO($dsn, $this->username, $this->password);

                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }

        return $this->conn;
    }
}


?>