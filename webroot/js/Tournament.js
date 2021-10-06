const Tournament = {

    existingTournamentsName: null,
    existingTournamentsProgram: null,
    language: location.pathname.substr(1, 2),
    jsonFile: null,
    getTournaments: function () {
        var values = []
        var keysReturn = []
        keys = Object.keys(localStorage)
        i = keys.length;
        while (i--) {
            if (keys[i].match(/tournamentCustom\((.*?)\)/)) {
                var tournament = JSON.parse(localStorage.getItem(keys[i]))
                values.push(tournament);
                keysReturn.push(tournament.name)
            }
        }
        this.existingTournamentsName = keysReturn
        this.existingTournamentsProgram = values
        this.loadTournaments()
    },
    loadTournaments: function () {
        if (this.jsonFile === null){
            throw new Error("JSON file not returned");
        }
        $("#tournaments").html("")
        $("#tournamentInfo").html("<h1 class='alignCenter' id='noSelectedTournament'>" + this.jsonFile.error.tournamentInfo[this.language] + "</h1>")
        if (this.existingTournamentsName.length === 0) {
            $("#tournaments").html("<h1 class='alignCenter'>" + this.jsonFile.error.tournaments[this.language] + "</h1>")
        }
        this.existingTournamentsName.forEach(element => {
            $("#tournaments").append("<p class='tournamentName' onclick='Tournament.tournamentInfos(\"" + element + "\")'>" + element + "</p>")
        });
    },
    addTournament: function () {
        var newTournament = this.escapeHtml($("#tournamentName")[0].value.trim())
        var tournamentType = null;
        for (h = 0; h < $(".tournamentType").length; h++) {
            if ($(".tournamentType")[h].checked) {
                tournamentType = $(".tournamentType")[h].id
                tournamentType = tournamentType.charAt(0).toUpperCase() + tournamentType.slice(1);
            }
        }
        console.log(tournamentType)
        if (newTournament === "") {
            $("#errorMessage").html(this.jsonFile.error.tournamentName[this.language]);
            $("#error").css("visibility", "visible")
        } else if (this.existingTournamentsName.includes(newTournament)) {
            $("#errorMessage").html(this.jsonFile.error.alreadyNamed[this.language])
            $("#error").css("visibility", "visible")
        } else if (tournamentType === null) {
            $("#errorMessage").html(this.jsonFile.error.tournamentType[this.language])
            $("#error").css("visibility", "visible")
        } else if ($(".name").length < 3) {
            $("#errorMessage").html(this.jsonFile.error.minimumPlayers[this.language])
            $("#error").css("visibility", "visible")
        } else {
            var listOfNames = {}
            var listOfOptions = {}
            var queryOptions = $(".options" + tournamentType)
            for (i = 1; i < $(".name").length; i++) {
                if (Object.values(listOfNames).includes($(".name")[i - 1].value)) {
                    $("#errorMessage").html(this.jsonFile.error.similarName[this.language])
                    $("#error").css("visibility", "visible")
                    return
                } else {
                    listOfNames[i] = this.escapeHtml($(".name")[i - 1].value.trim())
                }
            }
            for (j = 0; j < queryOptions.length; j++) {
                if (queryOptions[j].tagName === "INPUT") {
                    listOfOptions[queryOptions[j].id] = queryOptions[j].checked
                } else if (queryOptions[j].id === "bestOf") {
                    if (listOfOptions.winType !== "Score") {
                        listOfOptions[queryOptions[j].id] = queryOptions[j].value
                    }
                } else if (queryOptions[j].id === "drawPoints") {
                    if (listOfOptions.drawOption === true) {
                        listOfOptions[queryOptions[j].id] = queryOptions[j].value
                    }
                } else {
                    listOfOptions[queryOptions[j].id] = queryOptions[j].value
                }
            }
            var name = "tournamentCustom(" + newTournament + ")"
            var json = {
                "name": newTournament,
                "type": tournamentType,
                "participants": listOfNames,
                "options": listOfOptions
            }
            localStorage.setItem(name, JSON.stringify(json))
            $("#errorMessage").html("hidden")
            $("#error").css("visibility", "hidden")
            $("#tournamentName")[0].value = ""
            this.getTournaments()
        }
    },
    deleteTournament: function () {
        this.existingTournamentsName.forEach(name => {
            localStorage.removeItem("tournamentCustom(" + name + ")")
        })
        $("#error").css("visibility", "hidden")
        $("#tournamentInfo").html("<h1 class='alignCenter'>" + this.jsonFile.error.tournaments[this.language] + "</h1>")
        this.getTournaments()
    },
    tournamentInfos: function (name) {
        var left = "<section class='infosSection'>"
        var right = "<section class='infosSection'>"
        var type;
        var participants;
        var options;
        var bottom = "<button>Continue</button><button>Delete</button>"
        index = this.existingTournamentsName.indexOf(this.escapeHtml(name))
        infos = this.existingTournamentsProgram[index];
        Object.keys(infos).forEach(key => {
            switch (key) {
                case "type":
                    type = infos[key];
                    break;
                case "name":
                    name = infos[key];
                    break;
                case "participants":
                    participants = infos[key];
                    break;
                case "options":
                    options = infos[key];
                    break;
            }
        })
        right += "<h3 class='alignCenter'>Participants</h3>"

        left += "<p><strong>" + this.jsonFile.infos.name[this.language] + "</strong> : " + name + "</br>"
        left += "<strong>" + this.jsonFile.infos.type[this.language] + "</strong> : " + this.jsonFile.possibleTournamentTypes[type][this.language] + "</p>"
        left += "<p class='alignCenter'><strong>" + this.jsonFile.infos.options[this.language] + "</strong></p><p>"
        Object.keys(options).forEach(key => {
            left += "<strong>" + this.jsonFile.options[key][this.language] + "</strong> : " + options[key] + "</br>"
        });
        left += "</p>"

        right += "</section>"
        left += "</section>"
        $("#tournamentInfo").html(left + right + bottom)
    },
    typeTournament: function (event) {
        type = event.target.id
        type = type.charAt(0).toUpperCase() + type.slice(1);
        $(".options").css("display", "none")
        $("#option, .options" + type).css("display", "block")
    },
    escapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function (m) { return map[m]; });
    }
}