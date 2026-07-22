<?php define("ROOT",  __DIR__ . "/.."); 

include ROOT . "/tpl/header.php"; 
include ROOT . "/tpl/header-dnd.php";

require_once ROOT . "/scripts/php/spells.php";

$spells = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

uasort($spells, function ($a, $b) {
    return strcmp($a["name"], $b["name"]);
});
uasort($spells, function ($a, $b) {
    return strcmp($a["level"], $b["level"]);
});

$schools = [];
$sources = [];
foreach ($spells as $spell) {
    if (!in_array($spell["school"], $schools)) {
        $schools[] = $spell["school"];
    }
    if (!in_array($spell["source"], $sources)) {
        $sources[] = $spell["source"];
    }
}

uasort($schools, function ($a, $b) {
    return strcmp($a, $b);
});
uasort($sources, function ($a, $b) {
    return strcmp($a, $b);
});

// --

$classes = json_decode(file_get_contents(ROOT . "/dnd/data/classes.json"), true);
$casters = [];
foreach ($classes as $x => $class) {
    if ($class["magic"]["caster"] !== "none") {
        $casters[$x] = $class;
    }
}

// --

$levels = ["Cantrip", 1, 2, 3, 4, 5, 6, 7, 8, 9];

// --

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-md-12 col-lg-9 mx-auto py-0">
            <div class="content-list row" style="padding: 0;" id="spell-view">
                <div class="content-list col-md-12 col-lg-4" style="position: relative;" id="spell-list">
                    
                    <!-- searchbar -->
                    <div class="row mx-auto align-items-center justify-content-center" id="spell-search">
                        <p class="sm col-auto">Search:</p>
                        <div class="col align-items-center" style="position: relative;">
                            <input id="spell-searchbar" class="sm py-1 px-3" style="width: 100%;"></input>
                            <a id="clear-button" class="sm button col-aut white" style="">X</a>
                        </div>
                        <a class="sm button-toggle pink col-auto align-items-center" style="height: 2rem; margin-left: 1rem; padding: 0 1rem;" id="filter-button">Filters</a>
                    </div>

                    <!-- filter menu -->
                    <div class="d-none content-list" style="position: absolute;" id="filter-menu">
                        <div class="row" style="margin: 0;">
                            <p class="md title">Filters:</p>
                        </div>

                        <hr >

                        <div class="row" style="margin: 0;">

                            <div class="col row pink justify-content-center">
                                <div class="d-flex row justify-content-center align-items-center" style="margin: 0;">
                                    <p class="md title align-items-center col-auto" style="height: 3rem;">Classes</p>
                                    <div class="col-auto d-flex">
                                        <a class="button-switch sm andor classlist-andor active" data-id="and">AND</a>
                                        <a class="button-switch sm andor classlist-andor" data-id="or">OR</a>
                                    </div>
                                </div>
                                <?php foreach ($casters as $name => $caster): ?>
                                    <a class="button-toggle-2 col-5 spell-toggle toggle-classlist toggle sm" id="<?= $name ?>" data-id="classlist"><?= $caster["name"] ?></a>
                                <?php endforeach; ?>
                            </div>
                            <div class="col row pink justify-content-center">
                                <p class="md title">Schools</p>
                                <?php foreach ($schools as $school): ?>
                                    <a class="button-toggle-2 col-5 spell-toggle toggle-school sm" id="<?= $school ?>"><?= ucfirst($school) ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr >

                        <div class="row justify-content-center" style="margin: 0;">
                            <div class="col row pink justify-content-center">
                                <div class="d-flex row justify-content-center align-items-center" style="margin: 0;">
                                    <p class="md title align-items-center col-auto" style="height: 3rem;">Levels</p>
                                </div>
                                <?php foreach ($levels as $i => $level): ?>
                                    <a class="button-toggle-2 col-auto spell-toggle toggle-level sm justify-content-center" style="padding: 0.5rem 1rem;" id="<?= $i ?>"><?= $level ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <hr >

                        <div class="row justify-content-center" style="margin: 0;">
                            <div class="col row pink justify-content-center">
                                <div class="d-flex row justify-content-center align-items-center" style="margin: 0;">
                                    <p class="md title align-items-center col-auto" style="height: 3rem;">Sources</p>
                                </div>
                                <?php foreach ($sources as $name => $source): ?>
                                    <a class="button-toggle-2 col-11 spell-toggle toggle-source toggle sm" id="<?= $source ?>" data-id="source"><?= $source ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr >
                    </div>
                    
                    <!-- spell list -->
                    <div class="scroll row" id="spell-scrolllist">
                        <ul class="list-group" style="padding: 0;">
                            <?php $i = 0;
                            foreach ($spells as $x => $y): ?>
                                <a 
                                    class="list-group-item blue low-opac button-list d-grid w-100 align-items-center"
                                    style="outline: none; box-shadow: none; grid-template-columns: 1fr auto;"
                                    id="<?= $x ?>"
                                    href="#<?= $x ?>"

                                    data-name="<?= $y["name"] ?>"
                                    data-level="<?= $y["level"] ?>"
                                    data-school="<?= $y["school"] ?>"
                                    data-lists="<?= implode(" ", $y["lists"]) ?>"
                                    data-source="<?= $y["source"] ?>"
                                >
                                    <span class="md" style="text-align: left;"><?= $y["name"] ?></span>
                                    <span class="sm" style="opacity: 0.7;"><?= get_level($y["level"], $y["school"]) ?></span>
                                </a>
                                <?php $i++;
                            endforeach; ?>
                        </ul>
                    </div>

                </div>

                <!-- spell display -->
                <div id="spellbox" class="col-md-12 col-lg-8" style="padding-right: 0;">
                    <div class="content-list" style="height: 100%; width: 100%; margin: 0;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="title" data-id="Spells - "></div>

<script src="/scripts/js/spells.js"></script>
<?php include ROOT . "/tpl/footer.php"; ?>