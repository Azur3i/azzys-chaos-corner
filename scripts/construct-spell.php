<?php 

if (!defined("ROOT")) {
    define("ROOT",  __DIR__ . "/..");
}

require_once ROOT . "/scripts/spell-functions.php";
require_once ROOT . "/scripts/functions.php";

if (!empty($_GET)) {
    
    $spells = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

    $spellName = $_GET["target"];
    $targetSpell = $spells[$spellName];
}

$cantripLevels = [1, 5, 11, 17];

require_once ROOT . "/scripts/functions.php"; ?>

<div class="content-list" style="padding: 1.25rem; margin: 0;" id="spell-display" spell="<?= $spellName ?>">
    <div class="scroll">
        <div class="row spell-list align-items-center" style="padding: 0; padding-left: 1.5rem;">
            <h1 class="xlg col"><?= $targetSpell["name"] ?></h1>
            <h3 class="md col" style="text-align: right;">Source: <?= $targetSpell["source"] ?></h3>

            <p class="md" style="opacity: 0.7;"><?= get_level($targetSpell["level"], $targetSpell["school"]); ?></p>
        </div>
        
        <hr >

        <div class="row spell-list align-items-center">
            <div class="col title">
                <p class="lg">Casttime</p>
                <p class="md"><?= get_time($targetSpell["castTime"]) ?> </p>
            </div>
            <div class="col title">
                <p class="lg">Range</p>
                <p class="md"><?= get_dist($targetSpell["range"]) ?> </p>
            </div>
            <div class="col title">
                <p class="lg">Duration</p>
                <p class="md"><?= get_time($targetSpell["duration"]) ?> </p>
            </div>
            <div class="col title">
                <p class="lg">Components</p>
                <p class="md">
                    <?php 
                    echo $targetSpell["comp"][0] ? "<b>V</b>erbal" : "";
                    echo $targetSpell["comp"][0] && $targetSpell["comp"][1] ? ", " : "";
                    echo $targetSpell["comp"][1] ? "<b>S</b>omatic" : "";
                    echo ($targetSpell["comp"][0] || $targetSpell["comp"][1]) && $targetSpell["comp"][2] ? "<br >" : "";
                    echo $targetSpell["comp"][2] ? "<b>M</b>aterial: " . $targetSpell["comp"][2] : "";
                    ?> 
                </p>
            </div>
        </div>

        <hr >

        <div class="row spell-list margin" id="spell-desc">
            <?php $i = 0; foreach ($targetSpell["desc"] as $desc):
                ob_start();
                renderAbility($desc);
                if ($targetSpell["levels"] !== null) {
                    echo get_first_level(ob_get_clean(), $targetSpell["levels"]);
                } else {
                    echo ob_get_clean();
                }
            endforeach; ?>
        </div>
        
        <?php if ($targetSpell["levels"] !== null): ?>
        
        <hr >
        <div style="padding: 1rem;" class="row spell-list align-items-center">
            <p class="md col-2 text-center">Different levels:</p>
            <div class="spell-level-selector d-flex col-10">
                <?php foreach($targetSpell["levels"] as $i => $level):?>
                    <a class="blue button-lvl md <?= $i === 0 ? "active" : "" ?>" value="<?= $i ?>">
                        <?php if ($targetSpell["level"] === 0) {
                            echo $cantripLevels[$i];
                        } else {
                            echo $targetSpell["level"] + $i;
                        } ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>