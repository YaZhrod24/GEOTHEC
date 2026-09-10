<?php

require_once __DIR__ . '/../app/controleur/ControleurPrincipal.php';

// Création du contrôleur principal
$controleur = new ControleurPrincipal();

// Lancement de l'application
$controleur->routeur();
