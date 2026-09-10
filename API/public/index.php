<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\InterventionController;
use App\Controllers\ParcController;
use App\Middleware\AuthMiddleware;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$baseDir = '/Ecole/GEOTECH/API/public';
$route = str_replace($baseDir, '', $uri);

// Route publique
if ($route === '/login' && $method === 'POST') {
    AuthController::login();
    exit;
}

// Sécurisation JWT
$decoded = AuthMiddleware::authenticate();
$currentUser = $decoded->user;

// Routes Interventions
if ($route === '/interventions' && $method === 'GET') {
    InterventionController::getAll($currentUser);
} elseif ($route === '/interventions' && $method === 'POST') {
    InterventionController::create($currentUser);
} elseif (preg_match('#^/interventions/(\d+)/status$#', $route, $matches) && $method === 'PUT') {
    InterventionController::updateStatus($matches[1], $currentUser);

// Routes Gestion du Parc & Techniciens
} elseif ($route === '/clients' && $method === 'GET') {
    ParcController::getClients($currentUser);
} elseif ($route === '/clients' && $method === 'POST') {
    ParcController::createClient($currentUser);
} elseif ($route === '/equipements' && $method === 'GET') {
    ParcController::getEquipements($currentUser);
} elseif ($route === '/equipements' && $method === 'POST') {
    ParcController::createEquipement($currentUser);
} elseif ($route === '/techniciens' && $method === 'GET') {
    ParcController::getTechniciens($currentUser);

} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route introuvable']);
}