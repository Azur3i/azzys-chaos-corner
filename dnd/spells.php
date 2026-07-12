<?php define("ROOT",  __DIR__ . "/.."); 

include ROOT . "/tpl/header.php"; 
include ROOT . "/tpl/header-dnd.php";

require_once ROOT . "/scripts/spell-functions.php";

$spells = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

uasort($spells, function ($a, $b) {
    return strcmp($a["name"], $b["name"]);
});
uasort($spells, function ($a, $b) {
    return strcmp($a["level"], $b["level"]);
});

$spellName = array_key_first($spells);
$targetSpell = $spells[$spellName];

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-md-12 col-lg-9 mx-auto py-0">
            <div class="content-list row" style="padding: 0;" id="spell-view">
                <div class="content-list col-md-12 col-lg-4" id="spell-list">
                    <div class="row mx-auto align-items-center justify-content-center" id="spell-search">
                        <p class="sm col-auto">Search:</p>
                        <input id="spell-searchbar" class="sm col align-items-center py-1 px-3"></input>
                    </div>
                    <div class="scroll row" style="margin: 0;" id="spell-scrolllist">
                        <ul class="list-group" style="padding: 0;">
                            <?php $i = 0;
                            foreach ($spells as $x => $y): ?>
                                <a 
                                    class="list-group-item blue low-opac button-list d-grid w-100 align-items-center"
                                    style="outline: none; box-shadow: none; grid-template-columns: 1fr auto;"
                                    id="<?= $x ?>"
                                    href="#<?= $x ?>"
                                    data-name="<?= $y["name"] ?>">
                                    <span class="md" style="text-align: left;"><?= $y["name"] ?></span>
                                    <span data="school" class="sm" style="opacity: 0.7;"><?= get_level($y["level"], $y["school"]) ?></span>
                                </a>
                                <?php $i++;
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div id="spellbox" class="col-md-12 col-lg-8" style="padding-right: 0;">
                    <?php include ROOT . "/scripts/construct-spell.php" ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/scripts/spells.js"></script>
<?php include ROOT . "/tpl/footer.php"; ?>