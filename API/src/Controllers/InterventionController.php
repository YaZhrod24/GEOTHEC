<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class InterventionController {

    // GET /interventions : Manager voit tout, Technicien ne voit que les siennes
    public static function getAll($user) {
        $db = Database::getConnection();

        if ($user->role === 'admin') {
            $sql = "SELECT i.*, c.raison_social AS client_nom, e.nom AS equipement_nom, emp.nom AS tech_nom, emp.prenom AS tech_prenom
                    FROM Intervention i
                    JOIN Equipement e ON i.id_equipement = e.id_equipement
                    JOIN Client c ON e.id_client = c.id_client
                    JOIN Employe emp ON i.id_employe = emp.id_employe
                    ORDER BY i.date_intervention ASC";
            $stmt = $db->query($sql);
            $interventions = $stmt->fetchAll();
        } else {
            $sql = "SELECT i.*, c.raison_social AS client_nom, c.adresse, c.ville, c.tel AS client_tel, e.nom AS equipement_nom
                    FROM Intervention i
                    JOIN Equipement e ON i.id_equipement = e.id_equipement
                    JOIN Client c ON e.id_client = c.id_client
                    WHERE i.id_employe = ?
                    ORDER BY i.date_intervention ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$user->id_employe]);
            $interventions = $stmt->fetchAll();
        }

        echo json_encode($interventions);
    }

    // POST /interventions : Réservé au manager
    public static function create($user) {
        if ($user->role !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Action réservée aux managers']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['desc_panne']) || empty($data['date_intervention']) || empty($data['id_equipement']) || empty($data['id_employe'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Champs obligatoires manquants']);
            return;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO Intervention (desc_panne, date_intervention, statut, id_equipement, id_employe) 
                              VALUES (?, ?, 'En attente', ?, ?)");
        $stmt->execute([
            $data['desc_panne'],
            $data['date_intervention'],
            $data['id_equipement'],
            $data['id_employe']
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Intervention créée', 'id' => $db->lastInsertId()]);
    }

    // PUT /interventions/{id}/status : Technicien met à jour le statut ou le rapport
    public static function updateStatus($id, $user) {
        $data = json_decode(file_get_contents('php://input'), true);
        $db = Database::getConnection();

        // Récupérer l'intervention
        $stmt = $db->prepare("SELECT * FROM Intervention WHERE id_intervention = ?");
        $stmt->execute([$id]);
        $intervention = $stmt->fetch();

        if (!$intervention) {
            http_response_code(404);
            echo json_encode(['error' => 'Intervention non trouvée']);
            return;
        }

        // Vérification des droits
        if ($user->role !== 'admin' && $intervention['id_employe'] != $user->id_employe) {
            http_response_code(403);
            echo json_encode(['error' => 'Cette intervention ne vous est pas assignée']);
            return;
        }

        // Règle du sujet : une intervention clôturée devient non modifiable pour le technicien
        if ($user->role !== 'admin' && $intervention['statut'] === 'Clôturée') {
            http_response_code(403);
            echo json_encode(['error' => 'Intervention clôturée : modification interdite']);
            return;
        }

        $statut = $data['statut'] ?? $intervention['statut'];
        $rapport = $data['rapport'] ?? $intervention['rapport'];
        $dateCloture = ($statut === 'Clôturée' && !$intervention['date_cloture']) ? date('Y-m-d H:i:s') : $intervention['date_cloture'];

        $update = $db->prepare("UPDATE Intervention SET statut = ?, rapport = ?, date_cloture = ? WHERE id_intervention = ?");
        $update->execute([$statut, $rapport, $dateCloture, $id]);

        echo json_encode(['message' => 'Statut mis à jour']);
    }
}