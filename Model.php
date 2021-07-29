<?php

class Tournoi
{

    function __construct()
    {
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=turnament;charset=utf8', 'newuser', 'password');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $this->bdd = $bdd;
    }

    function start()
    {
        $table = $this->bdd->query("SHOW TABLES LIKE 'teams'");
        if ($donnees = $table->fetch()) {
            $this->bdd->query("DROP TABLE teams");
        }
        $this->bdd->query("CREATE TABLE teams (
            id_team int PRIMARY KEY NOT NULL AUTO_INCREMENT,
            id_gen_team int,
            win smallint,
            lost smallint,
            points smallint,
            `pv+` smallint,
            `pv-` smallint
        );");

        $table = $this->bdd->query("SHOW TABLES LIKE 'planning'");
        if ($donnees = $table->fetch()) {
            $this->bdd->query("DROP TABLE planning");
        }
        $this->bdd->query("CREATE TABLE planning (
            id_match int PRIMARY KEY NOT NULL AUTO_INCREMENT,
            id_team_1 tinyint,
            score_1 smallint,
            id_team_2 tinyint,
            score_2 smallint,
            id_winner tinyint
        );");

        $tab_des_teams = [];
        $requete = $this->bdd->query("SELECT * FROM total_points");
        while ($donnees = $requete->fetch()) {
            array_push($tab_des_teams, $donnees['id_gen_team']);
        }
        shuffle($tab_des_teams);
        foreach ($tab_des_teams as $team) {
            $requete = $this->bdd->prepare("INSERT INTO teams VALUES (null, :team, 0, 0, 0, 0, 0)");
            $requete->bindValue('team', $team);
            $requete->execute();
        }
        $this->planning();
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

        $tab_des_adversaires = [$a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n, $o, $p, $q, $r, $s, $t];
        for ($j = 0; $j < 19; $j++) {
            $tab_temp = range(0, 19);
            for ($k = 0; $k < 10; $k++) {
                $int_select = rand(0, count($tab_temp) - 1);
                $nbr_team_dom = $tab_temp[$int_select];
                $nbr_team_ext = $tab_des_adversaires[$nbr_team_dom][$j];
                unset($tab_temp[$int_select]);
                unset($tab_temp[array_search($nbr_team_ext, $tab_temp)]);
                sort($tab_temp);
                if ($nbr_team_ext == 0) {
                    $nbr_team_ext = 20;
                }
                if ($nbr_team_dom == 0) {
                    $nbr_team_dom = 20;
                }
                $requete = $this->bdd->prepare("INSERT INTO planning (id_team_1, id_team_2, id_match) VALUES (:team1, :team2, null)");
                $requete->bindValue('team1', $nbr_team_dom);
                $requete->bindValue('team2', $nbr_team_ext);
                $requete->execute();
            }
        }
        $this->affiche();
    }

