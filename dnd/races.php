<?php define("ROOT",  __DIR__ . "/.."); 

include ROOT . "/tpl/header.php"; 
include ROOT . "/tpl/header-dnd.php";

require_once ROOT . "/scripts/php/spells.php";

$races = json_decode(file_get_contents(ROOT . "/dnd/data/races.json"), true);

uasort($races, function ($a, $b) {
    return strcmp($a["name"], $b["name"]);
});

$raceName = array_key_first($races);
$target = $races[$raceName];

$sources = [];
foreach ($races as $race) {
    if (!in_array($race["source"], $sources)) {
        $sources[] = $race["source"];
    }
}

uasort($sources, function ($a, $b) {
    return strcmp($a, $b);
});

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
                    
                    <!-- race list -->
                    <div class="scroll row" id="spell-scrolllist">
                        <ul class="list-group" style="padding: 0;">
                            <?php $i = 0;
                            foreach ($races as $x => $y): ?>
                                <a 
                                    class="list-group-item blue low-opac button-list d-grid w-100 align-items-center"
                                    style="outline: none; box-shadow: none; grid-template-columns: 1fr auto;"
                                    id="<?= $x ?>"
                                    href="#<?= $x ?>"

                                    data-name="<?= $y["name"] ?>"
                                    data-source="<?= $y["source"] ?>"
                                >
                                    <span class="md" style="text-align: left;"><?= $y["name"] ?></span>
                                    <span class="sm" style="opacity: 0.7;"><?= $y["source"] ?></span>
                                </a>
                                <?php $i++;
                            endforeach; ?>
                        </ul>
                    </div>

                </div>

                <!-- race display -->
                <div id="racebox" class="col-md-12 col-lg-8" style="padding-right: 0;">
                    <?php include ROOT . "/scripts/constructors/race.php" ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="title" data-id="Races - "></div>

<script src="/scripts/js/races.js"></script>
<?php include ROOT . "/tpl/footer.php"; ?>