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

// Route publique : Connexion
if ($route === '/login' && $method === 'POST') {
    AuthController::login();
    exit;
}

// Vérification du Token JWT pour toutes les routes suivantes
$decoded = AuthMiddleware::authenticate();
$currentUser = $decoded->user;

// ==========================================
// ROUTES INTERVENTIONS
// ==========================================
if ($route === '/interventions' && $method === 'GET') {
    InterventionController::getAll($currentUser);
} elseif ($route === '/interventions' && $method === 'POST') {
    InterventionController::create($currentUser);
} elseif (preg_match('#^/interventions/(\d+)/status$#', $route, $matches) && $method === 'PUT') {
    InterventionController::updateStatus($matches[1], $currentUser);

// ==========================================
// ROUTES CLIENTS (CRUD)
// ==========================================
} elseif ($route === '/clients' && $method === 'GET') {
    ParcController::getClients($currentUser);
} elseif ($route === '/clients' && $method === 'POST') {
    ParcController::createClient($currentUser);
} elseif (preg_match('#^/clients/(\d+)$#', $route, $matches) && $method === 'PUT') {
    ParcController::updateClient($matches[1], $currentUser);
} elseif (preg_match('#^/clients/(\d+)$#', $route, $matches) && $method === 'DELETE') {
    ParcController::deleteClient($matches[1], $currentUser);

// ==========================================
// ROUTES ÉQUIPEMENTS (CRUD)
// ==========================================
} elseif ($route === '/equipements' && $method === 'GET') {
    ParcController::getEquipements($currentUser);
} elseif ($route === '/equipements' && $method === 'POST') {
    ParcController::createEquipement($currentUser);
} elseif (preg_match('#^/equipements/(\d+)$#', $route, $matches) && $method === 'PUT') {
    ParcController::updateEquipement($matches[1], $currentUser);
} elseif (preg_match('#^/equipements/(\d+)$#', $route, $matches) && $method === 'DELETE') {
    ParcController::deleteEquipement($matches[1], $currentUser);

// ==========================================
// ROUTE TECHNICIENS
// ==========================================
} elseif ($route === '/techniciens' && $method === 'GET') {
    ParcController::getTechniciens($currentUser);

// ==========================================
// ROUTE INCONNUE
// ==========================================
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route introuvable', 'uri' => $route]);
}