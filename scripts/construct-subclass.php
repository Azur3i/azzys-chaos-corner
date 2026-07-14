<?php
$subclasses = json_decode(file_get_contents(ROOT . "/dnd/data/subclasses.json"), true);
$target = $subclasses[$mainClass][$target];
$subclassTypes = [
    "artificer" => "Artificer Specialism",
    "barbarian" => "Primal Path",
    "bard" => "Bard College",
    "cleric" => "Divine Domain",
    "druid" => "Druid Circle",
    "fighter" => "Martial Archetype",
    "monk" => "Monastic Tradition",
    "oracle" => "Knowledge Base",
    "paladin" => "Sacred Oath",
    "ranger" => "Ranger Conclave",
    "rogue" => "Roguish Archetype",
    "sorcerer" => "Sorcerous Origin",
    "warlock" => "Otherworldy Patron",
    "wizard" => "Arcane Tradition"
]

?>

<div class="content-list">
    <a  class="button white md position-absolute m-3"
        href="javascript:history.back()"
        title="Back to the previous page."
        >
        <img src="/assets/img/back.png" style="width: 2rem;">
    </a>

    <h1 class="xlg title"><a href="/dnd/<?= $mainClass ?>"><?= ucwords($mainClass) . "</a>: " . $target["name"] ?></h1>
    <p class="sm title" style="opacity: 0.5;"><?= $subclassTypes[$mainClass] ?></p>

    <hr >

    <p class="md title"><?= implode('</p><br ><p class="md title">', $target["desc"]) ?></p>
    
    <?php include ROOT . "/scripts/construct-spellslot-table.php"; ?>

    <?php foreach ($target["levels"] as $level => $ability): ?>
        <hr >
        <div class="row mx-auto justify-content-center">
            <div class="col-10">
                <?php include ROOT . "/scripts/construct-level.php"; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <hr >
    

    <p class="sm title" style="opacity: 0.7;">Source: <?= $target["source"]; ?></p>
    
</div>