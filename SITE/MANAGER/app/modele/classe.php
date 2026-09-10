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


    public function GetAllClasse()
    {
        $stmt = $this->db->prepare("SELECT * FROM classe");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetClasse(int $idClasse)
    {
        $stmt = $this->db->prepare("SELECT * FROM classe WHERE id_classe = :id_classe");
        $stmt->bindValue(':id_classe', $idClasse, PDO::PARAM_INT);
        $stmt->execute();
        $classe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($classe) {
            return $classe;
        } else {
            return null;
        }
    }

    public function AddClasse(string $nom)
    {
        $stmt = $this->db->prepare("INSERT INTO classe (nom) VALUES (:nom)");
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function ModifyClasse(int $idClasse, string $nom)
    {
        $stmt = $this->db->prepare("UPDATE classe SET nom = :nom WHERE id_classe = :id_classe");
        $stmt->bindValue(':id_classe', $idClasse, PDO::PARAM_INT);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function DeleteClasse(int $idClasse)
    {
        $stmt = $this->db->prepare("DELETE FROM classe WHERE id_classe = :id_classe");
        $stmt->bindValue(':id_classe', $idClasse, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
