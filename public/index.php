<?php
// Configuration sécurisée du cookie de session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
    'httponly' => true,      // Interdit l'accès au cookie via JavaScript (Anti-XSS)
    'samesite' => 'Strict'   // Empêche l'envoi du cookie depuis d'autres sites (Anti-CSRF partiel)
]);

session_start();


// Expiration de la session après 30 minutes (1800 secondes) d'inactivité
$timeout = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    session_start();
}
// Mise à jour de l'activité à chaque clic
$_SESSION['last_activity'] = time();

// Génération d'un token CSRF global s'il n'existe pas déjà
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


define('ROOT', dirname(__DIR__));

include_once ROOT . '/config.php';

// Un root pour les liens (ex : css, js, images) et un autre pour les inclusions PHP
if (DEV === true) {
    // URL en local avec XAMPP
    define('URL', 'http://192.168.1.16/dossier');
} else {
    // URL en production (https://)
    define('URL', 'https://devweb.ddnsfree.com');
}

$env = parse_ini_file(ROOT . '/.env');

$_ENV = array_merge($_ENV, $env);


if (DEV === true) {
    // Mode Développement : On affiche tout
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Mode Production : On cache tout aux visiteurs
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0); // Désactive l'affichage, mais les erreurs sont quand même dans le fichier error.log du serveur
}


include_once ROOT . '/app/controleur/ControleurPrincipal.php';

// Autoload sécurisé
spl_autoload_register(function ($class) {
    // Sécurité : on empêche le Path Traversal
    $class = str_replace(['/', '\\', '.'], '', $class);

    $paths = [
        ROOT . "/app/controleur/$class.php",
        ROOT . "/app/modele/$class.php",
        ROOT . "/app/core/$class.php"
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$url = $_GET["url"] ?? "";
$front = new ControllerPrincipal();

// ============================= Page principal =============================

$front->add("", "Accueil", "index", true);
$front->add("([0-9]+)", "Accueil", "Chiffre", true);
$front->add("([a-zA-Z0-9_-]+)", "Accueil", "Lettre", false);

$front->run($url);