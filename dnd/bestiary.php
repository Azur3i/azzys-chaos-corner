<?php define("ROOT",  __DIR__ . "/.."); 

require_once ROOT . "/scripts/php/spells.php";

$items = json_decode(file_get_contents(ROOT . "/dnd/data/statblocks.json"), true);

//sort -> cr > name
uasort($items, fn($a, $b) => strcmp($a["name"], $b["name"]));

uasort($items, fn($a, $b) => $a["cr"] <=> $b["cr"]);

// filters

$creatureTypes = [];
$sizes = [];
$sources = [];
foreach ($items as $creature) {
    if (!in_array(ucwords($creature["creatureType"]), $creatureTypes)) {
        $creatureTypes[ucwords($creature["creatureType"])] = $creature["creatureType"];
    }
    if (!in_array(ucwords($creature["size"]), $sizes)) {
        $sizes[ucwords($creature["size"])] = $creature["size"];
    }
    if (!in_array($creature["source"], $sources)) {
        $sources[$creature["source"]] = $creature["source"];
    }
}

foreach ([$creatureTypes, $sizes] as $type) {
    uasort($type, function ($a, $b) {
        return strcmp($a, $b);
    });
}

$cr = [0=>0, "1/8"=>0.125, "1/4"=>0.25, "1/2"=>0.5];
foreach (range(1, 30) as $i) {
    $cr[$i] = $i;
}

$filterOptions = [
    "cr" => [
        "key" => "cr",
        "var" => $cr,
        "andor" => false,
        "type" => "width"
    ],
    "creature-types" => [
        "key" => "creatureType",
        "var" => $creatureTypes,
        "andor" => false,
        "type" => "half"
    ],
    "sizes" => [
        "key" => "size",
        "var" => $sizes,
        "andor" => false,
        "type" => "half"
    ],
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
    $subHead[$y["name"]] = "CR " . $y["cr"] . " " . $y["creatureType"];
}

$title = "creature"

// --



// --

?>

<?php include ROOT . "/tpl/header.php";  ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>

<?php include ROOT . "/tpl/list-view.php"; ?>

<script>document.title = "Bestiary - Azzy's Chaos Corner";</script>

<?php include ROOT . "/tpl/footer.php"; ?>