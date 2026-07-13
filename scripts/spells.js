function loadSpell(target) {
    $.get(
        "/scripts/construct-spell.php", {target: target},
        function(response) {
            $("#spellbox").html(response);
            updateButton(target);
        }
    )
}

function updateButton(button) {
    $(".button-list").removeClass("active");
    $(`#${button}`).addClass("active");
}

$(function () {
    let spell = location.hash.substring(1);

    if (spell) {
        loadSpell(spell);
    } else if (button) {
        updateButton($(`#spell-display`).attr("spell"));
    }
})

$(".button-list").click(function (e) {
    e.preventDefault();

    let spell = $(this).attr("id");
    history.pushState(null, "", "#" + spell);

    loadSpell(spell);
});

$("#spellbox").on("click", ".button-lvl", function () {
    let level = $(this).attr("value");
    let spell = $("#spell-display").attr("spell");

    $(".button-lvl").removeClass("active");
    $(this).addClass("active");

    $.post(
        "/scripts/retrieve_spell_level.php", {
            level: level,
            spell: spell
        },
        function(response) {
            $(".level-replace").text(response);
        }
    )
});

function filterSpell () {
    let search = $("#spell-searchbar").val().toLowerCase();

    let [wl, bl] = getFilters();
    $(".button-list").each(function() {
        let name = $(this).data("name").toLowerCase();
        let prop = [
            $(this).data("level").toString(),
            $(this).data("school").toLowerCase(),
            ...$(this).data("lists").toLowerCase().split(" ")
        ];
        let hidden = false

        if (wl.length > 0) {
            hidden = true;
            if (wl.every(filter => prop.includes(filter))) {
                hidden = false;
            }
        }

        if (!name.includes(search)) {
            hidden = true;
        }
        
        if (bl.some(filter => prop.includes(filter))) {
            hidden = true;
        }

        $(this).toggleClass("d-none", hidden);
    });
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

function getFilters() {
    let pos = [];
    let neg = [];
    $(".button-toggle-2").each(function () {
        if ($(this).hasClass("pos")) {
            pos.push($(this).attr("id"));
        } else if ($(this).hasClass("neg")) {
            neg.push($(this).attr("id"));
        }
    });

    return [pos, neg];
}

$(document).on("keydown", function(e) {
    if (e.key === "ArrowDown") {
        e.preventDefault();
        $(function () {
            $(".button-list.active").next().each(function () {
                $(this).trigger("click");
                this.scrollIntoView({behavior: "smooth", block: "nearest"});
            });
        });
    }
    if (e.key === "ArrowUp") {
        e.preventDefault();
        $(function () {
            $(".button-list.active").prev().each(function () {
                $(this).trigger("click");
                this.scrollIntoView({behavior: "smooth", block: "nearest"});
            });
        });
    }
});