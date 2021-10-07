<?php

class Planning_generator
{
    private static $teams;
    private static $tabOfDuels;
    private static $odd;

    public function __construct($nbrTeams)
    {
        self::$teams = [];
        for ($i = 1; $i <= $nbrTeams; $i++) {
            array_push(self::$teams, $i);
        }
        self::$odd = $nbrTeams % 2 === 0 ? true : false;
        self::$tabOfDuels = [];
        for ($i = 0; $i < $nbrTeams; $i++) {
            self::$tabOfDuels[self::$teams[$i]] = [];
            if (self::$odd) {
                for ($j = 1; $j < $nbrTeams; $j++) {
                    self::$tabOfDuels[self::$teams[$i]][$j] = null;
                }
            } else {
                for ($j = 1; $j <= $nbrTeams; $j++) {
                    self::$tabOfDuels[self::$teams[$i]][$j] = null;
                }
            }
        }
        $this->setTabOfDuels();
    }

    public function getTeams()
    {
        return self::$teams;
    }

    public function getTabOfDuels()
    {
        return self::$tabOfDuels;
    }

    public function setTabOfDuels($tabOfTeams = [], $day_match = 0)
    {
        if (count($tabOfTeams) <= 1) {
            $day_match++;
            if ($day_match >= count(self::$teams) && self::$odd) {
                return true;
            } else if ($day_match > count(self::$teams) && !self::$odd) {
                return true;
            } else if (count($tabOfTeams) === 1 && count($this->getAlreadyFaced($tabOfTeams[array_key_first($tabOfTeams)])) === $day_match - 3) {
                return false;
            }
            $tabOfTeams = self::$teams;
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
                $opponent = self::$teams[$this->searchForName($nameOpponent)];
                $idInShuffle = array_search($opponent, $tabOfTeams);
                unset($tabOfTeams[$idInShuffle]);
                self::$tabOfDuels[$team][$day_match] = $opponent;
                $returnBool = $this->setTabOfDuels($tabOfTeams, $day_match);
                if ($returnBool) {
                    return true;
                } else {
                    $tabOfTeams[$idInShuffle] = $opponent;
                    self::$tabOfDuels[$team][$day_match] = null;
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
        foreach (self::$tabOfDuels as $name => $team) {
            if ($name === $teamName) {
                foreach ($team as $opponent) {
                    if ($opponent !== null) {
                        array_push($alreadyFaced, $opponent);
                    }
                }
            } else if (in_array($teamName, $team)) {
                array_push($alreadyFaced, $name);
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
        foreach (self::$teams as $key => $val) {
            if ($val === $name) {
                return $key;
            }
        }
        return null;
    }

    public function display()
    {
        if (self::$odd) {
            for ($day = 1; $day < count(self::$teams); $day++) {
                echo "<h3>JOUR " . $day . " !</h3>";
                // echo "JOUR " . $day . " !\n";
                $shuffledArray = $this->shuffle_assoc(self::$tabOfDuels);
                foreach ($shuffledArray as $team => $duels) {
                    if ($duels[$day] !== null) {
                        $opponent = $duels[$day];
                        echo $team . " VS " . $opponent . "<br>";
                        // echo $team . " VS " . $opponent . "\n";
                    }
                }
            }
        } else {
            for ($day = 1; $day <= count(self::$teams); $day++) {
                echo "<h3>JOUR " . $day . " !</h3>";
                // echo "JOUR " . $day . " !\n";
                $shuffledArray = self::shuffle_assoc(self::$tabOfDuels);
                foreach ($shuffledArray as $team => $duels) {
                    if ($duels[$day] !== null) {
                        $opponent = $duels[$day];
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
