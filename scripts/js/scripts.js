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