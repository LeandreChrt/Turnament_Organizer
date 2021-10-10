$("document").ready(function () {
    $.getJSON("/webroot/js/File.json", function (data) {
        Tournament.jsonFile = data
        Tournament.getTournaments()
    })
})
function nameKeyDown (event) {
    var target = event.target
    var key = event.key
    var array = [" ", "Tab"]
    if (target.value === "" && array.includes(key)) {
        event.preventDefault()
    }
}
function nameKeyUp (event) {
    var target = event.target
    if (target.value.trim() === "") {
        target.value = ""
    }
}
function drawChange (event) {
    if (event.target.checked) {
        $("#drawPoints")[0].disabled = false
    } else {
        $("#drawPoints")[0].disabled = true
    }
}
function keyIsUp(event) {
    target = event.target
    if (target.value.trim() === "") {
        target.value = ""
        if (target === $(".name")[$(".name").length - 2]) {
            var lastInput = $(".name").last()
            var lastLabel = lastInput.prev()
            lastInput.remove()
            lastLabel.remove()
        }
    } else if (target === $(".name")[$(".name").length - 1]) {
        number = $(".name").length + 1
        $("#participantsName").append("<label for='number" + number + "'>" + number + "</label><input type='text' id='number" + number + "' class='name' autocomplete='off' onkeyup='keyIsUp(event)' onkeydown='keyIsDown(event)' onfocusout='focusIsOut(event)'>")
    }
}
function keyIsDown(event) {
    var target = event.target
    var key = event.key
    var array = [" ", "Tab"]
    if (target.value === "" && array.includes(key)) {
        event.preventDefault()
    } else if (target.value !== "" && key === "Enter") {
        $(".name").last().focus()
    } else if (target.value !== "" && key === "ArrowDown") {
        target.nextElementSibling.nextElementSibling.focus()
    } else if (key === "ArrowUp" && target.id !== "number1") {
        target.previousElementSibling.previousElementSibling.focus()
    }
}
function focusIsOut(event) {
    if (event.target !== $(".name")[$(".name").length - 1] && event.target.value.trim() === "") {
        $("#participantsName label").remove()
        event.target.remove()
        for (i = 1; i <= $(".name").length; i++) {
            $(".name")[i - 1].setAttribute("id", "number" + i)
            var newItem = document.createElement("LABEL");
            var textnode = document.createTextNode(i);
            newItem.appendChild(textnode);
            newItem.setAttribute("for", "number" + i)
            $("#participantsName")[0].insertBefore(newItem, $(".name")[i - 1])
        }
    }
}

function winChange(event) {
    if (event.target.value === "score") {
        $("#bestOf").append("<option value='homeAndAway' id='homeAndAway'>" + Tournament.jsonFile.options.bestOf.homeAndAway[Tournament.language] + "</option>")
    } else {
        $("option").remove("#homeAndAway")
    }
}