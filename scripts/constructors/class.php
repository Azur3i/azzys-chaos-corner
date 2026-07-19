<?php
$classes = json_decode(file_get_contents(ROOT . "/dnd/data/classes.json"), true);
$class = $target;
$target = $classes[$target];
$levels = [
    [1, 2], [3, 4],
    [5, 6], [7, 8],
    [9, 10], [11, 12],
    [13, 14], [15, 16],
    [17, 18], [19, 20]
];

?>

<div class="content-list">
    <a  class="button white md position-absolute m-2"
        href="javascript:history.back()"
        title="Back to the previous page."
        >
        <img src="/assets/img/back.png" style="width: 2rem;">
    </a>
    <h1 class="title" data-name="<?= $class ?>"><?= $target["name"] ?></h1>
        <p class="sm title" style="opacity: 0.5;"><i>You must have a <?= implode(" and ", $target["mc-scores"]) ?> score of 13 or higher in order to multiclass in or out of this class.</i></p>

    <hr >

    <p class="md title"><?= implode('</p><br ><p class="md title">', $target["desc"]) ?></p>
    
    <hr >
    
    <div class="row">
        <div class="col col-md-12 col-lg-4">
            <h2 class="title lg">Hitpoints (HP):</h2>
            <ul>
                <li class="md"><b>Hit Dice: </b>1d<?= $target["hitdie"] ?> per <?= $target["name"] ?> level</li>
                <li class="md"><b>HP at lv1: </b><?= $target["hitdie"] ?> + CON</li>
                <li class="md"><b>HP increase: </b>1d<?= $target["hitdie"] ?> (<?= $target["hitdie"] / 2 + 1 ?>) + CON per level</li>
            </ul>
        </div>

        <div class="col col-md-12 col-lg-4">
            <h2 class="title lg">Proficiencies:</h2>
            <ul>
                <li class="md"><b>Armor: </b><?= ucfirst(implode(", ", $target["proficiencies"]["armor"])) ?></li>
                <li class="md"><b>Weapons: </b><?= ucfirst(implode(", ", $target["proficiencies"]["weapons"])) ?></li>
                <li class="md"><b>Tools: </b><?= ucfirst(implode(", ", $target["proficiencies"]["tools"])) ?></li>
                <li class="md"><b>Saving throws: </b><?= ucfirst(implode(", ", $target["proficiencies"]["saves"])) ?></li>
                <li class="md"><b>Skills: </b>Choose <?= $target["proficiencies"]["skills"][0] ?> from <?= ucfirst(implode(", ", $target["proficiencies"]["skills"][1])) ?></li>
            </ul>
        </div>

        <div class="col col-md-12 col-lg-4">
            <h2 class="title lg">Equipment:</h2>
            <ul>
                <?php foreach ($target["equipment"] as $equipment): ?>
                    <li class="md"><?= ucfirst(implode(", or ", $equipment)) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <?php include ROOT . "/scripts/constructors/spellslot-table.php"; ?>

    <?php foreach ($levels as $i => $row): ?>
        <hr >
        <div class="row mx-auto">
            <?php if ($i % 2 == 0): ?>
                <h4 class="title sm" style="opacity: 0.5;">Proficiency bonus: +<?= $i / 2 + 2 ?></h4>
            <?php endif; ?>
            <?php foreach ($row as $level): ?>
                <div class="col col-md-12 col-lg-6">
                    <?php include ROOT . "/scripts/constructors/level.php"; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    
</div>