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

function searchSpell () {
    let search = $(this).val().toLowerCase();
    $(".button-list").each(function() {
        let searchable = $(this).data("name").toLowerCase();

        if (searchable.includes(search)) {
            $(this).removeClass("d-none");
        } else {
            $(this).addClass("d-none");
        }
    });
}

$("#spell-searchbar").on("input", searchSpell);
$(function () {$("#spell-searchbar").trigger("input");});

$(function() {console.log(window.innerWidth);});

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