CREATE DATABASE IF NOT EXISTS gestion_interventions;
USE gestion_interventions;

-- TABLE CLIENT
CREATE TABLE Client (
    id_client INT AUTO_INCREMENT PRIMARY KEY,
    raison_social VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    tel VARCHAR(50),
    adresse VARCHAR(255),
    cp VARCHAR(10),
    ville VARCHAR(255)
);

-- TABLE EMPLOYE
CREATE TABLE Employe (
    id_employe INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    mdp VARCHAR(255),
    tel VARCHAR(50),
    role VARCHAR(255)
);

-- TABLE EQUIPEMENT
CREATE TABLE Equipement (
    id_equipement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    type VARCHAR(255),
    num_serie VARCHAR(255),
    id_client INT NOT NULL,

    CONSTRAINT fk_equipement_client
        FOREIGN KEY (id_client)
        REFERENCES Client(id_client)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================
-- TABLE INTERVENTION
-- =========================
CREATE TABLE Intervention (
    id_intervention INT AUTO_INCREMENT PRIMARY KEY,
    desc_panne TEXT,
    date_intervention DATETIME NOT NULL,
    date_cloture DATETIME,
    statut VARCHAR(255),
    rapport TEXT,
    id_equipement INT NOT NULL,
    id_employe INT NOT NULL,

    CONSTRAINT fk_intervention_equipement
        FOREIGN KEY (id_equipement)
        REFERENCES Equipement(id_equipement)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_intervention_employe
        FOREIGN KEY (id_employe)
        REFERENCES Employe(id_employe)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- ==========================================
-- JEUX DE DONNÉES DE TEST (Clés & Relations)
-- ==========================================

INSERT INTO Client (id_client, raison_social, email, tel, adresse, cp, ville) VALUES
(1, 'TechCorp SAS', 'contact@techcorp.fr', '0140000001', '12 rue de la Paix', '75001', 'Paris'),
(2, 'BioSanté Lab', 'support@biosante.fr', '0467000002', '45 avenue des Cévennes', '34000', 'Montpellier');

INSERT INTO Employe (id_employe, nom, prenom, email, mdp, tel, role) VALUES
(1, 'Dupont', 'Jean', 'j.dupont@geotech.fr', '$2y$10$e8vK...hashAdmin', '0600000001', 'admin'),
(2, 'Martin', 'Sophie', 's.martin@geotech.fr', '$2y$10$e8vK...hashTech', '0600000002', 'technicien');

INSERT INTO Equipement (id_equipement, nom, type, num_serie, id_client) VALUES
(1, 'Serveur ProLiant DL380', 'Serveur Rack', 'SN-HPE-2026-001', 1),
(2, 'Switch Cisco 48p', 'Réseau', 'SN-CISCO-8899', 1),
(3, 'Station Dell Precision', 'Workstation', 'SN-DELL-5544', 2);

INSERT INTO Intervention (id_intervention, desc_panne, date_intervention, date_cloture, statut, rapport, id_equipement, id_employe) VALUES
(1, 'Disque dur HS sur baie RAID', '2026-09-01 09:30:00', '2026-09-01 11:45:00', 'Clôturée', 'Remplacement à chaud du disque 3 validé.', 1, 2),
(2, 'Perte alimentation port PoE', '2026-09-03 14:00:00', NULL, 'En cours', 'Diagnostic en cours sur le module d\'alimentation.', 2, 2);