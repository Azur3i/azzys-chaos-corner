<?php
$subclasses = json_decode(file_get_contents(ROOT . "/dnd/data/subclasses.json"), true);
$target = $subclasses[$target];
$subclassTypes = [
    "rogue" => "Roguish Archetype"
]

?>

<div class="content-list">
    <h1 class="title"><?= $target["name"] ?></h1>
    <p class="sm title" style="opacity: 0.5;"><?= ucwords($target["mainClass"]) ?> Subclass - <?= $subclassTypes[$target["mainClass"]] ?></p>
    <hr >
    <p class="md title"><?= implode('</p><br ><p class="md title">', $target["desc"]) ?></p>
    
    <div class="row">
        <?php include ROOT . "/scripts/construct-spellslot-table.php"; ?>
    </div>

    <?php foreach ($target["levels"] as $level => $ability): ?>
        <hr >
        <div class="row mx-auto justify-content-center">
            <div class="col-10">
                <?php include ROOT . "/scripts/construct-level.php"; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
</div>