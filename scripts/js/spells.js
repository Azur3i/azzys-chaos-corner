let ajaxTimer;

function loadSpell(target) {
    updateButton(target);

    clearTimeout(ajaxTimer);
    ajaxTimer = setTimeout(() => {
        if (spellCache[target]) {
            $("#spellbox").html(spellCache[target]);
            document.title = $("#spellname").data("name") + " - Spells - Azzy's Chaos Corner";
            history.pushState(null, "", "#" + target);
        } else {
            $.get(
                "/scripts/constructors/spell.php", {target: target},
                function(response) {
                    $("#spellbox").html(response);
                    spellCache[target] = response;

                    document.title = $("#spellname").data("name") + " - Spells - Azzy's Chaos Corner";
                    history.pushState(null, "", "#" + target);
                }
            )
        }
    }, 50);
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

// if url#hash is changed manually, load the spell
$(window).on("hashchange", function () {
    let spell = location.hash.substring(1);
    loadSpell(spell);
});

// if a spell is selected in the spell list, load the spell
$(".button-list").click(function (e) {
    e.preventDefault();

    let spell = $(this).attr("id");
    loadSpell(spell);
});

// applies filters to spell list
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
            "classlist"
        ];

        let [operand] = fetchLogicOp();

        elAndOr.forEach(element => {
            let [wl, bl] = fetchFilters(".button-toggle-2.toggle-" + element);

            if (operand == "and") {
                hidden = applyAnd(prop, wl, bl, operand, hidden);
            } else if (operand == "or") {
                hidden = applyOr(prop, wl, bl, operand, hidden);
            }
        });

        // filter options that can only be filtered OR; 
        // filtering AND would be redundant as these can only have 1 value
        let elOr = [
            "school",
            "level",
            "source"
        ]

        elOr.forEach(element => {
            let [wl, bl] = fetchFilters(".button-toggle-2.toggle-" + element);

            hidden = applyOr(prop, wl, bl, operand, hidden);
        });

        // filter searchbar contents regardless of other active filters
        if (!name.includes(search)) {
            hidden = true;
        }

        $(this).toggleClass("d-none", hidden);
    });
}

// applies the and operand
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

// applies the or operand
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

// fetches buttons with active pos/neg filters
function fetchFilters(el) {
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

// fetches logic operands of filter options
function fetchLogicOp() {
    return [$(".classlist-andor.active").data("id")];
}

// switch buttons
$(document).on("click", ".button-switch:not(.active)", function () {
    $(".button-switch").removeClass("active");
    $(this).addClass("active");
});

// reapply filters on searchbar input
$("#spell-searchbar").on("input", filterSpell);
// reapplies existing searchbar filter on page refresh
$(function () {$("#spell-searchbar").trigger("input");});
// repplies existing filters when and/or is switched for class lists
$(".classlist-andor").click(filterSpell());


// 2-stop toggle button -> off/pos
// 3-stop toggle button -> off/pos/neg

// highlight on-hover
$(".button-toggle, .button-toggle-2").hover(
    function () {
        $(this).addClass("hover");
    },
    function () {
        $(this).removeClass("hover");
    }
);

// cycle through options on click
$(".button-toggle").click(function () {
    $(this).toggleClass("active");
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

// show/hide filter menu on clicking the filter button
$("#filter-button").click(function () {
    $("#filter-menu").toggleClass("d-none");
});

// keyboard scrolling
$(document).on("keydown", function(e) {
    switch (e.key) {
        // up and down keys scroll the spells
        case "ArrowDown":
            e.preventDefault();
            $(function () {
                let next = $(".button-list.active").nextAll(":not(.d-none)").first();
                next.trigger("click");
                next[0]?.scrollIntoView({behavior: "smooth", block: "nearest"});
            }); break;
        case "ArrowUp":
            e.preventDefault();
            $(function () {
                let prev = $(".button-list.active").prevAll(":not(.d-none)").first();
                prev.trigger("click");
                prev[0]?.scrollIntoView({behavior: "smooth", block: "nearest"});
            }); break;
        
        // left and right keys scroll the levels
        case "ArrowRight":
            e.preventDefault();
            $(function () {
                let next = $(".button-lvl.active").next();
                next.trigger("click");
            }); break;
        case "ArrowLeft":
            e.preventDefault();
            $(function () {
                let prev = $(".button-lvl.active").prev();
                prev.trigger("click");
            }); break;
    }
});

// searchbar clear button
$("#clear-button").click(function () {
    $("#spell-searchbar").val("");
    $("#spell-searchbar").trigger("input");
});