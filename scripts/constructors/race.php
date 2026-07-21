<?php

if (!defined("ROOT")) {define("ROOT",  __DIR__ . "/../..");}

require_once ROOT . "/scripts/php/general.php";

if (!empty($_GET)) {
    
    $races = json_decode(file_get_contents(ROOT . "/dnd/data/races.json"), true);

    $raceName = $_GET["target"];
    $target = $races[$raceName];
}

function renderASI ($asi) {
    if (count($asi) == 7) {
        $increase = $asi["total"] / 6;
        return "Your ability scores each increase by $increase.";
    }

    if (count($asi) == 1) {
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

<div class="content-list" style="margin: 0;" id="race-display" race="<?= $raceName ?>">
    <div class="scroll">
        <h1 class="title" id="racename" data-name="<?= $target["name"] ?>"><?= $target["name"] ?></h1>
        <p class="sm title" style="opacity: 0.5;">Source: <?= $target["source"] ?></p>

        <hr >

        <p class="md title"><?= implode('</p><br ><p class="md title">', $target["desc"]) ?></p>
        
        <hr >
        
        <div class="mx-auto md row p-margin" style="width: 85%;">
            <div class="col">
                <p class="lg title">Body</p>

                <hr >
                <div class="mx-auto md" style="width: 90%;">
                    
                    <?php echo (!empty($target["asi"])) ? "<p><b>Alignment Score Increase. </b>" . renderASI($target['asi']) . "</p>" : ""; ?>
                    <?php echo (!empty($target["creatureType"])) ?
                        "<p><b>Creature Type. </b>You are a" .
                        (startsWith($target["creatureType"], ["a", "e", "i", "o", "u"]) ? "n" : "") .
                        " " .
                        $target["creatureType"] .
                        ".</p>" : "";
                    ?>
                    
                    <?php echo (!empty($target["size"])) ? "<p><b>Size. </b>" . $target['size'] . "</p>" : "" ?>
                    <?php echo (!empty($target["speed"])) ? "<p><b>Speed. </b>Your base walking speed is " . $target['speed'] . "ft.</p>" : "" ?>

                    <?php if (empty($target["creatureType"]) && empty($target["size"]) && empty($target["speed"])) {
                        echo "<p style='text-align: center;'>Defined by options below.</p>";
                    } ?>
                </div>

                
            </div>
            
            <div class="col">
                <p class="lg title">Mind</p>

                <hr >
                <div class="mx-auto md" style="width: 90%;">
                    <?php echo (!empty($target["age"])) ? "<p><b>Age. </b>" . $target['age'] . "</p>" : "" ?>
                    <?php echo (!empty($target["alignment"])) ? "<p><b>Alignment. </b>" . $target['alignment'] . "</p>" : "" ?>
                    <?php echo (!empty($target["languages"])) ? "<p><b>Languages. </b>" . $target['languages'] . "</p>" : "" ?>

                    <?php if (empty($target["age"]) && empty($target["alignment"]) && empty($target["languages"])) {
                        echo "<p style='text-align: center;'>Defined by options below.</p>";
                    } ?>
                </div>
            </div>
        </div>

        <?php if (count($target["abilities"])): ?>
        <hr >
        
        <p class="lg title">Abilities</p>
        <div class="mx-auto row p-margin" style="width: 85%;">
            <?php foreach ($target["abilities"] as $ability) {renderAbility($ability, 1);} ?>
        </div>
        <?php endif; ?>

        <?php if (count($target["options"])): 
        uasort($target["options"], function ($a, $b) {
            return strcmp($a["name"], $b["name"]);
        }); ?>
        <hr >

        <p class="lg title">Options & Subraces</p>
        <div class="mx-auto row p-margin" style="width: 75%;">
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
                            <div class="accordion-body md" style="padding-bottom: 0;">
                                <?php include ROOT . "/scripts/constructors/subrace.php"; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    
    </div>
</div>