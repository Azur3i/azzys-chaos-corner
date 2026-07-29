let spellCache = {};
let levelCache = {};

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

let hideTimer;

// hover on spell link: load and show respective preview
$(document).on("mouseenter", ".spell-link", function () {
    clearTimeout(hideTimer);
    
    // if spell link is already on the preview, don't show preview
    if ($(this).closest("#spell-preview").length) {
        return;
    }

    let spell = $(this).data("spell");
    let el = this;

    // if cached, show that instead
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

// hover off spell link: wait 200ms, then hide preview
$(document).on("mouseleave", ".spell-link", function () {
    if (!$(this).closest("#spell-preview").length) {
        hideSpellPreview(200)
    }
});
// hover on preview: cancel hide timer and keep showing
$(document).on("mouseenter", "#spell-preview", () => clearTimeout(hideTimer));
// hover off preview: wait 200ms, then hide preview
$(document).on("mouseleave", "#spell-preview", () => hideSpellPreview(200));

// hide preview instantly when:
// document scrolls
$(document).on("scroll", () => hideSpellPreview(0))
// user clicks outside the preview
$(document).on("click", function (e) {
    if (!$(e.target).closest("#spell-preview").length) {
        hideSpellPreview(0);
    }
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
            visibility: "visible",
        })
        .stop(true, true)
        .fadeIn(100);
    
    $("#spell-preview .button-pin").hide();
}

function hideSpellPreview(timer) {
    hideTimer = setTimeout(function () {
        $("#spell-preview").stop(true, true).fadeOut(100);
        $("#spell-preview-content").html("");
    }, timer);
}

// allows spell preview to switch between spell levels
$("#spell-preview, #spellbox").on("click", "div.spell-level-selector > a.button-lvl", function () {
    let level = $(this).attr("value");
    let spell = $("#spell-display").attr("spell");

    $(".button-lvl").removeClass("active");
    $(this).addClass("active");

    levelCache[spell] ??= {};
    if (levelCache[spell][level]) {
        if (Array.isArray(levelCache[spell][level])) {
            levelCache[spell][level].forEach((param, index) => {
                $(`.level-replace-${index}`).html(param);
            })
        } else {
            $(".level-replace-0").text(levelCache[spell][level]);
        }
    } else {
        $.post(
            "/scripts/getters/spell_level.php", {
                level: level,
                spell: spell
            },
            function(response) {
                levelCache[spell][level] = response;
                if (Array.isArray(response)) {
                    response.forEach((param, index) => {
                        $(`.level-replace-${index}`).html(param);
                    })
                } else {
                    $(".level-replace-0").text(response);
                }
            }, "json"
        )
    }
});

// 2-stop toggle button -> off/pos
// 3-stop toggle button -> off/pos/neg

// highlight on-hover
$(document).on("hover", ".button-toggle, .button-toggle-2",
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