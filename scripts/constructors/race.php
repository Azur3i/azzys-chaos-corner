<?php

if (!defined("ROOT")) {define("ROOT",  __DIR__ . "/../..");}

require_once ROOT . "/scripts/php/general.php";

if (!empty($_GET)) {
    
    $races = json_decode(file_get_contents(ROOT . "/dnd/data/races.json"), true);

    $raceName = $_GET["target"];
    $target = $races[$raceName];
}

function renderASI ($asi) {
    if (!empty($asi["default"])) {
        switch ($asi["default"]) {
            case 2: return "Increase one ability score of your choice by 2, or two different ones by 1.";
            case 3: return "Increase one ability score by 2 and a different one by 1, or increase three different ability scores by 1.";
        }
    }

    if (count($asi) == 7 && !empty($asi["total"])) {
        $increase = $asi["total"] / 6;
        return "Your ability scores each increase by $increase.";
    }

    if (count($asi) == 1 && !empty($asi["total"])) {
        $increase = $asi["total"];
        return "$increase different ability scores of your choice increase by 1.";
    }

    arsort($asi);

    $scoreNames = [
        "STR" => "Strength",
        "DEX" => "Dexterity",
        "CON" => "Constitution",
        "INT" => "Intelligence",
        "WIS" => "Wisdom",
        "CHA" => "Charisma"
    ];

    $result = [];
    foreach ($asi as $score => $increase) {
        if ($score != "total") {
            $result[] = "your $scoreNames[$score] score " . ($increase > 0 ? "in" : "de") . "creases by " . abs($increase);
        }
    }
    if (count($result) > 1) {
        $result[array_key_last($result)] = "and " . $result[array_key_last($result)];
    }

    return ucfirst(implode(", ", $result)) . ".";
}

?>

<div id="select-display" class="blue box scroll no-margin" item="<?= $raceName ?>">
        <h1 class="title" id="select-item" data-name="<?= $target["name"] ?>"><?= $target["name"] ?></h1>
        <a class="md col-auto white ms-auto button-pin"><img src="/assets/img/pin.png" id="pin"></a>
        <p class="sm title" style="opacity: 0.5;">Source: <?= $target["source"] ?></p>

        <hr >

        <? // description and image ?>
        <div class="row">
            <div class="col">
                <p class="md title"><?= implode('</p><p class="md title" style="margin-top: 1rem;">', $target["desc"]) ?></p>
            </div>
            <?php if (file_exists(ROOT . "/assets/img/dnd/races/$raceName.png")): ?>
                <img id="item-img" class="col-auto my-auto" src="/assets/img/dnd/races/<?= $raceName ?>.png">
            <?php endif; ?>
        </div>
        
        <hr >
        
        <? // body and mind ?>
        <div class="mx-auto md row" style="width: 85%; margin: 0;">
            <? // body ?>
            <div class="col mx-auto md" style="width: 90%;">
                <p class="lg title">Body</p>
                <hr style="color: rgb(var(--black)); margin-top: 0;">

                <?php if (empty($target["creatureType"]) && empty($target["size"]) && empty($target["speed"])): ?>
                    <p style='text-align: center;'>Defined by options below.</p>
                <?php endif; ?>

                <?php echo (!empty($target["asi"])) ? "<p style='margin-bottom: 1rem;'><b>Alignment Score Increase. </b>" . renderASI($target['asi']) . "</p>" : ""; ?>
                <?php echo (!empty($target["creatureType"])) ?
                    "<p style='margin-bottom: 1rem;'><b>Creature Type. </b>You are a" .
                    (startsWith($target["creatureType"], ["a", "e", "i", "o", "u"]) ? "n" : "") .
                    " " .
                    $target["creatureType"] .
                    ".</p>" : "";
                ?>
                <?php echo (!empty($target["size"])) ? "<p style='margin-bottom: 1rem;'><b>Size. </b>" . $target['size'] . "</p>" : "" ?>
                <?php echo (!empty($target["speed"])) ? "<p style='margin-bottom: 1rem;'><b>Speed. </b>Your base walking speed is " . $target['speed'] . "ft.</p>" : "" ?>
            </div>
            
            <? // mind ?>
            <div class="col mx-auto md" style="width: 90%;">    
                <p class="lg title">Mind</p>
                <hr style="color: rgb(var(--black)); margin-top: 0;">

                <?php if (empty($target["age"]) && empty($target["alignment"]) && empty($target["languages"])): ?>
                    <p style='text-align: center;'>Defined by options below.</p>
                <?php endif; ?>
            
                <?php foreach (["age", "alignment", "languages"] as $attr): ?>
                    <?php echo (!empty($target[$attr])) ? "<p style='margin-bottom: 1rem;'><b>" . ucfirst($attr) . ". </b>" . $target[$attr] . "</p>" : "" ?>
                <?php endforeach; ?>
            </div>
        </div>

        <? // abilities / traits ?>
        <?php if (count($target["abilities"])): ?>
        <hr >
        
        <div class="row mx-auto no-margin" style="width: 85%;">
            <p class="lg title">Abilities</p>
            <hr style="color: rgb(var(--black));">

            <?php foreach ($target["abilities"] as $ability): ?>
            <div class="indent-li" style="margin-bottom: 1rem;">
                <?php renderAbility($ability, 1); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (count($target["options"])): 
        uasort($target["options"], function ($a, $b) {
            return strcmp($a["name"], $b["name"]);
        }); ?>
        <hr >
        
        <div class="mx-auto row p-margin" style="width: 75%;">
            <p class="lg title">Options & Subraces</p>
            <hr style="color: rgb(var(--black));">
            <div class="accordion" id="race-options">
                <?php foreach ($target["options"] as $name => $option): ?>
                    <div class="accordion-item blue low-opac shadow-lg">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed subclass-accordion"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#<?= $name ?>"
                                    style="grid-template-columns: 1fr auto;">
                                <span><?= $option["name"] ?></span>
                                <span class="sm ms-auto" style="opacity: 0.7;"><?= $option["source"] ?></span>
                            </button>
                        </h2>

                        <div id="<?= $name ?>"
                                class="accordion-collapse collapse">
                            <div class="accordion-body md subrace">
                                <?php include ROOT . "/scripts/constructors/subrace.php"; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
</div>