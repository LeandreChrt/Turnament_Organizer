function start() {
    $.ajax({
        type: 'post',
        url: 'Controller.php',
        data: {
            start: 'start'
        },
        success: function (response) {
            $('body').html(response);
        }
    });
}

function load() {
    $.ajax({
        type: 'post',
        url: 'Controller.php',
        data: {
            load: 'load'
        },
        success: function (response) {
            $('body').html(response);
        }
    });
}

function new_score(id_match) {
    if (document.getElementById('HP1_'+id_match).value != '' && document.getElementById('HP2_'+id_match).value != ''){
        if (document.getElementById('HP1_'+id_match).value.match(/^([0-9]+)$/) && document.getElementById('HP2_'+id_match).value.match(/^([0-9]+)$/)){
            var HP1 = document.getElementById('HP1_'+id_match).value
            var HP2 = document.getElementById('HP2_'+id_match).value
            var team1 = document.getElementById('team1_'+id_match).value
            var team2 = document.getElementById('team2_'+id_match).value
            var WIN = $("#team1_"+id_match)[0].checked ? document.getElementById('team1_'+id_match).value : document.getElementById('team2_'+id_match).value
            $.ajax({
                type: 'post',
                url: 'Controller.php',
                data: {
                    id_match: id_match,
                    HP1: HP1,
                    HP2: HP2,
                    WIN: WIN,
                    team1: team1,
                    team2: team2
                },
                success: function (response) {
                    $('body').html(response);
                    if (document.querySelectorAll('.modal').length == 0){
                        $('#ranking')[0].innerHTML += '<button onclick="end_turnament()">Turnament\'s end</button>'
                    }
                }
            });
        }
        else {
            alert('HP\'s should be written as an integer')
        }
    }
    else {
        alert('Please, fill in the HP')
    }
}

function check_confirm(id_match) {
    document.getElementById('button_save_'+id_match).disabled = false
}

function end_turnament() {
    ranking = document.querySelectorAll('.ranking')
    ranking = Array.from(ranking)
    let true_ranking = []
    ranking.forEach(e => {
        true_ranking.push(e.innerHTML)
    });
    $.ajax({
        type: 'post',
        url: 'Controller.php',
        data: {
            end: 'end',
            rank: true_ranking
        },
        success: function (response) {
            alert(response)
            document.location.reload();
        }
    });
}