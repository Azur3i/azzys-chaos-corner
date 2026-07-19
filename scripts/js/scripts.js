$(".dnd-nav").click(function () {
    let page = $(this).text().toLowerCase();
    $("#dnd-mc").load("/pages/dnd/" + page + ".php");
});

$("tbody td:not([rowspan])").hover(
    function() {
        $(this).parent().addClass("hover");
    },
    function() {
        $(this).parent().removeClass("hover");
    }
);

function checkSubclass (cls, sbcls) {
    $(".subclass-check").removeClass("checked");
    $(this).addClass("checked");
    $.post(
        "/scripts/getters/subclass.php", {
            cls: cls,
            sbcls: sbcls
        },
        function(response) {
            $(".subclass-select").each(function (index) {
                 $(this).html(response[index]);
                 $(this).removeClass("hide");
            })
        }
        , "json"
    )
};

$(".subclass-check").click(function (e) {
    let data = $(this).attr("data").split("~");

    history.pushState(null, "", "#" + data[1]);
    checkSubclass($("h1").data("name"), location.hash.substring(1));
});

$(function () {
    checkSubclass($("h1").data("name"), location.hash.substring(1));

    document.title = $("#title").data("id") + "Azzy's Chaos Corner";
});

$(window).on("hashchange", function () {
    checkSubclass($("h1").data("name"), location.hash.substring(1));
});