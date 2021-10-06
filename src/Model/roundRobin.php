<?php

class Planning_generator
{
    private static $teams;
    private static $tabOfDuels;
    private static $odd;
    public static function setTeams($array)
    {
        foreach ($array as $number => $team) {
            self::$teams[$number] = ["id" => $number + 1, "name" => $team];
        }
        self::$odd = count(self::$teams) % 2 === 0 ? true : false;
    }
    public static function getTeams()
    {
        return self::$teams;
    }
    public static function createTabOfDuels($numberOfTeams)
    {
        self::$tabOfDuels = [];
        for ($i = 0; $i < $numberOfTeams; $i++) {
            self::$tabOfDuels[self::$teams[$i]["name"]] = [];
            if (self::$odd) {
                for ($j = 1; $j < $numberOfTeams; $j++) {
                    self::$tabOfDuels[self::$teams[$i]["name"]][$j] = null;
                }
            } else {
                for ($j = 1; $j <= $numberOfTeams; $j++) {
                    self::$tabOfDuels[self::$teams[$i]["name"]][$j] = null;
                }
            }
        }
    }
    public static function setTabOfDuels($tabOfTeams = [], $day_match = 0)
    {
        if (count($tabOfTeams) <= 1) {
            $day_match++;
            if ($day_match >= count(self::$teams) && self::$odd) {
                return true;
            } else if ($day_match > count(self::$teams) && !self::$odd) {
                return true;
            } else if (count($tabOfTeams) === 1 && count(self::getAlreadyFaced($tabOfTeams[array_key_first($tabOfTeams)]["name"])) === $day_match - 3){
                return false;
            }
            $tabOfTeams = self::$teams;
            shuffle($tabOfTeams);
            foreach ($tabOfTeams as $key => $team) {
                if (count(self::getAlreadyFaced($team["name"])) === $day_match - 2) {
                    $stock = $tabOfTeams[$key];
                    unset($tabOfTeams[$key]);
                    array_unshift($tabOfTeams, $stock);
                }
            }
        }
        $tabTeamForeach = $tabOfTeams;
        foreach ($tabTeamForeach as $idShuffle => $team) {
            unset($tabOfTeams[$idShuffle]);
            $tabOpponent = self::getPossibleOpponents($team["name"], $tabOfTeams);
            if (count($tabOpponent) === 0) {
                return false;
            }
            shuffle($tabOpponent);
            foreach ($tabOpponent as $key => $nameOpponent){
                if (count(self::getAlreadyFaced($nameOpponent)) === $day_match - 2) {
                    $stock = $tabOpponent[$key];
                    unset($tabOpponent[$key]);
                    array_unshift($tabOpponent, $stock);
                }
            }
            foreach ($tabOpponent as $nameOpponent) {
                $opponent = self::$teams[self::searchForName($nameOpponent)];
                $idInShuffle = array_search($opponent, $tabOfTeams);
                unset($tabOfTeams[$idInShuffle]);
                self::$tabOfDuels[$team["name"]][$day_match] = $opponent["name"];
                $returnBool = self::setTabOfDuels($tabOfTeams, $day_match);
                if ($returnBool) {
                    return true;
                } else {
                    $tabOfTeams[$idInShuffle] = $opponent;
                    self::$tabOfDuels[$team["name"]][$day_match] = null;
                }
            }
            $tabOfTeams[$idShuffle] = $team;
            return false;
        }
        return false;
    }
    public static function getTabOfDuels()
    {
        return self::$tabOfDuels;
    }
    private static function getAlreadyFaced($teamName)
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
    private static function getPossibleOpponents($teamName, $tabOfTeams)
    {
        $alreadyFaced = self::getAlreadyFaced($teamName);
        $newTabOfTeams = [];
        foreach ($tabOfTeams as $team) {
            array_push($newTabOfTeams, $team["name"]);
        }
        $available = array_diff($newTabOfTeams, $alreadyFaced);
        return $available;
    }
    public static function searchForName($name)
    {
        foreach (self::$teams as $key => $val) {
            if ($val['name'] === $name) {
                return $key;
            }
        }
        return null;
    }

    public static function display()
    {
        if (self::$odd) {
            for ($day = 1; $day < count(self::$teams); $day++) {
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
    private static function shuffle_assoc($list)
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
