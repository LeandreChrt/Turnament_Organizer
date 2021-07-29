<?php 

require 'Model.php';

$requete = new Turnament();
if (isset($_POST['start'])){
    $requete->start();
}
elseif (isset($_POST['id_match'])){
    $requete->score_in_db($_POST);
}
elseif (isset($_POST['end'])){
    $requete->end_turnament($_POST);
}