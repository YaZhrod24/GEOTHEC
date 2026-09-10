<?php

class ControleurPrincipal
{
    public function routeur()
    {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        switch ($url) {
            case '/':
                require_once __DIR__ . '/ControleurAccueil.php';
                break;

            default:
                require_once(__DIR__ . "/../vue/erreur/404.php");
        }
    }
}
