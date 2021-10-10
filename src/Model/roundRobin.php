<?php

namespace Model;

class roundRobin
{
    private $teams;
    private $tabOfDuels;
    private $odd;

    public function __construct($nbrTeams)
    {
        $this->teams = [];
        for ($i = 1; $i <= $nbrTeams; $i++) {
            array_push($this->teams, $i);
        }
        $this->odd = $nbrTeams % 2 === 0 ? true : false;
        $this->tabOfDuels = [];
        for ($i = 0; $i < $nbrTeams; $i++) {
            $this->tabOfDuels[$this->teams[$i]] = [];
            if ($this->odd) {
                for ($j = 1; $j < $nbrTeams; $j++) {
                    $this->tabOfDuels[$this->teams[$i]][$j] = ["opponent" => null, "win" => null, "score" => null];
                }
            } else {
                for ($j = 1; $j <= $nbrTeams; $j++) {
                    $this->tabOfDuels[$this->teams[$i]][$j] = ["opponent" => null, "win" => null, "score" => null];
                }
            }
        }
        $this->setTabOfDuels();
    }

    public function getTeams()
    {
        return $this->teams;
    }

    public function getTabOfDuels()
    {
        return $this->tabOfDuels;
    }

    public function setTabOfDuels($tabOfTeams = [], $day_match = 0)
    {
        if (count($tabOfTeams) <= 1) {
            $day_match++;
            if ($day_match >= count($this->teams) && $this->odd) {
                return true;
            } else if ($day_match > count($this->teams) && !$this->odd) {
                return true;
            } else if (count($tabOfTeams) === 1 && count($this->getAlreadyFaced($tabOfTeams[array_key_first($tabOfTeams)])) === $day_match - 3) {
                return false;
            }
            $tabOfTeams = $this->teams;
            shuffle($tabOfTeams);
            foreach ($tabOfTeams as $key => $team) {
                if (count($this->getAlreadyFaced($team)) === $day_match - 2) {
                    $stock = $tabOfTeams[$key];
                    unset($tabOfTeams[$key]);
                    array_unshift($tabOfTeams, $stock);
                }
            }
        }
        $tabTeamForeach = $tabOfTeams;
        foreach ($tabTeamForeach as $idShuffle => $team) {
            unset($tabOfTeams[$idShuffle]);
            $tabOpponent = $this->getPossibleOpponents($team, $tabOfTeams);
            if (count($tabOpponent) === 0) {
                return false;
            }
            shuffle($tabOpponent);
            foreach ($tabOpponent as $key => $nameOpponent) {
                if (count($this->getAlreadyFaced($nameOpponent)) === $day_match - 2) {
                    $stock = $tabOpponent[$key];
                    unset($tabOpponent[$key]);
                    array_unshift($tabOpponent, $stock);
                }
            }
            foreach ($tabOpponent as $nameOpponent) {
                $opponent = $this->teams[$this->searchForName($nameOpponent)];
                $idInShuffle = array_search($opponent, $tabOfTeams);
                unset($tabOfTeams[$idInShuffle]);
                $this->tabOfDuels[$team][$day_match]["opponent"] = $opponent;
                $returnBool = $this->setTabOfDuels($tabOfTeams, $day_match);
                if ($returnBool) {
                    return true;
                } else {
                    $tabOfTeams[$idInShuffle] = $opponent;
                    $this->tabOfDuels[$team][$day_match]["opponent"] = null;
                }
            }
            $tabOfTeams[$idShuffle] = $team;
            return false;
        }
        return false;
    }

    private function getAlreadyFaced($teamName)
    {
        $alreadyFaced = [];
        foreach ($this->tabOfDuels as $name => $team) {
            if ($name === $teamName) {
                foreach ($team as $opponent) {
                    if ($opponent["opponent"] !== null) {
                        array_push($alreadyFaced, $opponent["opponent"]);
                    }
                }
            // } else if (in_array($teamName, $team)) {
            //     array_push($alreadyFaced, $name);
            } else {
                foreach ($team as $array){
                    if ($teamName === $array["opponent"]) {
                        array_push($alreadyFaced, $name);
                    }
                }
            }
        }
        return $alreadyFaced;
    }
    private function getPossibleOpponents($teamName, $tabOfTeams)
    {
        $alreadyFaced = $this->getAlreadyFaced($teamName);
        $newTabOfTeams = [];
        foreach ($tabOfTeams as $team) {
            array_push($newTabOfTeams, $team);
        }
        $available = array_diff($newTabOfTeams, $alreadyFaced);
        return $available;
    }
    public function searchForName($name)
    {
        foreach ($this->teams as $key => $val) {
            if ($val === $name) {
                return $key;
            }
        }
        return null;
    }

    public function display()
    {
        if ($this->odd) {
            for ($day = 1; $day < count($this->teams); $day++) {
                echo "<h3>JOUR " . $day . " !</h3>";
                // echo "JOUR " . $day . " !\n";
                $shuffledArray = $this->shuffle_assoc($this->tabOfDuels);
                foreach ($shuffledArray as $team => $duels) {
                    if ($duels[$day]["opponent"] !== null) {
                        $opponent = $duels[$day]["opponent"];
                        echo $team . " VS " . $opponent . "<br>";
                        // echo $team . " VS " . $opponent . "\n";
                    }
                }
            }
        } else {
            for ($day = 1; $day <= count($this->teams); $day++) {
                echo "<h3>JOUR " . $day . " !</h3>";
                // echo "JOUR " . $day . " !\n";
                $shuffledArray = $this->shuffle_assoc($this->tabOfDuels);
                foreach ($shuffledArray as $team => $duels) {
                    if ($duels[$day]["opponent"] !== null) {
                        $opponent = $duels[$day]["opponent"];
                        echo $team . " VS " . $opponent . "<br>";
                        // echo $team . " VS " . $opponent . "\n";
                    }
                }
            }
        }
    }
    private function shuffle_assoc($list)
    {
        if (!is_array($list)) {
            return $list;
        }
        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }
}
