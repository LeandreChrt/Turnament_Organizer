<?php

require "New_Organizer.php";

class Turnament
{

    function __construct()
    {
        try {
            $db = new PDO('mysql:host=localhost;dbname=turnament;charset=utf8', 'newuser', 'password');
        } catch (Exception $e) {
            die('Error : ' . $e->getMessage());
        }
        $this->db = $db;
    }

    function start()
    {
        $table = $this->db->query("SHOW TABLES LIKE 'total_points'");
        if ($table->fetch() == null) {
            $this->db->query("CREATE TABLE total_points (
                CREATE TABLE total_points (
                id_gen_team int PRIMARY KEY NOT NULL AUTO_INCREMENT,
                name varchar(3),
                gen_win smallint,
                gen_lost smallint,
                gen_points smallint,
                `gen_hp+` int,
                `gen_hp-` int,
                turnament_wins smallint
            )");
        }

        $table = $this->db->query("SHOW TABLES LIKE 'teams'");
        if ($data = $table->fetch()) {
            $this->db->query("DROP TABLE teams");
        }
        $this->db->query("CREATE TABLE teams (
            id_team int PRIMARY KEY NOT NULL AUTO_INCREMENT,
            id_gen_team int,
            win smallint,
            lost smallint,
            points smallint,
            `hp+` int,
            `hp-` int
        );");

        $table = $this->db->query("SHOW TABLES LIKE 'planning'");
        if ($data = $table->fetch()) {
            $this->db->query("DROP TABLE planning");
        }
        $this->db->query("CREATE TABLE planning (
            id_match int PRIMARY KEY NOT NULL AUTO_INCREMENT,
            id_team_1 tinyint,
            score_1 smallint,
            id_team_2 tinyint,
            score_2 smallint,
            id_winner tinyint
        );");

        $teams_table = [];
        $teamsName = [];
        $request = $this->db->query("SELECT * FROM total_points");
        while ($data = $request->fetch()) {
            array_push($teams_table, $data['id_gen_team']);
            array_push($teamsName, $data["name"]);
        }
        shuffle($teams_table);
        foreach ($teams_table as $team) {
            $request = $this->db->prepare("INSERT INTO teams VALUES (null, :team, 0, 0, 0, 0, 0)");
            $request->bindValue('team', $team);
            $request->execute();
        }
        // $this->planning();
        Planning_generator::setTeams($teamsName);
        Planning_generator::createTabOfDuels(count($teamsName));
        Planning_generator::setTabOfDuels();
        $this->inDB(count($teamsName), Planning_generator::getTabOfDuels());
        $this->affiche();
    }

    function load()
    {
        $this->affiche();
    }

    function planning()
    {
        $a = range(1, 19);
        $b = $this->mirror($a);
        $c = [3, 0, 1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 4, 5];
        $d = $this->mirror($c);
        $e = [5, 8, 9, 0, 1, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 6, 7, 2, 3];
        $f = $this->mirror($e);
        $g = [7, 12, 13, 2, 3, 0, 1, 14, 15, 16, 17, 18, 19, 8, 9, 4, 5, 10, 11];
        $h = $this->mirror($g);
        $i = [9, 4, 5, 16, 17, 2, 3, 0, 1, 18, 19, 10, 11, 6, 7, 12, 13, 14, 15];
        $j = $this->mirror($i);
        $k = [11, 16, 17, 18, 19, 4, 5, 2, 3, 0, 1, 8, 9, 12, 13, 14, 15, 6, 7];
        $l = $this->mirror($k);
        $m = [13, 6, 7, 14, 15, 18, 19, 4, 5, 2, 3, 0, 1, 10, 11, 8, 9, 16, 17];
        $n = $this->mirror($m);
        $o = [15, 18, 19, 12, 13, 16, 17, 6, 7, 4, 5, 2, 3, 0, 1, 10, 11, 8, 9];
        $p = $this->mirror($o);
        $q = [17, 10, 11, 8, 9, 14, 15, 18, 19, 6, 7, 4, 5, 2, 3, 0, 1, 12, 13];
        $r = $this->mirror($q);
        $s = [19, 14, 15, 10, 11, 12, 13, 16, 17, 8, 9, 6, 7, 4, 5, 2, 3, 0, 1];
        $t = $this->mirror($s);

        $oppenent_table = [$a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $q, $r, $s, $t];
        for ($j = 0; $j < 19; $j++) {
            $temp_tab = range(0, 19);
            for ($k = 0; $k < 10; $k++) {
                $int_select = rand(0, count($temp_tab) - 1);
                $number_team_1 = $temp_tab[$int_select];
                $number_team_2 = $oppenent_table[$number_team_1][$j];
                unset($temp_tab[$int_select]);
                unset($temp_tab[array_search($number_team_2, $temp_tab)]);
                sort($temp_tab);
                if ($number_team_2 == 0) {
                    $number_team_2 = 20;
                }
                if ($number_team_1 == 0) {
                    $number_team_1 = 20;
                }
                $request = $this->db->prepare("INSERT INTO planning (id_team_1, id_team_2, id_match) VALUES (:team1, :team2, null)");
                $request->bindValue('team1', $number_team_1);
                $request->bindValue('team2', $number_team_2);
                $request->execute();
            }
        }
        $this->affiche();
    }

    function affiche()
    {
        $request1 = $this->db->query("SELECT name AS team_1, id_winner, score_1, id_team_1 FROM planning INNER JOIN teams ON id_team_1=teams.id_team INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team");
        $request2 = $this->db->query("SELECT name AS team_2, id_winner, score_2, id_team_2 FROM planning INNER JOIN teams ON id_team_2=teams.id_team INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team");
        $count_plus_10 = 11;
        echo "<div id='planning'>";
        while ($data1 = $request1->fetch()) {
            if (($count_plus_10 - 1) % 10 === 0) {
                echo "<table id='D" . round($count_plus_10 / 10) . "'><tr><th colspan=3>Day " . round($count_plus_10 / 10) . "</th></tr>";
            }
            $data2 = $request2->fetch();
            // echo "<tr id='".($count_plus_10-10)."'><td class='left'>" . $data1['team_1'] . "</td><td class=center onclick='enter_result(\"".($count_plus_10-10)."\")'> VS </td><td class='right'>" . $data2['team_2'] . "</td></tr>";
            echo "<tr id='" . ($count_plus_10 - 10) . "'>
                <td>" . $data1['team_1'] . "</td>";
            if ($data1['id_winner'] == $data1['id_team_1']) {
                echo "<td><strong>" . $data1['score_1'] . "</strong>-" . $data2['score_2'] . "</td>";
            } elseif ($data2['id_winner'] == $data2['id_team_2']) {
                echo "<td>" . $data1['score_1'] . "-<strong>" . $data2['score_2'] . "</strong></td>";
            } else {
                echo "<td class='center' data-bs-toggle='modal' data-bs-target='#exampleModal" . ($count_plus_10 - 10) . "'>result</td>";
            }
            echo "<td>" . $data2['team_2'] . "</td>
            </tr>";
            if ($count_plus_10 % 10 == 0) {
                echo "</table>";
            }
            if ($data1['id_winner'] != $data1['id_team_1'] && $data2['id_winner'] != $data2['id_team_2']) {
                $this->create_modal($count_plus_10 - 10);
            }
            $count_plus_10++;
        }
        echo '</div>';
        echo '<table id="ranking">
            <tr>
                <th>Rank</th>
                <th>Team</th>
                <th>Win(s)</th>
                <th>Loose(s)</th>
                <th>Point(s)</th>
                <th>HP+</th>
                <th>HP-</th>
            </tr>';

        $request = $this->db->query("SELECT * FROM teams INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team ORDER BY points DESC, `hp+` - `hp-` DESC, lost, name");
        $count_plus_10 = 1;
        $temp_pts = null;
        $temp_diff = null;
        $temp_rank = null;
        $temp_lost = null;
        while ($data = $request->fetch()) {
            if ($data['points'] == $temp_pts && $data['lost'] == $temp_lost && $data['hp+'] - $data['hp-'] == $temp_diff) {
                $rank = $temp_rank;
            } else {
                $rank = $count_plus_10;
            }
            echo "<tr><td class='ranking'>" . $rank . "</td>";
            echo "<td>" . $data['name'] . "</td><td>" . $data['win'] . "</td><td>" . $data['lost'] . "</td>";
            echo "<td>" . $data['points'] . "</td><td>" . $data['hp+'] . "</td><td>" . $data['hp-'] . "</td></tr>";
            $count_plus_10++;
            $temp_pts = $data['points'];
            $temp_diff = $data['hp+'] - $data['hp-'];
            $temp_lost = $data['lost'];
            $temp_rank = $rank;
        }

        echo '</table>';
    }

    function mirror($prev)
    {
        $tab_return = [];
        foreach ($prev as $key => $value) {
            if ($key % 2 == 0) {
                array_push($tab_return, $value - 1);
            } else {
                array_push($tab_return, $value + 1);
            }
        }
        return $tab_return;
    }

    function create_modal($id_match)
    {
        $request1 = $this->db->prepare("SELECT * FROM teams INNER JOIN planning ON teams.id_team=id_team_1 INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team WHERE id_match=:match");
        $request1->bindValue('match', $id_match);
        $request1->execute();
        $request2 = $this->db->prepare("SELECT * FROM teams INNER JOIN planning ON teams.id_team=id_team_2 INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team WHERE id_match=:match");
        $request2->bindValue('match', $id_match);
        $request2->execute();
        $data1 = $request1->fetch();
        $data2 = $request2->fetch();
        echo '<div class="modal fade" id="exampleModal' . $id_match . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Result</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h1>Who won ?</h1>
                        <p><label for="team1_' . $id_match . '">' . $data1['name'] . '</label>
                        <input type="radio" id="team1_' . $id_match . '" name="winner" onclick="check_confirm(' . $id_match . ')" value="' . $data1['id_team'] . '">
                        <input type="radio" id="team2_' . $id_match . '" name="winner" onclick="check_confirm(' . $id_match . ')" value="' . $data2['id_team'] . '">
                        <label for="team2_' . $id_match . '">' . $data2['name'] . '</label></p>
                        <h2>What\'s their HP ?</h2>
                        <p><input type="number" value=0 id="HP1_' . $id_match . '">
                        <input type="number" value=0 id="HP2_' . $id_match . '"></p>
                        <p>Day ' . floor(($id_match - 1) / 10 + 1) . ' Match ' . ($id_match % 10 == 0 ? 10 : $id_match % 10) . '</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="button_save_' . $id_match . '" onclick="new_score(' . $id_match . ')" disabled>Save changes</button>
                    </div>
                </div>
            </div>
        </div>';
    }

    function score_in_db($tab_Post)
    {
        $request = $this->db->prepare("UPDATE planning SET score_1=:score1, score_2=:score2, id_winner=:win WHERE id_match=:id_match");
        $request->bindValue('score1', $tab_Post['HP1']);
        $request->bindValue('score2', $tab_Post['HP2']);
        $request->bindValue('win', $tab_Post['WIN']);
        $request->bindValue('id_match', $tab_Post['id_match']);
        $request->execute();
        $team_1_infos = $this->db->prepare("SELECT * FROM teams WHERE id_team=:id");
        $team_1_infos->bindValue('id', $tab_Post['team1']);
        $team_1_infos->execute();
        $data1 = $team_1_infos->fetch(PDO::FETCH_ASSOC);
        $update_team_1 = $this->db->prepare("UPDATE teams SET win=:win, lost=:lost, points=:points, `hp+`=:hpplus, `hp-`=:hpminus WHERE id_team=:id");
        $update_team_1->bindValue('win', $tab_Post['WIN'] == $tab_Post['team1'] ? $data1['win'] + 1 : $data1['win']);
        $update_team_1->bindValue('lost', $tab_Post['WIN'] != $tab_Post['team1'] ? $data1['lost'] + 1 : $data1['lost']);
        $update_team_1->bindValue('points', $tab_Post['WIN'] == $tab_Post['team1'] ? $data1['points'] + 3 : $data1['points']);
        $update_team_1->bindValue('hpplus', $data1['hp+'] + $tab_Post['HP1']);
        $update_team_1->bindValue('hpminus', $data1['hp-'] + $tab_Post['HP2']);
        $update_team_1->bindValue('id', $tab_Post['team1']);
        $update_team_1->execute();

        $team_2_infos = $this->db->prepare("SELECT * FROM teams WHERE id_team=:id");
        $team_2_infos->bindValue('id', $tab_Post['team2']);
        $team_2_infos->execute();
        $data2 = $team_2_infos->fetch(PDO::FETCH_ASSOC);
        $update_team_2 = $this->db->prepare("UPDATE teams SET win=:win, lost=:lost, points=:points, `hp+`=:hpplus, `hp-`=:hpminus WHERE id_team=:id");
        $update_team_2->bindValue('win', $tab_Post['WIN'] == $tab_Post['team2'] ? $data2['win'] + 1 : $data2['win']);
        $update_team_2->bindValue('lost', $tab_Post['WIN'] != $tab_Post['team2'] ? $data2['lost'] + 1 : $data2['lost']);
        $update_team_2->bindValue('points', $tab_Post['WIN'] == $tab_Post['team2'] ? $data2['points'] + 3 : $data2['points']);
        $update_team_2->bindValue('hpplus', $data2['hp+'] + $tab_Post['HP2']);
        $update_team_2->bindValue('hpminus', $data2['hp-'] + $tab_Post['HP1']);
        $update_team_2->bindValue('id', $tab_Post['team2']);
        $update_team_2->execute();
        $this->affiche();
    }

    function end_turnament($tab_Post)
    {
        $rank_turnament = $this->db->query("SELECT * FROM teams INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team ORDER BY points DESC, `hp+` - `hp-` DESC, name");
        $i = 0;
        while ($result_team = $rank_turnament->fetch(PDO::FETCH_ASSOC)) {
            $current_stats_db = $this->db->prepare("SELECT * FROM total_points WHERE id_gen_team=:id");
            $current_stats_db->bindValue('id', $result_team['id_gen_team']);
            $current_stats_db->execute();
            $current_stats = $current_stats_db->fetch(PDO::FETCH_ASSOC);
            $final_update = $this->db->prepare("UPDATE total_points SET gen_win=:win, gen_lost=:lost, gen_points=:points, `gen_hp+`=:hpplus, `gen_hp-`=:hpminus, turnament_wins=:winner WHERE id_gen_team=:id");
            $final_update->bindValue('win', $current_stats['gen_win'] + $result_team['win']);
            $final_update->bindValue('lost', $current_stats['gen_lost'] + $result_team['lost']);
            $final_update->bindValue('points', $current_stats['gen_points'] + $result_team['points']);
            $final_update->bindValue('hpplus', $current_stats['gen_hp+'] + $result_team['hp+']);
            $final_update->bindValue('hpminus', $current_stats['gen_hp-'] + $result_team['hp-']);
            $final_update->bindValue('winner', $tab_Post['rank'][$i] == 1 ? $current_stats['turnament_wins'] + 1 : $current_stats['turnament_wins']);
            $final_update->bindValue('id', $result_team['id_gen_team']);
            $final_update->execute();
            $i++;
        }
        $ranking_gen = $this->db->query("SELECT * FROM total_points ORDER BY gen_points DESC, `gen_hp+` - `gen_hp-` DESC, gen_lost");
        echo "Classement général : \n";
        $k = 1;
        while ($data = $ranking_gen->fetch(PDO::FETCH_ASSOC)) {
            echo $k . " : " . $data['name'] . " avec " . $data['gen_points'] . " points !\n";
            $k++;
        }
    }

    function inDB($compte, $tabOfDuels)
    {
        for ($day = 1; $day < $compte; $day++) {
            $shuffledArray = $this->shuffle_assoc($tabOfDuels);
            foreach ($shuffledArray as $team => $duels) {
                if ($duels[$day] !== "0") {
                    $opponent = $duels[$day];
                    $id_team = Planning_generator::searchForName($team);
                    $id_opponent = Planning_generator::searchForName($opponent);
                    $request = $this->db->prepare("INSERT INTO planning (id_team_1, id_team_2, id_match) VALUES (:team1, :team2, null)");
                    $request->bindValue('team1', $id_team + 1);
                    $request->bindValue('team2', $id_opponent + 1);
                    $request->execute();
                }
            }
        }
    }
    function shuffle_assoc($list)
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
