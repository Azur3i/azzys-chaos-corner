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

$(".subclass-check").click(function () {
    let data = $(this).attr("data").split("~");
    let cls = data[0];
    let sbcls = data[1];
    $(".subclass-check").removeClass("checked");
    $(this).addClass("checked");
    $.post(
        "/scripts/get-subclass.php", {
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
});