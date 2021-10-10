<?php

/*

CAS 9 à 16 équipes
Structure $planning : [
    Finale => [
        0 => [WinDemi0, [Score0, Score1], WinDemi1]
    ],
    Demis => [
        0 => [WinQuart0, [Score0, Score1], WinQuart1],
        1 => [WinQuart2, [Score0, Score1], WinQuart3]
    ],
    Quarts => [
        0 => [WinHuit0, [Score0, Score1], WinHuit1],
        1 => [WinHuit2, [Score0, Score1], WinHuit3],
        2 => [WinHuit4, [Score0, Score1], WinHuit5],
        3 => [WinHuit6, [Score0, Score1], WinHuit7]
    ],
    Huitièmes => [
        0 => [1, [Score0, Score1], 16],
        1 => [2, [Score0, Score1], 15],
        2 => [3, [Score0, Score1], 14],
        3 => [4, [Score0, Score1], 13]
        4 => [5, [Score0, Score1], 12],
        5 => [6, [Score0, Score1], 11],
        6 => [7, [Score0, Score1], 10],
        7 => [8, [Score0, Score1], 9]
    ]
]

*/

namespace Model;

class singleBrackets
{
    private $planning = [];
    public function __construct($nbrTeams, $thirdPlace)
    {
        for ($i = 1; $i > 0; $i++){
            if (pow(2, $i) >= $nbrTeams){
                $nbrRounds = $i;
                break;
            }
        }
        $arrayRoundNames = ["finals", "semiFinals", "quarterFinals", "roundOf16"];
        for ($j = 0; $j < $nbrRounds; $j++){
            if ($j < 4){
                $this->planning[$arrayRoundNames[$j]] = [];
            } else {
                $this->planning["round" . ($nbrRounds - $j)] = [];
            }
        }
        $this->planning = array_reverse($this->planning);
        var_dump($this->planning);
    }
}
