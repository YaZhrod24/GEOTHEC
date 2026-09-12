<?php

$racine = dirname(__FILE__);

require_once "$racine/../app/controleur/controleurPrincipal.php";

$controleur = new ControleurPrincipal();

$controleur->gererRequete();
