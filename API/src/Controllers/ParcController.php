<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class ParcController {

    // Sécurité : réservé aux managers
    private static function checkAdmin($user) {
        if ($user->role !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès réservé au manager']);
            exit;
        }
    }

    // ==========================================
    // CLIENTS (CRUD)
    // ==========================================

    // LECTURE : Tous les clients
    public static function getClients($user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM Client ORDER BY raison_social ASC");
        echo json_encode($stmt->fetchAll());
    }

    // CRÉATION : Ajouter un client
    public static function createClient($user) {
        self::checkAdmin($user);
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['raison_social'])) {
            http_response_code(400);
            echo json_encode(['error' => 'La raison sociale est requise']);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Client (raison_social, email, tel, adresse, cp, ville) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['raison_social'],
            $data['email'] ?? null,
            $data['tel'] ?? null,
            $data['adresse'] ?? null,
            $data['cp'] ?? null,
            $data['ville'] ?? null
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Client créé', 'id_client' => $db->lastInsertId()]);
    }

    // MODIFICATION : Mettre à jour un client
    public static function updateClient($id, $user) {
        self::checkAdmin($user);
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['raison_social'])) {
            http_response_code(400);
            echo json_encode(['error' => 'La raison sociale est requise']);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Client SET raison_social = ?, email = ?, tel = ?, adresse = ?, cp = ?, ville = ? WHERE id_client = ?");
        $stmt->execute([
            $data['raison_social'],
            $data['email'] ?? null,
            $data['tel'] ?? null,
            $data['adresse'] ?? null,
            $data['cp'] ?? null,
            $data['ville'] ?? null,
            $id
        ]);

        echo json_encode(['message' => 'Client mis à jour']);
    }

    // SUPPRESSION : Supprimer un client
    public static function deleteClient($id, $user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM Client WHERE id_client = ?");
        $stmt->execute([$id]);

        echo json_encode(['message' => 'Client supprimé']);
    }

    // ==========================================
    // ÉQUIPEMENTS (CRUD)
    // ==========================================

    // LECTURE : Tous les équipements
    public static function getEquipements($user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->query("SELECT e.*, c.raison_social AS client_nom 
                            FROM Equipement e 
                            JOIN Client c ON e.id_client = c.id_client 
                            ORDER BY e.nom ASC");
        echo json_encode($stmt->fetchAll());
    }

    // CRÉATION : Ajouter un équipement
    public static function createEquipement($user) {
        self::checkAdmin($user);
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nom']) || empty($data['id_client'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nom et id_client obligatoires']);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Equipement (nom, type, num_serie, id_client) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nom'],
            $data['type'] ?? null,
            $data['num_serie'] ?? null,
            $data['id_client']
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Équipement créé', 'id_equipement' => $db->lastInsertId()]);
    }

    // MODIFICATION : Mettre à jour un équipement
    public static function updateEquipement($id, $user) {
        self::checkAdmin($user);
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nom']) || empty($data['id_client'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nom et id_client obligatoires']);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE Equipement SET nom = ?, type = ?, num_serie = ?, id_client = ? WHERE id_equipement = ?");
        $stmt->execute([
            $data['nom'],
            $data['type'] ?? null,
            $data['num_serie'] ?? null,
            $data['id_client'],
            $id
        ]);

        echo json_encode(['message' => 'Équipement mis à jour']);
    }

    // SUPPRESSION : Supprimer un équipement
    public static function deleteEquipement($id, $user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM Equipement WHERE id_equipement = ?");
        $stmt->execute([$id]);

        echo json_encode(['message' => 'Équipement supprimé']);
    }

    // ==========================================
    // TECHNICIENS (Pour formulaire d'intervention)
    // ==========================================

    public static function getTechniciens($user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id_employe, nom, prenom, email, tel FROM Employe WHERE role = 'technicien'");
        echo json_encode($stmt->fetchAll());
    }
}