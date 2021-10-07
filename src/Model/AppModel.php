<?php

namespace Model;

class AppModel
{
    public function newTournament($type, $options, $nombreParticipants)
    {
        var_dump($type);
        var_dump($options);
        var_dump($nombreParticipants);
        switch ($type) {
            case "SwissRound":
                break;
            case "RoundRobin":
                break;
            case "SingleBrackets":
                break;
            case "DoubleBrackets":
                break;
        }
    }
}
