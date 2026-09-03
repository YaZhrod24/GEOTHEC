<?php
class Erreur
{
    public function view500()
    {
        include ROOT . "/app/vue/erreur/vue500.php";
    }

    public function view404()
    {
        include ROOT . "/app/vue/erreur/vue404.php";
    }
}