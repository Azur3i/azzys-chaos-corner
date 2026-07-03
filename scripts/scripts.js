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
)