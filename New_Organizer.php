<?php

class Planning_generator
{
    private static $teams;
    private static $tabOfDuels;
    public static function setTeams($array)
    {
        foreach ($array as $number => $team) {
            self::$teams[$number] = ["id" => $number + 1, "name" => $team];
        }
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
            for ($j = 1; $j < $numberOfTeams; $j++) {
                self::$tabOfDuels[self::$teams[$i]["name"]][$j] = "0";
            }
        }
        // echo "<pre>";
        // var_dump(self::$tabOfDuels);
        // echo "</pre>";
    }
    public static function setTabOfDuels($tabOfTeams = [], $day_match = 0)
    {
        if ($tabOfTeams === []) {
            $tabOfTeams = self::$teams;
            shuffle($tabOfTeams);
            $day_match++;
            // var_dump($day_match);
        }
        if ($day_match >= count(self::$teams)) {
            return true;
        }
        $tabTeamForeach = $tabOfTeams;
        foreach ($tabTeamForeach as $idShuffle => $team) {
            unset($tabOfTeams[$idShuffle]);
            $tabOpponent = self::getPossibleOpponents($team["name"], $tabOfTeams);
            if (count($tabOpponent) === 0) {
                return false;
            }
            shuffle($tabOpponent);
            foreach ($tabOpponent as $nameOpponent) {
                $opponent = self::$teams[self::searchForName($nameOpponent)];
                $idInShuffle = array_search($opponent, $tabOfTeams);
                unset($tabOfTeams[$idInShuffle]);
                self::$tabOfDuels[$team["name"]][$day_match] = $opponent["name"];
                // self::$tabOfDuels[$opponent["name"]][$day_match] = $team["name"];
                $returnBool = self::setTabOfDuels($tabOfTeams, $day_match);
                if ($returnBool) {
                    return true;
                } else {
                    $tabOfTeams[$idInShuffle] = $opponent;
                    // self::$tabOfDuels[$opponent["name"]][$day_match] = "0";
                    self::$tabOfDuels[$team["name"]][$day_match] = "0";
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
    private static function getPossibleOpponents($teamName, $tabOfTeams)
    {
        $alreadyFaced = [];
        foreach (self::$tabOfDuels as $name => $team) {
            if ($name === $teamName) {
                foreach ($team as $opponent) {
                    if ($opponent !== "0") {
                        array_push($alreadyFaced, $opponent);
                    }
                }
            } else if (in_array($teamName, $team)) {
                array_push($alreadyFaced, $name);
            }
        }
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
        for ($day = 1; $day < count(self::$teams); $day++) {
            echo "<h3>JOUR " . $day . " !</h3>";
            $shuffledArray = self::shuffle_assoc(self::$tabOfDuels);
            foreach ($shuffledArray as $team => $duels) {
                if ($duels[$day] !== "0") {
                    $opponent = $duels[$day];
                    echo $team . " VS " . $opponent . "<br>";
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
    // public static function getIdFromName($name)
    // {
    //     // return searchForName($name)
    // }
}
