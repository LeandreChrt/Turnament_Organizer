<?php

namespace Model;

class singleBrackets
{
    private $planning = [];
    public function __construct($nbrTeams, $thirdPlace, $random)
    {
        $finalsMatch = ($thirdPlace === "off" ? 1 : ($nbrTeams < 4 ? 1 : 2));
        $arrayRoundNames = ["finals", "semiFinals", "quarterFinals", "roundOf16"];
        for ($i = 1; $i > 0; $i++) {
            if (pow(2, $i) >= $nbrTeams) {
                $nbrRounds = $i;
                break;
            }
        }
        for ($j = 0; $j < $nbrRounds; $j++) {
            if ($j === 0) {
                $this->planning[$arrayRoundNames[$j]] = [];
                for ($k = 0; $k < $finalsMatch; $k++) {
                    if ($nbrRounds === 1) {
                        $team1 = $k + 1;
                        $team2 = pow(2, $nbrRounds) - $k > $nbrTeams ? null : pow(2, $nbrRounds) - $k;
                    } else {
                        $team1 = null;
                        $team2 = null;
                    }
                    $this->planning[$arrayRoundNames[$j]][$k] = ["team1" => $team1, "score1" => null, "winner" => null, "team2" => $team2, "score2" => null];
                }
            } else {
                if ($j >= 4) {
                    array_push($arrayRoundNames, "round" . ($nbrRounds - $j));
                }
                $roundName = $arrayRoundNames[$j];
                $this->planning[$roundName] = [];

                $half = pow(2, $j);
                if ($j + 1 < $nbrRounds) {
                    for ($k = 0; $k < $half; $k++) {
                        $this->planning[$roundName][$k] = ["team1" => null, "score1" => null, "winner" => null, "team2" => null, "score2" => null];
                    }
                } else {
                    // DISTRIBUER AVEC L'ORDRE
                    $tabLogos = [
                        1 => 0,
                        2 => $half / 2,
                    ];
                    if ($random !== 'random') {
                        $team1 = (rand(0, 2) & 1) === 1 ? (pow(2, $nbrRounds) > $nbrTeams ? null : pow(2, $nbrRounds)) : 1;
                        $opponent1 = $team1 === 1 ? (pow(2, $nbrRounds) > $nbrTeams ? null : pow(2, $nbrRounds)) : 1;
                        $team2 = (rand(0, 2) & 1) === 1 ? (pow(2, $nbrRounds) - 1 > $nbrTeams ? null : pow(2, $nbrRounds) - 1) : 2;
                        $opponent2 = $team2 === 2 ? (pow(2, $nbrRounds) - 1 > $nbrTeams ? null : pow(2, $nbrRounds) - 1) : 2;
                        $this->planning[$roundName][0] = ["team1" => $team1, "score1" => null, "winner" => null, "team2" => $opponent1, "score2" => null];
                        $this->planning[$roundName][$half / 2] = ["team1" => $team2, "score1" => null, "winner" => null, "team2" => $opponent2, "score2" => null];
                    }
                    $quarter = $half / 2;
                    for ($k = 3; $k <= $half; ++$k) {
                        if ($k <= $quarter) {
                            // test si $k est une puissance de 2
                            if (($k & $k - 1) === 0) {
                                $nextPow = $k;
                            } else {
                                // crée une copie de $k pour faire des manipulations
                                $x = $k;
                                // boucle $x jusqu'à récupérer la puissance supérieur de $k
                                for ($l = 0; $x > 1; $l++) {
                                    $x = $x >> 1;
                                }
                                $nextPow = 1 << ($l + 1);
                            }
                            $posPlayer = $tabLogos[$nextPow + 1 - $k] + $half / $nextPow;
                        } else {
                            $posPlayer = $tabLogos[$half - $k + 1] + 1;
                        }
                        $tabLogos[$k] = $posPlayer;
                        if ($random !== 'random') {
                            $team1 = rand(0, 2) & 1 === 1 ? (pow(2, $nbrRounds) - $k + 1 > $nbrTeams ? null : pow(2, $nbrRounds) - $k + 1) : $k;
                            $team2 = $team1 === $k ? (pow(2, $nbrRounds) - $k + 1 > $nbrTeams ? null : pow(2, $nbrRounds) - $k + 1) : $k;
                            $this->planning[$roundName][$posPlayer] = ["team1" => $team1, "score1" => null, "winner" => null, "team2" => $team2, "score2" => null];
                        }
                    }
                    if ($random === 'random') {
                        $arrayTeams = range(1, $nbrTeams);
                        $limit = 2 * $half - $nbrTeams;
                        for ($k = 1; $k <= $half; ++$k) {
                            $team1 = $arrayTeams[array_rand($arrayTeams)];
                            unset($arrayTeams[$team1 - 1]);
                            if ($limit > 0) {
                                $team2 = null;
                                --$limit;
                            } else {
                                $team2 = $arrayTeams[array_rand($arrayTeams)];
                                unset($arrayTeams[$team2 - 1]);
                            }
                            $posMatch = $tabLogos[$k];
                            $this->planning[$roundName][$posMatch] = ["team1" => $team1, "score1" => null, "winner" => null, "team2" => $team2, "score2" => null];
                        }
                    }
                    ksort($this->planning[$roundName]);
                }
            }
        }
        $this->planning = array_reverse($this->planning);
        $nameFirstRound = null;
        $multiplier = 1;
        $maxForRand = $random === 'random' ? 1 : 2;
        foreach ($this->planning as $nameRound => $round) {
            if ($nameFirstRound === null) {
                $nameFirstRound = $nameRound;
            } else {
                foreach ($round as $number => $arrayDual) {
                    if ((rand(0, $maxForRand) & 1) === 1) {
                        $firstHalf = [];
                        $secondHalf = [];
                        for ($i = $number * $multiplier; $i < ($number + 1) * $multiplier; $i++) {
                            if ($i < ($number + 0.5) * $multiplier) {
                                array_push($firstHalf, $i);
                            } else {
                                array_push($secondHalf, $i);
                            }
                        }
                        $count = count($firstHalf);
                        for ($i = 0; $i < $count; ++$i) {
                            $temp = $this->planning[$nameFirstRound][$firstHalf[$i]];
                            $this->planning[$nameFirstRound][$firstHalf[$i]] = $this->planning[$nameFirstRound][$secondHalf[$i]];
                            $this->planning[$nameFirstRound][$secondHalf[$i]] = $temp;
                        }
                    }
                }
            }
            $multiplier *= 2;
        }
        // RAJOUTER L'AVANCÉE DES ÉQUIPES PRÉ-QUALIFIÉES
        $round2Name = $arrayRoundNames[$nbrRounds - 2];
        foreach ($this->planning[$nameFirstRound] as $matchNumber => $matchArray) {
            if (in_array(null, [$matchArray['team1'], $matchArray['team2']])) {
                foreach ($matchArray as $matchInfos) {
                    if ($matchInfos !== null) {
                        $team = ($matchNumber & 1) === 1 ? 'team2' : 'team1';
                        $matchPos = ($matchNumber & 1) === 1 ? ($matchNumber - 1) / 2 : $matchNumber / 2;
                        $this->planning[$round2Name][$matchPos][$team] = $matchInfos;
                        unset($this->planning[$nameFirstRound][$matchNumber]);
                    }
                }
            }
        }
    }

    public function getPlaning()
    {
        return $this->planning;
    }

    public function display()
    {
        foreach ($this->planning as $roundName => $roundMatchs) {
            echo '<p><strong>', $roundName, '</strong> : ';
            foreach ($roundMatchs as $matchID => $matchArray) {
                $termUsed = $roundName !== 'finals' ? 'winner ' : ($matchID === 0 ? 'winner ' : 'looser ');
                $precMatch = $roundName !== 'finals' ? $matchID * 2 : ($matchID === 0 ? $matchID * 2 : ($matchID - 1) * 2);
                $team1 = $matchArray['team1'] === null ? $termUsed . ($precMatch) : $matchArray['team1'];
                $team2 = $matchArray['team2'] === null ? $termUsed . ($precMatch + 1) : $matchArray['team2'];
                echo '<strong>', $matchID, '</strong> (', $team1, ' VS ', $team2, '); ';
            }
            echo '</p>';
        }
    }
}
