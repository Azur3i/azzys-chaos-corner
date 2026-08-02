let cache = [];
let item = $("#title").data("id"); 
let title = toUpper(item) + "s";

let ajaxTimer;

function loadItem(target) {
    updateButton(target);

    clearTimeout(ajaxTimer);
    ajaxTimer = setTimeout(() => {
        if (cache[target]) {
            $("#select-box").html(cache[target]);
            updateState(target);
        } else {
            $.get(
                "/scripts/constructors/" + item + ".php", {target: target},
                function(response) {
                    $("#select-box").html(response);
                    cache[target] = response;
                    updateState(target);
                }
            )
        }
    }, 50);
}

function updateButton(button) {
    $(".button-list").removeClass("active");
    $(`#${button}`).addClass("active");
}

function updateState(target) {
    document.title = $("#select-item").data("name") + " - " + title + " - Azzy's Chaos Corner";
    history.pushState(null, "", "#" + target);

    if ($(`#${target}`).closest("#pinned").length) {
        $(".button-pin").addClass("active");
    }
    $(`#${target}`)[0]?.scrollIntoView({block: "nearest"});
}

function loadItemByHash() {
    let item = location.hash.substring(1);

    item && loadItem(item);
}

// load currently selected item on refresh
$(loadItemByHash);

// if url#hash is changed manually, load item
$(window).on("hashchange", loadItemByHash);

// if a item is selected in item list, load item
$(".button-list").click(function (e) {
    e.preventDefault();

    let item = $(this).attr("id");
    loadItem(item);
});

// reapply filters on searchbar input
$("#searchbar").on("input", applyFilter);
// reapplies existing searchbar filter on page refresh
$(function () {$("#searchbar").trigger("input");});

// searchbar clear button
$("#clear-button").click(function () {
    $("#searchbar").val("");
    $("#searchbar").trigger("input");
});

// show/hide filter menu on clicking the filter button
$("#filter-button").click(function () {
    $("#select-filter").fadeToggle(100);
});

$(document).on("click", ".filter-toggle", applyFilter)

// applies filters to item list
function applyFilter () {
    let search = $("#searchbar").val().toLowerCase();

    $(".button-list").each(function() {
        let name = $(this).data("name").toLowerCase();
        let prop = $(this).data("filter").toLowerCase();
        let hidden = false;

        // filter options that can be filtered AND/OR
        let elAndOr = $("#andor").data("and");
        let operand;

        elAndOr.forEach(element => {
            let [wl, bl] = fetchFilters(".button-toggle-2.toggle-" + element);
            operand = $(`.${element}-andor.active`).data("id");
            if (operand == "and") {
                hidden = applyAnd(prop, wl, bl, hidden);
            } else if (operand == "or") {
                hidden = applyOr(prop, wl, bl, hidden);
            }
        });

        // filter options that can only be filtered OR; 
        // filtering AND would be redundant as these can only have 1 value
        let elOr = $("#andor").data("or");

        elOr.forEach(element => {
            let [wl, bl] = fetchFilters(".button-toggle-2.toggle-" + element);

            hidden = applyOr(prop, wl, bl, hidden);
        });

        // filter searchbar contents regardless of other active filters
        if (!name.includes(search)) {
            hidden = true;
        }

        $(this).toggleClass("d-none", hidden);
    });
    
    let num = countItems();
    $("#counter").text(`Filters: ${num} ${item}${isPlural(num)}`);
}

// applies the and operand
function applyAnd (prop, wl, bl, hidden) {
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
function applyOr (prop, wl, bl, hidden) {
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

// repplies existing filters when and/or is switched for class lists
$(document).on("click", ".andor", applyFilter);

// fetches buttons with active pos/neg filters
function fetchFilters(el) {
    let pos = [];
    let neg = [];
    $(el).each(function () {
        if ($(this).hasClass("pos")) {
            pos.push($(this).data("id"));
        } else if ($(this).hasClass("neg")) {
            neg.push($(this).data("id"));
        }
    });

    return [pos, neg];
}

// returns n where n is amount of viewable items in list view
function countItems() {
    return $("#pinned, #unpinned").children(":not(.d-none)").length;
}

function sortItemList(ls) {
    let lsItems;

    lsItems = ls.children().get();
    lsItems.sort((a, b) => {
        let x = a.dataset.filter.replace(/\D/g, "").localeCompare(b.dataset.filter.replace(/\D/g, ""));
        if (x) {return x;}

        return a.id.localeCompare(b.id);
    });
    ls.append(lsItems);
}

let pinned = JSON.parse(localStorage.getItem("pinned") ?? "[]");

// pin items pinned previously
$(function () {
    pinned.forEach(item => {
        $(`#${item}`).appendTo("#pinned");
    })

    sortItemList($("#pinned"));
});

// pin items when clicking pin button
$(document).on("click", ".button-pin", function () {
    $(this).toggleClass("active");
    let item = location.hash.substring(1);
    let ls;

    if ($(this).hasClass("active")) {
        $(`#${item}`).appendTo("#pinned");
        pinned.push(item);
        ls = $("#pinned");
    } else {
        $(`#${item}`).appendTo("#unpinned");
        pinned = pinned.filter(id => id !== item);
        ls = $("#unpinned");
    }

    sortItemList(ls);

    localStorage.setItem("pinned", JSON.stringify(pinned));
});

// keyboard scrolling
$(document).on("keydown", function(e) {
    switch (e.key) {
        // up and down keys scroll the items
        case "ArrowDown":
            e.preventDefault();
            $(function () {
                let next = $(".button-list.active").nextAll(":not(.d-none)").first();
                // if no elements found, return to top
                if (!next.length) {
                    if ($(".button-list.active").closest("#unpinned").length) {
                        next = $(".button-list:not(.d-none)").first();
                    } else {
                        next = $("#unpinned > .button-list:not(.d-none)").first();
                    }
                }
                next.trigger("click");
                next[0]?.scrollIntoView({behavior: "smooth", block: "nearest"});
            }); break;
        case "ArrowUp":
            e.preventDefault();
            $(function () {
                let prev = $(".button-list.active").prevAll(":not(.d-none)").first();
                // if no elements found, return to bottom
                if (!prev.length) {
                    if ($(".button-list.active").closest("#unpinned").length && $("#pinned").children().length) {
                        prev = $("#pinned > .button-list:not(.d-none)").last();
                    } else {
                        prev = $(".button-list:not(.d-none)").last();
                    }
                }
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