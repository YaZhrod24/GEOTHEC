<?php

class ControllerPrincipal
{
    private array $routes = [];

    public function add(string $pattern, string $controller, string $method, bool $loader = false)
    {
        $this->routes[$pattern] = [$controller, $method, $loader];
    }

    private function error404()
    {
        http_response_code(404);
        include_once ROOT . '/app/controleur/Erreur.php';
        $ctrl = new Erreur();
        $ctrl->view404();
    }

    private function error500(string $message)
    {
        http_response_code(500);

        if (defined("DEV") && DEV === true) {
            echo "<h1>Erreur interne</h1>";
            echo "<p>$message</p>";
            exit;
        }

        include_once ROOT . '/app/controleur/Erreur.php';
        $err = new Erreur();
        $err->view500();
        exit;
    }



    public function run(string $url)
    {
        $url = trim($url, "/");

        foreach ($this->routes as $pattern => $controllerInfo) {

            if (preg_match("#^$pattern$#", $url, $matches)) {

                [$controllerClass, $method, $loader] = $controllerInfo;

                define('AFFICHER_LOADER', $loader);

                if (!class_exists($controllerClass)) {
                    return $this->error500("Contrôleur introuvable : $controllerClass");
                }

                $controller = new $controllerClass();

                if (!method_exists($controller, $method)) {
                    return $this->error500("Méthode introuvable : $method dans $controllerClass");
                }

                array_shift($matches);

                return $controller->$method(...$matches);
            }
        }

        return $this->error404();

    }
}
