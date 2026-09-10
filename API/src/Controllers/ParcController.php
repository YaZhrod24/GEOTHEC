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

    // --- CLIENTS ---

    public static function getClients($user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM Client ORDER BY raison_social ASC");
        echo json_encode($stmt->fetchAll());
    }

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

    // --- ÉQUIPEMENTS ---

    public static function getEquipements($user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->query("SELECT e.*, c.raison_social AS client_nom 
                            FROM Equipement e 
                            JOIN Client c ON e.id_client = c.id_client 
                            ORDER BY e.nom ASC");
        echo json_encode($stmt->fetchAll());
    }

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

    // --- LISTE DES TECHNICIENS (pour le formulaire de planification) ---

    public static function getTechniciens($user) {
        self::checkAdmin($user);
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id_employe, nom, prenom, email, tel FROM Employe WHERE role = 'technicien'");
        echo json_encode($stmt->fetchAll());
    }
}