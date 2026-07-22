function loadRace(target) {
    $.get(
        "/scripts/constructors/race.php", {target: target},
        function(response) {
            $("#racebox").html(response);
            updateButton(target);

            document.title = $("#racename").data("name") + " - Races - Azzy's Chaos Corner"
        }
    )
}

function updateButton(button) {
    $(".button-list").removeClass("active");
    $(`#${button}`).addClass("active");
}

$(function () {
    let race = location.hash.substring(1);

    if (race) {
        loadRace(race);
    } else if (button) {
        updateButton($(`#race-display`).attr("race"));
    }
})

$(".button-list").click(function (e) {
    e.preventDefault();

    let race = $(this).attr("id");
    history.pushState(null, "", "#" + race);

    loadRace(race);
});


function filterSpell () {
    let search = $("#spell-searchbar").val().toLowerCase();

    $(".button-list").each(function() {
        let name = $(this).data("name").toLowerCase();
        let prop = [
            $(this).data("level").toString(),
            $(this).data("school").toLowerCase(),
            $(this).data("source"),
            ...$(this).data("lists").toLowerCase().split(" ")
        ];
        let hidden = false;

        // filter options that can be filtered AND/OR
        let elAndOr = [
            ".button-toggle-2.toggle-school"
        ];

        let [operand] = getLogicOp();

        elAndOr.forEach(element => {
            let [wl, bl] = getFilters(element);

            if (operand == "and") {
                hidden = applyAnd(prop, wl, bl, operand, hidden);
            } else if (operand == "or") {
                hidden = applyOr(prop, wl, bl, operand, hidden);
            }
        });

        // filter options that can only be filtered OR; 
        // filtering AND would be redundant as these can only have 1 value
        let elOr = [
            ".button-toggle-2.toggle-classlist",
            ".button-toggle-2.toggle-level",
            ".button-toggle-2.toggle-source"
        ]

        elOr.forEach(element => {
            let [wl, bl] = getFilters(element);

            hidden = applyOr(prop, wl, bl, operand, hidden);
        });

        // filter searchbar contents regardless of other active filters
        if (!name.includes(search)) {
            hidden = true;
        }

        $(this).toggleClass("d-none", hidden);
    });
}

function applyAnd (prop, wl, bl, operand, hidden) {
    if (hidden == false) {

        if (wl.length > 0) {
            hidden = true;
            if (wl.every(filter => prop.includes(filter))) {
                hidden = false;
            }
        }
        
        if (bl.some(filter => prop.includes(filter))) {
            hidden = true;
        }
    }

    return hidden;
}

function applyOr (prop, wl, bl, operand, hidden) {
    if (hidden == false) {
    
        if (wl.length > 0) {
            hidden = true;
            if (wl.some(filter => prop.includes(filter))) {
                hidden = false;
            }
        }

        if (bl.some(filter => prop.includes(filter))) {
            hidden = true;
        }
    }

    return hidden;
}

function getFilters (el) {
    let pos = [];
    let neg = [];
    $(el).each(function () {
        if ($(this).hasClass("pos")) {
            pos.push($(this).attr("id"));
        } else if ($(this).hasClass("neg")) {
            neg.push($(this).attr("id"));
        }
    });

    return [pos, neg];
}

function getLogicOp () {
    return [$(".classlist-andor.active").data("id")];
}

$("#spell-searchbar").on("input", filterSpell);
$(function () {$("#spell-searchbar").trigger("input");});

$(".button-toggle").click(function () {
    $(this).toggleClass("active");
});

$(".button-toggle").hover(
    function () {
        $(this).addClass("hover");
    },
    function () {
        $(this).removeClass("hover");
    });

$("#filter-button").click(function () {
    $("#filter-menu").toggleClass("d-none");
});

$(".button-toggle-2").hover(
    function () {
        $(this).addClass("hover");
    },
    function () {
        $(this).removeClass("hover");
    });

$(".button-toggle-2").on({
    "click": function () {
        if ($(this).hasClass("pos")) {
            $(this).removeClass("pos");
            $(this).addClass("neg");
        } else if ($(this).hasClass("neg")) {
            $(this).removeClass("neg");
        } else {
            $(this).addClass("pos");
        };
        filterSpell();
    },
    "contextmenu": function(e) {
        e.preventDefault();
        if ($(this).hasClass("neg")) {
            $(this).removeClass("neg");
            $(this).addClass("pos");
        } else if ($(this).hasClass("pos")) {
            $(this).removeClass("pos");
        } else {
            $(this).addClass("neg");
        };
        filterSpell();
    }
});

$(document).on("keydown", function(e) {
    if (e.key === "ArrowDown") {
        e.preventDefault();
        $(function () {
            let next = $(".button-list.active").nextAll(":not(.d-none)").first()
            next.trigger("click");
            next[0]?.scrollIntoView({behavior: "smooth", block: "nearest"});
        });
    }
    if (e.key === "ArrowUp") {
        e.preventDefault();
        $(function () {
            let prev = $(".button-list.active").prevAll(":not(.d-none)").first()
            prev.trigger("click");
            prev[0]?.scrollIntoView({behavior: "smooth", block: "nearest"});
        });
    }
});

$(".classlist-andor").click(function () {
    $(".classlist-andor").removeClass("active");
    $(this).addClass("active");

    filterSpell();
});

$("#clear-button").click(function () {
    $("#spell-searchbar").val("");
    $("#spell-searchbar").trigger("input");
});