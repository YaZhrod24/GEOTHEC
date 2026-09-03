<?php
class Accueil
{
    public function index()
    {

        $titre = 'test';

        include_once '../app/vue/html/entete.php';
        include_once '../app/vue/html/vue.php';
        include_once '../app/vue/html/pied.php';
    }

    public function Chiffre($variable)
    {
        echo $variable;
    }

    public function Lettre($variable)
    {
        echo $variable;
    }
}