    function affiche()
    {
        $requete1 = $this->bdd->query("SELECT name AS team_1, id_winner, score_1, id_team_1 FROM planning INNER JOIN teams ON id_team_1=teams.id_team INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team");
        $requete2 = $this->bdd->query("SELECT name AS team_2, id_winner, score_2, id_team_2 FROM planning INNER JOIN teams ON id_team_2=teams.id_team INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team");
        $compte = 11;
        echo "<div id='planning'>";
        while ($donnees1 = $requete1->fetch()) {
            if (($compte - 1) % 10 === 0) {
                echo "<table id='J" . round($compte / 10) . "'>";
            }
            $donnees2 = $requete2->fetch();
            // echo "<tr id='".($compte-10)."'><td class='left'>" . $donnees1['team_1'] . "</td><td class=center onclick='enter_result(\"".($compte-10)."\")'> VS </td><td class='right'>" . $donnees2['team_2'] . "</td></tr>";
            echo "<tr id='" . ($compte - 10) . "'>
                <td>" . $donnees1['team_1'] . "</td>";
                if ($donnees1['id_winner'] == $donnees1['id_team_1']){
                    echo "<td><strong>".$donnees1['score_1']."</strong>-".$donnees2['score_2']."</td>";
                }
                elseif ($donnees2['id_winner'] == $donnees2['id_team_2']){
                    echo "<td>".$donnees1['score_1']."-<strong>".$donnees2['score_2']."</strong></td>";
                }
                else {
                    echo "<td class='center' data-bs-toggle='modal' data-bs-target='#exampleModal".($compte - 10)."'>result</td>";
                }
                echo "<td>" . $donnees2['team_2'] . "</td>
            </tr>";
            if ($compte % 10 == 0) {
                echo "</table>";
            }
            if ($donnees1['id_winner'] != $donnees1['id_team_1'] && $donnees2['id_winner'] != $donnees2['id_team_2']){
                $this->create_modal($compte-10);
            }
            $compte++;
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
        
        $requete = $this->bdd->query("SELECT * FROM teams INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team ORDER BY points DESC, lost, `pv+` - `pv-` DESC, name");
        $compte = 1;
        $temp_pts = null;
        $temp_diff = null;
        $temp_rank = null;
        $temp_lost = null;
        while ($donnees = $requete->fetch()){
            if ($donnees['points'] == $temp_pts && $donnees['lost'] == $temp_lost && $donnees['pv+'] - $donnees['pv-'] == $temp_diff){
                $rank = $temp_rank;
            }
            else {
                $rank = $compte;
            }
            echo "<tr><td class='ranking'>".$rank."</td>";
            echo "<td>".$donnees['name']."</td><td>".$donnees['win']."</td><td>".$donnees['lost']."</td>";
            echo "<td>".$donnees['points']."</td><td>".$donnees['pv+']."</td><td>".$donnees['pv-']."</td></tr>";
            $compte++;
            $temp_pts = $donnees['points'];
            $temp_diff = $donnees['pv+'] - $donnees['pv-'];
            $temp_lost = $donnees['lost'];
            $temp_rank = $rank;
        }

        echo '</table>';
    }

    function mirror($prec)
    {
        $tab_return = [];
        foreach ($prec as $key => $value) {
            if ($key % 2 == 0) {
                array_push($tab_return, $value - 1);
            } else {
                array_push($tab_return, $value + 1);
            }
        }
        return $tab_return;
    }

    function create_modal($id_match){
        $requete1 = $this->bdd->prepare("SELECT * FROM teams INNER JOIN planning ON teams.id_team=id_team_1 INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team WHERE id_match=:match");
        $requete1->bindValue('match', $id_match);
        $requete1->execute();
        $requete2 = $this->bdd->prepare("SELECT * FROM teams INNER JOIN planning ON teams.id_team=id_team_2 INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team WHERE id_match=:match");
        $requete2->bindValue('match', $id_match);
        $requete2->execute();
        $donnees1 = $requete1->fetch();
        $donnees2 = $requete2->fetch();
        echo '<div class="modal fade" id="exampleModal'.$id_match.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h1>Who won ?</h1>
                        <p><label for="team1_'.$id_match.'">'.$donnees1['name'].'</label>
                        <input type="radio" id="team1_'.$id_match.'" name="winner" onclick="check_confirm('.$id_match.')" value="'.$donnees1['id_team'].'">
                        <input type="radio" id="team2_'.$id_match.'" name="winner" onclick="check_confirm('.$id_match.')" value="'.$donnees2['id_team'].'">
                        <label for="team2_'.$id_match.'">'.$donnees2['name'].'</label></p>
                        <h2>What\'s their HP ?</h2>
                        <p><input type="number" value=0 id="HP1">
                        <input type="number" value=0 id="HP2"></p>
                        <p>Jour '.floor(($id_match-1)/10+1).' Match '.($id_match%10==0 ? 10 : $id_match%10).'</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="button_save_'.$id_match.'" onclick="new_score('.$id_match.')" disabled>Save changes</button>
                    </div>
                </div>
            </div>
        </div>';
    }

    function score_enregistre($tab_Post)
    {
        $requete = $this->bdd->prepare("UPDATE planning SET score_1=:score1, score_2=:score2, id_winner=:win WHERE id_match=:id_match");
        $requete->bindValue('score1', $tab_Post['HP1']);
        $requete->bindValue('score2', $tab_Post['HP2']);
        $requete->bindValue('win', $tab_Post['WIN']);
        $requete->bindValue('id_match', $tab_Post['id_match']);
        $requete->execute();
        $infos_team_1 = $this->bdd->prepare("SELECT * FROM teams WHERE id_team=:id");
        $infos_team_1->bindValue('id', $tab_Post['team1']);
        $infos_team_1->execute();
        $donnees1 = $infos_team_1->fetch(PDO::FETCH_ASSOC);
        $update_team_1 = $this->bdd->prepare("UPDATE teams SET win=:win, lost=:lost, points=:points, `pv+`=:pvplus, `pv-`=:pvminus WHERE id_team=:id");
        $update_team_1->bindValue('win', $tab_Post['WIN'] == $tab_Post['team1'] ? $donnees1['win'] + 1 : $donnees1['win']);
        $update_team_1->bindValue('lost', $tab_Post['WIN'] != $tab_Post['team1'] ? $donnees1['lost'] + 1 : $donnees1['lost']);
        $update_team_1->bindValue('points', $tab_Post['WIN'] == $tab_Post['team1'] ? $donnees1['points'] + 3 : $donnees1['points']);
        $update_team_1->bindValue('pvplus', $donnees1['pv+'] + $tab_Post['HP1']);
        $update_team_1->bindValue('pvminus', $donnees1['pv-'] + $tab_Post['HP2']);
        $update_team_1->bindValue('id', $tab_Post['team1']);
        $update_team_1->execute();

        $infos_team_2 = $this->bdd->prepare("SELECT * FROM teams WHERE id_team=:id");
        $infos_team_2->bindValue('id', $tab_Post['team2']);
        $infos_team_2->execute();
        $donnees1 = $infos_team_2->fetch(PDO::FETCH_ASSOC);
        $update_team_2 = $this->bdd->prepare("UPDATE teams SET win=:win, lost=:lost, points=:points, `pv+`=:pvplus, `pv-`=:pvminus WHERE id_team=:id");
        $update_team_2->bindValue('win', $tab_Post['WIN'] == $tab_Post['team2'] ? $donnees1['win'] + 1 : $donnees1['win']);
        $update_team_2->bindValue('lost', $tab_Post['WIN'] != $tab_Post['team2'] ? $donnees1['lost'] + 1 : $donnees1['lost']);
        $update_team_2->bindValue('points', $tab_Post['WIN'] == $tab_Post['team2'] ? $donnees1['points'] + 3 : $donnees1['points']);
        $update_team_2->bindValue('pvplus', $donnees1['pv+'] + $tab_Post['HP2']);
        $update_team_2->bindValue('pvminus', $donnees1['pv-'] + $tab_Post['HP1']);
        $update_team_2->bindValue('id', $tab_Post['team2']);
        $update_team_2->execute();
        $this->affiche();
    }

    function end_turnament($tab_Post){
        $rank_turnament = $this->bdd->query("SELECT * FROM teams INNER JOIN total_points ON teams.id_gen_team=total_points.id_gen_team ORDER BY points DESC, `pv+` - `pv-` DESC, name");
        $i = 0;
        while ($result_team = $rank_turnament->fetch(PDO::FETCH_ASSOC)){
            $current_stats_db = $this->bdd->prepare("SELECT * FROM total_points WHERE id_gen_team=:id");
            $current_stats_db->bindValue('id', $result_team['id_gen_team']);
            $current_stats_db->execute();
            $current_stats = $current_stats_db->fetch(PDO::FETCH_ASSOC);
            $update_final = $this->bdd->prepare("UPDATE total_points SET gen_win=:win, gen_lost=:lost, gen_points=:points, `gen_pv+`=:pvplus, `gen_pv-`=:pvminus, turnament_wins=:winner WHERE id_gen_team=:id");
            $update_final->bindValue('win', $current_stats['gen_win'] + $result_team['win']);
            $update_final->bindValue('lost', $current_stats['gen_lost'] + $result_team['lost']);
            $update_final->bindValue('points', $current_stats['gen_points'] + $result_team['points']);
            $update_final->bindValue('pvplus', $current_stats['gen_pv+'] + $result_team['pv+']);
            $update_final->bindValue('pvminus', $current_stats['gen_pv-'] + $result_team['pv-']);
            $update_final->bindValue('winner', $tab_Post['rank'][$i] == 1 ? $current_stats['turnament_wins'] + 1 : $current_stats['turnament_wins']);
            $update_final->bindValue('id', $result_team['id_gen_team']);
            $update_final->execute();
            $i++;
        }
        $ranking_gen = $this->bdd->query("SELECT * FROM total_points ORDER BY gen_points DESC, gen_lost, `gen_pv+` - `gen_pv-` DESC");
        echo "Classement général : \n";
        $k = 1;
        while ($donnees = $ranking_gen->fetch(PDO::FETCH_ASSOC)){
            echo $k . " : " . $donnees['name'] . " avec " . $donnees['gen_points'] . " points !\n";
            $k++;
        }
    }
}
