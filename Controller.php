<?php 

require 'Model.php';

$requete = new Tournoi();
if (isset($_POST['start'])){
    $requete->start();
}
elseif (isset($_POST['id_match'])){
    $requete->score_enregistre($_POST);
}
elseif (isset($_POST['end'])){
    $requete->end_turnament($_POST);
}