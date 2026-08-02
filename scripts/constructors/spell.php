<?php 

if (!defined("ROOT")) {define("ROOT",  __DIR__ . "/../..");}

require_once ROOT . "/scripts/php/spells.php";
require_once ROOT . "/scripts/php/general.php";

if (!empty($_GET)) {
    
    $spells = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

    $spellName = $_GET["target"];
    $targetSpell = $spells[$spellName];
}

$cantripLevels = [1, 5, 11, 17];

?>

<div id="select-display" class="blue box scroll no-margin" item="<?= $spellName ?>">
    <?// name, school/level, source, pin button ?>
    <div class="row" style="padding: 0 0 0 1.5rem;">

        <div class="row no-margin" style="padding: 0;">
            <h1 id="select-item" class="xlg col" data-name="<?= $targetSpell["name"] ?>"><?= $targetSpell["name"] ?></h1>
            <h3 class="md col" style="text-align: right; padding-top: 1rem; margin-right: 0.5rem;">Source: <?= $targetSpell["source"] ?></h3>
            <a class="md col-auto white ms-auto button-pin"><img src="/assets/img/pin.png" id="pin"></a>
        </div>

        <div class="row no-margin" style="padding: 0;">
            <p class="md col" style="opacity: 0.7;"><?= get_level($targetSpell["level"], $targetSpell["school"]); ?></p>
        </div>

    </div>
    
    <hr >

    <?// cast time, range, duration, components ?>
    <div class="row">
        <div class="col title">
            <p class="lg">Casttime</p>
            <p class="md"><?= get_first_level(get_time($targetSpell["castTime"]), $targetSpell["levels"]) ?> </p>
        </div>
        <div class="col title">
            <p class="lg">Range</p>
            <p class="md"><?= get_first_level(ucfirst($targetSpell["range"]), $targetSpell["levels"]) ?> </p>
        </div>
        <div class="col title">
            <p class="lg">Duration</p>
            <p class="md"><?= get_first_level(get_time($targetSpell["duration"]), $targetSpell["levels"]) ?> </p>
        </div>
        <div class="col title">
            <p class="lg">Components</p>
            <p class="md">
                <?php 
                echo $targetSpell["vsm"][0] ? "<b style='color: rgb(var(--pink));'>V</b>erbal" : "";
                echo $targetSpell["vsm"][0] && $targetSpell["vsm"][1] ? ", " : "";
                echo $targetSpell["vsm"][1] ? "<b style='color: rgb(var(--pink));'>S</b>omatic" : "";
                echo ($targetSpell["vsm"][0] || $targetSpell["vsm"][1]) && $targetSpell["vsm"][2] ? "<br >" : "";
                echo $targetSpell["vsm"][2] ? "<b style='color: rgb(var(--pink));'>M</b>aterial: " . $targetSpell["vsm"][2] : "";
                ?> 
            </p>
        </div>
    </div>

    <hr >

    <?// description ?>
    <div id="select-desc" class="row">
        <?php $i = 0; foreach ($targetSpell["desc"] as $desc):
            ob_start();
            renderAbility($desc);
            if ($targetSpell["levels"] !== null) {
                echo renderText(get_first_level(ob_get_clean(), $targetSpell["levels"]));
            } else {
                echo ob_get_clean();
            }
        endforeach; ?>
    </div>
    
    <?// at higher levels ?>
    <?php if ($targetSpell["levels"] !== null): ?>
    <hr >

    <div id="select-level" style="padding: 1rem;" class="row">
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
    <hr >
    
    <?// class lists ?>
    <div style="padding: 1rem;" class="row">
        <p class="md col-2 text-center">Spelllists: </p>
        <div class="spell-level-selector d-flex col-10">
            <p class="md">
            <?= ucwords(implode(", ", $targetSpell["lists"])); ?>
            </p>
        </div>
    </div>
</div>

<script>document.title = "<?= $targetSpell["name"] ?> - Spells - Azzy's Chaos Corner";</script>