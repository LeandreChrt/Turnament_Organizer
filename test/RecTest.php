<?php

$nbr = 1;
test();

function test(){
    global $nbr;
    if ($nbr === 1){
        $nbr = 2;
        test();
    } elseif ($nbr === 2){
        $nbr = 3;
        test();
    }
    echo $nbr . "\n";
}