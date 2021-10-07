<?php

Use Core\Core;

$language = Core::getLanguage();
$json = Core::getJsonFile();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <header>
        <h1><?=$json->string->welcome->$language?></h1>
    </header>
    <div id="left">
        <section id="tournaments" class="content"></section>
        <section id="tournamentInfo" class="content">
            
        </section>
    </div>
    <div id="right">
        <section id="create" class="content">
            <section id="sectionForTournamentName">
                <h1 class="alignCenter"><?=$json->create->title->$language?></h1>
                <label for="tournamentName"><?=$json->create->tournamentName->$language?></label>
                <input type="text" id="tournamentName" onkeyup="nameKeyUp(event)" onkeydown="nameKeyDown(event)" autocomplete="off">
                <section id="error"><p id="errorMessage">hidden<p></section>
            </section>
            <section id="infos">
                <label for="swissRound"><?=$json->possibleTournamentTypes->SwissRound->$language?></label>
                <input type="radio" name="tournamentType" id="swissRound" class="tournamentType" onclick='Tournament.typeTournament(event)' autocomplete="off">
                <label for="roundRobin"><?=$json->possibleTournamentTypes->RoundRobin->$language?></label>
                <input type="radio" name="tournamentType" id="roundRobin" class="tournamentType" onclick='Tournament.typeTournament(event)' autocomplete="off">
                <label for="singleBrackets"><?=$json->possibleTournamentTypes->SingleBrackets->$language?></label>
                <input type="radio" name="tournamentType" id="singleBrackets" class="tournamentType" onclick='Tournament.typeTournament(event)' autocomplete="off">
                <label for="doubleBrackets"><?=$json->possibleTournamentTypes->DoubleBrackets->$language?></label>
                <input type="radio" name="tournamentType" id="doubleBrackets" class="tournamentType" onclick='Tournament.typeTournament(event)' autocomplete="off">
                <div id="optionsDiv">
                    <h1 class="options" id="option"><?=$json->infos->options->$language?></h1>
                    <label for="randomOption" class="options optionsSingleBrackets optionsDoubleBrackets"><?=$json->options->randomOption->$language?></label>
                    <select id="randomOption" class="options optionsSingleBrackets optionsDoubleBrackets" autocomplete="off">
                        <option value="order"><?=$json->options->randomOption->options->order->$language?></option>
                        <option value="random"><?=$json->options->randomOption->options->random->$language?></option>
                    </select>
                    <label for="thirdPlaceOptions" class="options optionsSingleBrackets"><?=$json->options->thirdPlaceOptions->$language?></label>
                    <select id="thirdPlaceOptions" class="options optionsSingleBrackets" autocomplete="off">
                        <option value="on"><?=$json->options->thirdPlaceOptions->options->on->$language?></option>
                        <option value="off" selected><?=$json->options->thirdPlaceOptions->options->off->$language?></option>
                    </select>
                    <label for="winType" class="options optionsSingleBrackets optionsDoubleBrackets optionsSwissRound optionsRoundRobin"><?=$json->options->winType->$language?></label>
                    <select id="winType" class="options optionsSingleBrackets optionsDoubleBrackets optionsSwissRound optionsRoundRobin" onchange="winChange(event)" autocomplete="off">
                        <option value="score" selected><?=$json->options->winType->options->score->$language?></option>
                        <option value="designed"><?=$json->options->winType->options->designed->$language?></option>
                    </select>
                    <label for="bestOf" class="options optionsSingleBrackets optionsDoubleBrackets optionsSwissRound optionsRoundRobin"><?=$json->options->bestOf->$language?></label>
                    <select id="bestOf" class="options optionsSingleBrackets optionsDoubleBrackets optionsSwissRound optionsRoundRobin" autocomplete="off" disabled>
                        <option value="1" selected><?=$json->options->bestOf->diminutive->$language?>1</option>
                        <option value="3"><?=$json->options->bestOf->diminutive->$language?>3</option>
                        <option value="5"><?=$json->options->bestOf->diminutive->$language?>5</option>
                        <option value="7"><?=$json->options->bestOf->diminutive->$language?>7</option>
                    </select>
                    <label for="drawOption" class="options optionsSwissRound optionsRoundRobin"><?=$json->options->drawOption->$language?></label>
                    <input type="checkbox" id="drawOption" class="options optionsSwissRound optionsRoundRobin" onchange="drawChange(event)" autocomplete="off">
                    <label for="winPoints" class="options optionsSwissRound optionsRoundRobin"><?=$json->options->winPoints->$language?></label>
                    <select id="winPoints" class="options optionsSwissRound optionsRoundRobin">
                        <?php 
                            for ($i = 1; $i <= 100; $i++){
                                if ($i === 3){
                                    ?><option value=<?=$i?> selected><?=$i . $json->options->pointsDiminutive->multi->$language?></option><?php
                                } else if ($i === 1){
                                    ?><option value=<?=$i?>><?=$i . $json->options->pointsDiminutive->single->$language?></option><?php
                                } else {
                                    ?><option value=<?=$i?>><?=$i . $json->options->pointsDiminutive->multi->$language?></option><?php
                                }
                            }
                        ?>
                    </select>
                    <label for="drawPoints" class="options optionsSwissRound optionsRoundRobin"><?=$json->options->drawPoints->$language?></label>
                    <select id="drawPoints" class="options optionsSwissRound optionsRoundRobin" disabled>
                        <?php 
                            for ($i = 1; $i <= 100; $i++){
                                if ($i === 1){
                                    ?><option value=<?=$i?> selected><?=$i . $json->options->pointsDiminutive->single->$language?></option><?php
                                } else {
                                    ?><option value=<?=$i?>><?=$i . $json->options->pointsDiminutive->multi->$language?></option><?php
                                }
                            }
                        ?>
                    </select>
                    <label for="qualify" class="options optionsSwissRound"><?=$json->options->qualify->$language?></label>
                    <select id="qualify" class="options optionsSwissRound">
                    <?php 
                            for ($i = 1; $i <= 300; $i++){
                                if ($i === 9){
                                    ?><option value=<?=$i?> selected><?=$i . $json->options->pointsDiminutive->multi->$language?></option><?php
                                } else if ($i === 1) {
                                    ?><option value=<?=$i?>><?=$i . $json->options->pointsDiminutive->single->$language?></option><?php
                                } else {
                                    ?><option value=<?=$i?>><?=$i . $json->options->pointsDiminutive->multi->$language?></option><?php
                                }
                            }
                        ?>
                    </select>
                </div>
            </section>
            <section id="participants">
                <p for="participantsName"><?=$json->create->participantName->$language?></p>
                <div id="participantsName">
                    <label for="number1">1</label>
                    <input type="text" id="number1" class="name" autocomplete="off" onkeyup="keyIsUp(event)" onkeydown="keyIsDown(event)" onfocusout="focusIsOut(event)">
                </div>
            </section>
            <section id="confirm">
                <button id="addButton" onclick='Tournament.addTournament()'>Add</button>
                <button onclick='Tournament.deleteTournament()'>DeleteAll</button>
            </section>
        </section>
    </div>
</body>

</html>