function checkSubclass (cls, sbcls) {
    $(".subclass-check").removeClass("active");
    $(this).addClass("active");
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

function checkSubclassByHash () {
    checkSubclass($("h1").data("name"), location.hash.substring(1));
}

$(".subclass-check").click(function (e) {
    history.pushState(null, "", "#" + $(this).attr("data").split("~")[1]);
    checkSubclassByHash();
});

$(checkSubclassByHash);
$(window).on("hashchange", checkSubclassByHash);