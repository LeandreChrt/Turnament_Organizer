<?php

$sudoku = [
    [1,0,0],
    [0,0,0],
    [0,0,3]
];

$sudoku = resolve($sudoku);

function resolve($sudoku){
    foreach ($sudoku as $ligne => $tab_ligne){
        foreach ($tab_ligne as $colonne => $chiffre){
            // echo $ligne . " => " . $colonne . " => " . $chiffre . "\n";
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
    return $sudoku;
}

var_dump($sudoku);