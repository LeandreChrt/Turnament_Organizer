<?php

// $sudoku = [
//     [0,0],
//     [0,0]
// ];
// $sudoku = [
//     [0,0,0],
//     [0,0,0],
//     [0,0,0]
// ];
// $sudoku = [
//     [0,0,0,0],
//     [0,0,0,0],
//     [0,0,0,0],
//     [0,0,0,0]
// ];
$sudoku = [
    [1,2,0,0,0],
    [0,0,0,0,0],
    [0,0,0,0,0],
    [0,0,0,0,0],
    [0,0,0,0,0]
];
$appel = true;
$i = 2;
$sudoku = resolve($sudoku);

function resolve($sudoku){
    global $appel;
    global $i;
    foreach ($sudoku as $ligne => $tab_ligne){
        foreach ($tab_ligne as $colonne => $chiffre){
            if ($chiffre == 0){
                $valeurs_dispo = range(1,count($sudoku));
                $valeur_use = [];
                foreach (range(0,count($sudoku)-1) as $num_ligne_col){
                    if ($num_ligne_col == $ligne){
                        foreach ($sudoku[$num_ligne_col] as $chiffre_test){
                            if (in_array($chiffre_test, $valeurs_dispo)){
                                array_push($valeur_use, $chiffre_test);
                            }
                        }
                    }
                    if ($num_ligne_col == $colonne){
                        foreach ($sudoku as $tab_temp){
                            if (in_array($tab_temp[$num_ligne_col], $valeurs_dispo)){
                                array_push($valeur_use, $tab_temp[$num_ligne_col]);
                            }
                        }
                    }
                }
                $valeurs_possible = array_diff($valeurs_dispo, $valeur_use);
                sort($valeurs_possible);
                if (count($valeurs_possible) == 1){
                    $sudoku[$ligne][$colonne] = $valeurs_possible[0];
                    $sudoku = resolve($sudoku);
                    break 2;
                }
            }
        }
    }
    foreach ($sudoku as $row){
        if (in_array(0, $row)){
            $sudoku_de_suppositions = $sudoku;
            $appel=false;
            $sudoku = resolve_suppose($sudoku_de_suppositions,$i);
            break;
        }
    }
    return $sudoku;
}

function resolve_suppose($sudoku,&$i){
    $test_if_good = [];
    foreach ($sudoku as $ligne){
        if (!in_array(0,$ligne)){
            array_push($test_if_good, 1);
        }
        else {
            array_push($test_if_good, 0);
        }
    }
    if (!in_array(0, $test_if_good)){
        return $sudoku;
    }
    foreach ($sudoku as $ligne => $tab_ligne){
        foreach ($tab_ligne as $colonne => $chiffre){
            if ($chiffre == 0){
                $valeurs_dispo = range(1,count($sudoku));
                $valeur_use = [];
                foreach (range(0,count($sudoku)-1) as $num_ligne_col){
                    if ($num_ligne_col == $ligne){
                        foreach ($sudoku[$num_ligne_col] as $chiffre_test){
                            if (in_array($chiffre_test, $valeurs_dispo)){
                                array_push($valeur_use, $chiffre_test);
                            }
                        }
                    }
                    if ($num_ligne_col == $colonne){
                        foreach ($sudoku as $tab_temp){
                            if (in_array($tab_temp[$num_ligne_col], $valeurs_dispo)){
                                array_push($valeur_use, $tab_temp[$num_ligne_col]);
                            }
                        }
                    }
                }
                $valeurs_possible = array_diff($valeurs_dispo, $valeur_use);
                sort($valeurs_possible);
                var_dump($valeurs_possible);
                if (count($valeurs_possible) == 1){
                    $sudoku[$ligne][$colonne] = $valeurs_possible[0];
                    $sudoku = resolve_suppose($sudoku,$i);
                    return $sudoku;
                }
                elseif (count($valeurs_possible) == $i){
                    foreach ($valeurs_possible as $temp_valeur){
                        $sudoku[$ligne][$colonne] = $temp_valeur;
                        $sudoku = resolve_suppose($sudoku, $i);
                        $test = 0;
                        foreach ($sudoku as $row){
                            if (in_array(0, $row)){
                                $sudoku[$ligne][$colonne] = 0;
                                break;
                            }
                            else {
                                $test++;
                            }
                            if ($test == count($sudoku)){
                                break 3;
                            }
                        }
                    }
                }
            }
        }
    }
    foreach ($sudoku as $row){
        if (in_array(0, $row)){
            $erreur = true;
            break;
        }
        else {
            $erreur = false;
        }
    }
    if ($erreur == true){
        if ($i < count($sudoku)+1){
            $i++;
            $sudoku = resolve_suppose($sudoku, $i);
        }
    }
    return $sudoku;
}

foreach ($sudoku as $row => $row_tab){
    if ($row == 0){
        echo "+-+-+-+\n";
    }
    foreach ($row_tab as $col => $col_value){
        echo "|" . $col_value;
        if ($col == 2){
            echo "|\n";
        } 
    }
    echo "+-+-+-+\n";
}