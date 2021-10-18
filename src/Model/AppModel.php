<?php

namespace Model;

use Model\roundRobin;
use Model\singleBrackets;


class AppModel
{
    public function newTournament($type, $nombreParticipants, $options)
    {
        switch ($type) {
            case "SwissRound":
                break;
            case "RoundRobin":
                $tournament = new roundRobin ($nombreParticipants);
                echo json_encode($tournament->getTabOfDuels());
                break;
            case "SingleBrackets":
                $tournament = new singleBrackets ($nombreParticipants, $options["thirdPlaceOptions"], $options["randomOption"]);
                echo json_encode($tournament->getPlaning());
                break;
            case "DoubleBrackets":
                break;
        }
    }
}
