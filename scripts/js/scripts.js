$(".dnd-nav").click(function () {
    $("#dnd-mc").load("/pages/dnd/" + $(this).text().toLowerCase() + ".php");
});

$("tbody td:not([rowspan])").hover(
    function() {
        $(this).parent().addClass("hover");
    },
    function() {
        $(this).parent().removeClass("hover");
    }
);

$(function () {
    document.title = $("#title").data("id") + "Azzy's Chaos Corner";
});

let spellCache = {};
let hideTimer;

$(document).on("mouseenter", ".spell-link", function () {
    clearTimeout(hideTimer);
    
    let spell = $(this).data("spell");
    let el = this;

    if (spellCache[spell]) {
        showSpellPreview(spellCache[spell], this)
        return;
    }

    $.ajax({
        url: "/scripts/constructors/spell.php",
        method: "GET",
        data: {target: spell},
        success: function (html) {
            spellCache[spell] = html;
            showSpellPreview(html, el);
        }
    });
});

$(document).on("mouseleave", ".spell-link", function () {
    hideTimer = setTimeout(function () {
        $("#spell-preview").stop(true, true).fadeOut(100);
    }, 200);
});

$(document).on("mouseenter", "#spell-preview", function () {
    clearTimeout(hideTimer);
});

$(document).on("mouseleave", "#spell-preview", function () {
    $("#spell-preview").stop(true, true).fadeOut(100);
});

$(document).on("scroll", function() {
    $("#spell-preview").fadeOut(100);
})

function showSpellPreview(html, element) {
    let rect = element.getBoundingClientRect();
    let preview = $("#spell-preview");

    $("#spell-preview-content").html(html);

    preview
        .css({
            position: "fixed",
            visibility: "hidden",
            display: "block"
        });

    let previewRect = preview[0].getBoundingClientRect();

    let width = previewRect.width;
    let height = previewRect.height;

    preview.css("display", "none");

    let gap = 10;

    let left;
    let top;

    // Link is on left half → preview goes right
    if (rect.left < window.innerWidth / 2) {
        left = rect.right + gap;
    }
    // Link is on right half → preview goes left
    else {
        left = rect.left - width - gap;
    }

    // Link is on top half → preview extends downward
    if (rect.top < window.innerHeight / 2) {
        top = rect.top;
    }
    // Link is on bottom half → preview extends upward
    else {
        top = rect.bottom - height;
    }

    preview
        .css({
            left: left + "px",
            top: top + "px",
            visibility: "visible"
        })
        .stop(true, true)
        .fadeIn(100);
}

// allows spell preview to switch between spell levels
$("#spell-preview-content").on("click", ".button-lvl", function () {
    let level = $(this).attr("value");
    let spell = $("#spell-display").attr("spell");

    $(".button-lvl").removeClass("active");
    $(this).addClass("active");

    $.post(
        "/scripts/getters/spell_level.php", {
            level: level,
            spell: spell
        },
        function(response) {
            if (Array.isArray(response)) {
                response.forEach((param, index) => {
                    console.log(param);
                    $(`.level-replace-${index}`).html(param);
                })
            } else {
                $(".level-replace-0").text(response);
            }
        }, "json"
    )
});