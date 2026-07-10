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

$(".button-list").click(function () {
    let spell = $(this).attr("id");
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