<?php

class ControleurPrincipal
{
    public function gererRequete()
    {
        // Récupère l'URL demandée, ex : "/utilisateur/15?test=1"
        $url = $_SERVER['REQUEST_URI'];

        // echo ($url);

        // Supprime les paramètres après "?", ex : "/utilisateur/15?test=1" -> "/utilisateur/15"
        $url = explode('?', $url)[0];

        // Supprime les "/" inutiles au début et à la fin
        // "/utilisateur/15/" -> "utilisateur/15"
        $url = trim($url, '/');

        // Tableau associant chaque URL à la méthode à appeler
        // 'url' => 'méthode'
        // {id} représente un integer
        $routes = [
            '' => 'accueil',
            'utilisateurs' => 'listeUtilisateurs',
            'utilisateur/{id}' => 'afficherUtilisateur',
            'utilisateur/{id}/modifier' => 'modifierUtilisateur',
            'utilisateur/{id}/supprimer' => 'supprimerUtilisateur',
            'connexion' => 'connexion'
        ];

        // Parcourt toutes les routes pour trouver celle qui correspond à l'URL
        foreach ($routes as $route => $action) {

            // Transforme {id} en règle acceptant un ou plusieurs chiffres
            // "utilisateur/{id}" -> "utilisateur/([0-9]+)"
            $routeAvecRegex = str_replace('{id}', '([0-9]+)', $route);

            // Vérifie si l'URL correspond exactement à la route
            // $correspondance récupère également les valeurs trouvées
            if (preg_match('#^' . $routeAvecRegex . '$#', $url, $correspondance)) {

                // Récupère l'id s'il existe, sinon vaut null
                $parametre = $correspondance[1] ?? null;

                /* 
                $correspondance = [
                    0 => 'utilisateur/25',
                    1 => '25'
                ];
                */

                // Appelle la méthode dans la classe correspondant à la route
                // Ex : "afficherUtilisateur" + 15 -> afficherUtilisateur(15)
                $this->$action($parametre);

                // Route trouvée : inutile de continuer la boucle
                return;
            }
        }

        // Aucune route ne correspond à l'URL
        echo "Page introuvable";
    }



    private function accueil()
    {
        echo "Accueil";
    }


    private function listeUtilisateurs()
    {
        echo "Liste des utilisateurs";
    }


    private function afficherUtilisateur($id)
    {
        echo "Utilisateur numéro " . $id;
    }


    private function modifierUtilisateur($id)
    {
        echo "Modifier utilisateur numéro " . $id;
    }


    private function supprimerUtilisateur($id)
    {
        echo "Supprimer utilisateur numéro " . $id;
    }


    private function connexion()
    {
        echo "Page de connexion";
    }
}
