<?php

require_once(__DIR__ . "/bd.php");

class Classe extends PDO_Connexion
{
    private $db;

    public function __construct()
    {
        // Connexion PDO héritée de PDO_Connexion
        $this->db = $this->getConnection();
    }


    public function GetAllTable()
    {
        $stmt = $this->db->prepare("SELECT * FROM matable");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetTable(int $idTable)
    {
        $stmt = $this->db->prepare("SELECT * FROM matable WHERE id_table = :id_table");
        $stmt->bindValue(':id_table', $idTable, PDO::PARAM_INT);
        $stmt->execute();
        $table = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($table) {
            return $table;
        } else {
            return null;
        }
    }

    public function AddTable(string $nom)
    {
        $stmt = $this->db->prepare("INSERT INTO matable (nom) VALUES (:nom)");
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function ModifyTable(int $idTable, string $nom)
    {
        $stmt = $this->db->prepare("UPDATE matable SET nom = :nom WHERE id_table = :id_table");
        $stmt->bindValue(':id_table', $idTable, PDO::PARAM_INT);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function DeleteTable(int $idTable)
    {
        $stmt = $this->db->prepare("DELETE FROM matable WHERE id_table = :id_table");
        $stmt->bindValue(':id_table', $idTable, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
