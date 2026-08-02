<?php define("ROOT",  __DIR__ . "/.."); 

require_once ROOT . "/scripts/php/spells.php";

$items = json_decode(file_get_contents(ROOT . "/dnd/data/races.json"), true);

// sort -> name
uasort($items, function ($a, $b) {
    return strcmp($a["name"], $b["name"]);
});

$sources = [];
foreach ($items as $race) {
    if (!in_array($race["source"], $sources)) {
        $sources[] = $race["source"];
    }
}

// filters
uasort($sources, function ($a, $b) {
    return strcmp($a, $b);
});

$filterOptions = [
    "sources" => [
        "key" => "source",
        "var" => $sources,
        "andor" => false,
        "type" => "height"
    ]
];

// definitions
$subHead = [];
foreach ($items as $y) {
    $subHead[$y["name"]] = $y["source"];
}

$title = "race";

?>

<?php include ROOT . "/tpl/header.php";  ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>

<?php include ROOT . "/tpl/list-view.php"; ?>

<script>document.title = "Races - Azzy's Chaos Corner";</script>

<?php include ROOT . "/tpl/footer.php"; ?>

