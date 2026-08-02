<?php define("ROOT",  __DIR__ . "/.."); 

require_once ROOT . "/scripts/php/spells.php";

$items = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

// sort: level > name
uasort($items, function ($a, $b) {
    return strcmp($a["name"], $b["name"]);
});
uasort($items, function ($a, $b) {
    return strcmp($a["level"], $b["level"]);
});

$schools = [];
$sources = [];
$classes = [];
$levels = ["Cantrip", 1, 2, 3, 4, 5, 6, 7, 8, 9];
foreach ($items as $spell) {
    if (!in_array(ucwords($spell["school"]), $schools)) {
        $schools[] = ucwords($spell["school"]);
    }
    if (!in_array($spell["source"], $sources)) {
        $sources[] = $spell["source"];
    }
    foreach ($spell["lists"] as $class) {
        if (!in_array(ucwords($class), $classes)) {
            $classes[] = ucwords($class);
        }
    }
}

foreach ([$schools, $sources, $classes] as $type) {
    uasort($type, function ($a, $b) {
        return strcmp($a, $b);
    });
}

// --

$filterOptions = [
    "classes" => [
        "key" => "lists",
        "var" => $classes,
        "andor" => true,
        "type" => "half"
    ],
    "schools" => [
        "key" => "school",
        "var" => $schools,
        "andor" => false,
        "type" => "half"
    ],
    "levels" => [
        "key" => "level",
        "var" => $levels,
        "andor" => false,
        "type" => "width"
    ],
    "sources" => [
        "key" => "source",
        "var" => $sources,
        "andor" => false,
        "type" => "height"
    ]
];

$subHead = [];
foreach ($items as $y) {
    $subHead[$y["name"]] = get_level($y["level"], $y["school"]);
}

$title = "spell";

?>

<?php include ROOT . "/tpl/header.php";  ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>

<?php include ROOT . "/tpl/list-view.php"; ?>

<?php include ROOT . "/tpl/footer.php"; ?>