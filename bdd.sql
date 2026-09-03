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
