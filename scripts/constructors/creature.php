<?php

if (!defined("ROOT")) {define("ROOT",  __DIR__ . "/../..");}

require_once ROOT . "/scripts/php/bestiary.php";

if (!empty($_GET)) {
    
    $statblocks = json_decode(file_get_contents(ROOT . "/dnd/data/statblocks.json"), true);

    $creatureName = $_GET["target"];
    $statblock = $statblocks[$creatureName];
}

?>

<div class="content-list" style="padding: 1.25rem; margin: 0;" id="spell-display" spell="<?= $creatureName ?>">
    <div class="scroll">
        <div class="row spell-list align-items-center" style="padding: 0; padding-left: 1.5rem;">
            <h1 class="xlg col" id="creaturename" data-name="<?= $statblock["name"] ?>"><?= $statblock["name"] ?></h1>
            <h3 class="md col" style="text-align: right;">Source: <?= $statblock["source"] ?></h3>

            <h2 class="lg"><?= $statblock["subname"] ?? "" ?></h2>

            <p class="md" style="opacity: 0.7; margin-bottom: 0.5rem;"><?= ucfirst($statblock["size"]) ?> <?= $statblock["creatureType"] ?>, <?= renderAlignment($statblock["alignment"]) ?></p>
        </div>

        <?php if (!empty($statblock["desc"])): ?>
        <hr >

        <div class="row spell-list align-items-center w-75 mx-auto" style="padding-bottom: 0.5rem; margin: 0;">
            <p class="md title"><?= implode("<br ><br >", $statblock["desc"]) ?></p>
        </div>
        <?php endif; ?>

        

        <div style="background: rgb(var(--blue) / 0.05); border-radius: 0.8rem; border: 1px solid rgb(var(--blue) / 0.3); padding: 0.5rem 0; margin-bottom: 0.5rem;">
            <? // row for HP, AC, speed and senses ?>
            <div class="row spell-list align-items-center" style="padding-bottom: 0.5rem; margin: 0;">
                <div class="col title">
                    <p class="lg">Hitpoints</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="md"><?= renderHP($statblock["hitdice"], $statblock["size"], getMod($statblock["stats"]["CON"])) ?></p>
                </div>
                <div class="col title">
                    <p class="lg">Armor Class</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="md"><?= renderAC($statblock["ac"]) ?></p>
                </div>
                <div class="col title">
                    <p class="lg">Speed</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="md"><?= renderSpeed($statblock["speed"]) ?></p>
                </div>
                <div class="col title">
                    <p class="lg">Senses</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="md"><?= renderSenses($statblock["senses"]) ?></p>
                </div>
            </div>

            <hr >

            <? // row for game statistics ?>
            <div class="row spell-list align-items-center" style="padding-bottom: 0.5rem; margin: 0;">
                <?php foreach ($statblock["stats"] as $stat => $num): ?>
                <div class="col title">
                    <p class="lg"><?= $stat ?></p>
                    <hr style="color: rgb(var(--black));">
                    <p class="md"><?= $num ?> (<?= $num < 9 ? "" : "+" ?><?= getMod($num) ?>)</p>
                </div>
                <?php endforeach; ?>
            </div>

            <hr >
            <? // row for weaknesses, resistances and immunities ?>
            <div class="row spell-list align-items-center" style="padding-bottom: 0.5rem; margin: 0;">
                <div class="col title">
                    <p class="md">Damage Vulnerabilities</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= renderDamageColors(implode(", ", array_map(fn($x) => "@$x", $statblock["dmgVulnerable"]))) ?? "None" ?></p>
                </div>
                <div class="col title">
                    <p class="md">Damage Resistances</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= renderDamageColors(implode(", ", array_map(fn($x) => "@$x", $statblock["dmgResistant"]))) ?? "None" ?></p>
                </div>
                <div class="col title">
                    <p class="md">Damage Immunities</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= renderDamageColors(implode(", ", array_map(fn($x) => "@$x", $statblock["dmgImmune"]))) ?? "None" ?></p>
                </div>
                <div class="col title">
                    <p class="md">Condition Immunities</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= empty($statblock["conditionImmune"]) ? "None" : implode(", ", $statblock["conditionImmune"]) ?></p>
                </div>
            </div>
            
            <hr >

            <? // row for senses, skill proficiencies, saving throws and proficiency bonus ?>
            <div class="row spell-list align-items-center" style="padding-bottom: 0.5rem; margin: 0;">
                <?php $prof = getProf($statblock["cr"]) ?>
                <div class="col title">
                    <p class="md">Skill Proficiencies</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= renderSkills($statblock["skills"], $statblock["stats"], $prof) ?></p>
                </div>
                <div class="col title">
                    <p class="md">Saving Throw Proficiencies</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= renderSaves($statblock["saves"], $statblock["stats"], $prof) ?></p>
                </div>
                <div class="col title">
                    <p class="md">Proficiency Bonus</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm">+<?= $prof ?></p>
                </div>
                <div class="col title">
                    <p class="md">Challenge Rating</p>
                    <hr style="color: rgb(var(--black));">
                    <p class="sm"><?= renderCR($statblock["cr"]) ?></p>
                </div>
            </div>

        </div>

        <? // actions, bonus actions and reactions ?>
        <?php foreach (["abilities", "actions", "bonusActions", "reactions"] as $type): ?>
        <div class="row mx-auto w-75" style="margin: 0;"> 


            <?php if (!empty($statblock[$type])): ?>
            <div class="row spell-list align-items-center" style="padding: 0.5rem 0; margin: 0;">
                <p class="lg title"><?= $type == "bonusActions" ? "Bonus Actions" : ucfirst($type) ?></p>
                <hr style="color: rgb(var(--black));">
                <?php foreach ($statblock[$type] as $action): ?>
                    <div class="row align-items-center" style="margin: 0; padding: 0.5rem 0;">
                        <div class="col-3">
                            <p class="md" style="text-align: right;">
                                <b><?= is_array($action["name"]) ? implode("<br >", $action["name"]) : $action["name"] ?></b>
                            </p>
                        </div>
                        <div class="col-9">
                            <?= renderAbility($action) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php endif; ?>
        </div>
        
        <?php if (!empty($statblock[$type])) {
            echo "<hr >";
        } ?>
        
        <?php endforeach; ?>
        

    </div>
</div>