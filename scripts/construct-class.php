<?php
$classes = json_decode(file_get_contents(ROOT . "/dnd/data/classes.json"), true);
$class = $classes[$target];
$levels = [
    [1, 2], [3, 4],
    [5, 6], [7, 8],
    [9, 10], [11, 12],
    [13, 14], [15, 16],
    [17, 18], [19, 20]
];

?>

<div class="content-list">
    <h1 class="title"><?= $class["name"] ?></h1>
    <hr >
    <p class="lg title"><?= implode('</p><br ><p class="lg title">', $class["desc"]) ?></p>
    <p class="md title" style="opacity: 0.5;"><i>You must have a <?= implode(" and ", $class["mc-scores"]) ?> score of 13 or higher in order to multiclass in or out of this class.</i></p>
    
    <hr >
    
    <div class="row">
        <div class="col">
            <h2 class="title">Hitpoints (HP):</h2>
            <ul>
                <li class="lg"><b>Hit Dice: </b>1d<?= $class["hitdie"] ?> per <?= $class["name"] ?> level</li>
                <li class="lg"><b>HP at lv1: </b><?= $class["hitdie"] ?> + CON</li>
                <li class="lg"><b>HP increase: </b>1d<?= $class["hitdie"] ?> (<?= $class["hitdie"] / 2 + 1 ?>) + CON per level</li>
            </ul>
        </div>

        <div class="col">
            <h2 class="title">Proficiencies:</h2>
            <ul>
                <li class="lg"><b>Armor: </b><?= ucfirst(implode(", ", $class["proficiencies"]["armor"])) ?></li>
                <li class="lg"><b>Weapons: </b><?= ucfirst(implode(", ", $class["proficiencies"]["weapons"])) ?></li>
                <li class="lg"><b>Tools: </b><?= ucfirst(implode(", ", $class["proficiencies"]["tools"])) ?></li>
                <li class="lg"><b>Saving throws: </b><?= ucfirst(implode(", ", $class["proficiencies"]["saves"])) ?></li>
                <li class="lg"><b>Skills: </b>Choose <?= $class["proficiencies"]["skills"][0] ?> from <?= ucfirst(implode(", ", $class["proficiencies"]["skills"][1])) ?></li>
            </ul>
        </div>

        <div class="col">
            <h2 class="title">Equipment:</h2>
            <ul>
                <?php foreach ($class["equipment"] as $equipment): ?>
                    <li class="lg"><?= ucfirst(implode(", or ", $equipment)) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <div class="row">
        <?php include ROOT . "/scripts/construct-spellslot-table.php"; ?>
    </div>

    <?php foreach ($levels as $i => $row): ?>
        <hr >
        <div class="row mx-auto">
            <?php if ($i % 2 == 0): ?>
                <h4 class="title" style="opacity: 0.5;">Proficiency bonus: +<?= $i / 2 + 2 ?></h4>
            <?php endif; ?>
            <?php foreach ($row as $level): ?>
                <div class="col">
                    <?php include ROOT . "/scripts/construct-class-level.php"; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    
</div>