<?php

include_once(__DIR__ . "/../modele/classe.php");

$classe = new Classe();
$classes = $classe->GetAllClasse();

require_once(__DIR__ . "/../vue/layout/entete.php");
require_once(__DIR__ . "/../vue/vueAccueil.php");
require_once(__DIR__ . "/../vue/layout/pied.php");