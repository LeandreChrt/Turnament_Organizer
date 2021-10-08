<?php

namespace Model;

use Model\roundRobin;

class AppModel
{
    public function newTournament($type, $nombreParticipants)
    {
        switch ($type) {
            case "SwissRound":
                break;
            case "RoundRobin":
                $test = new roundRobin ($nombreParticipants);
                var_dump($test->getTabOfDuels());
                break;
            case "SingleBrackets":
                break;
            case "DoubleBrackets":
                break;
        }
    }
}
