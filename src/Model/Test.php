<?php

require "roundRobin.php";

$nbrTeams = 6;

$tournament = new Planning_generator ($nbrTeams);
$tournament->display();
// echo "<pre>";
// var_dump($tournament->getTabOfDuels());
// echo "</pre>";