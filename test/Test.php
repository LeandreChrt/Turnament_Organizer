<?php

require "New_Organizer.php";

$teams = ["+UN", "ARE", "ART", "CHA", "DRA", "ESP", "ENE", "GOB", "GRI", "GUI", "JES", "KAZ", "MMA", "MUR", "OIS", "SER", "SOR", "VAM", "ZAK", "ZOM"];

Planning_generator::setTeams($teams);
Planning_generator::createTabOfDuels(count($teams));
Planning_generator::setTabOfDuels();
Planning_generator::display();
// echo "<pre>";
// var_dump(Planning_generator::getTabOfDuels());
// echo "</pre>";