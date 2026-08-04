<?php

if (!defined("ROOT")) {define("ROOT",  __DIR__ . "/../..");}

require_once ROOT . "/scripts/php/bestiary.php";

$statblocks = json_decode(file_get_contents(ROOT . "/dnd/data/statblocks.json"), true);
$creatureName = $_GET["target"] ?? $creatureName;

$statblock = $statblocks[$creatureName] ?? null;

if ($statblock):
$prof = getProf($statblock["cr"]);

$statistics = [
    [
        "Hitpoints" => $statblock["hitpoints"] ?? renderHP($statblock["hitdice"], $statblock["size"], getMod($statblock["stats"]["CON"])),
        "Armor Class" => renderAC($statblock["ac"]),
        "Speed" => renderSpeed($statblock["speed"]),
        "Senses" => renderSenses($statblock["senses"])
    ],
    [
        "STR" => $statblock["stats"]["STR"] . " (" . ($statblock["stats"]["STR"] < 9 ? "" : "+") . getMod($statblock["stats"]["STR"]) . ")",
        "DEX" => $statblock["stats"]["DEX"] . " (" . ($statblock["stats"]["DEX"] < 9 ? "" : "+") . getMod($statblock["stats"]["DEX"]) . ")",
        "CON" => $statblock["stats"]["CON"] . " (" . ($statblock["stats"]["CON"] < 9 ? "" : "+") . getMod($statblock["stats"]["CON"]) . ")",
        "INT" => $statblock["stats"]["INT"] . " (" . ($statblock["stats"]["INT"] < 9 ? "" : "+") . getMod($statblock["stats"]["INT"]) . ")",
        "WIS" => $statblock["stats"]["WIS"] . " (" . ($statblock["stats"]["WIS"] < 9 ? "" : "+") . getMod($statblock["stats"]["WIS"]) . ")",
        "CHA" => $statblock["stats"]["CHA"] . " (" . ($statblock["stats"]["CHA"] < 9 ? "" : "+") . getMod($statblock["stats"]["CHA"]) . ")",
    ],
    [
        "Damage Vulnerabilities" => renderDamageColors(implode(", ", array_map(fn($x) => "@$x", $statblock["dmgVulnerable"]))) ?? "None",
        "Damage Resistances" => renderDamageColors(implode(", ", array_map(fn($x) => "@$x", $statblock["dmgResistant"]))) ?? "None",
        "Damage Immunities" => renderDamageColors(implode(", ", array_map(fn($x) => "@$x", $statblock["dmgImmune"]))) ?? "None",
        "Condition Immunities" => empty($statblock["conditionImmune"]) ? "None" : implode(", ", $statblock["conditionImmune"])
    ],
    [
        "Skill Proficiencies" => renderSkills($statblock["skills"], ($statblock["expertise"] ?? null), $statblock["stats"], $prof),
        "Saving Throw Proficiencies" => renderSaves($statblock["saves"], $statblock["stats"], $prof),
        "Proficiency Bonus" => (is_int($prof) ? "+" : "") . $prof,
        "Challenge Rating" => renderCR($statblock["cr"])
    ]
];

?>


<div id="select-display" class="blue box scroll no-margin">
    <?// title ?>
    <div class="row">
        <h1 class="xlg col"><?= $statblock["name"] ?></h1>
        <h3 class="md col" style="text-align: right;">Source: <?= $statblock["source"] ?></h3>
        <a class="md col-auto white ms-auto button-pin"><img src="/assets/img/pin.png" id="pin"></a>

        <h2 class="lg"><?= $statblock["subname"] ?? "" ?></h2>

        <p class="md txt-2">
            <?php printf("%s %s, %s", ucfirst($statblock['size']), $statblock['creatureType'], renderAlignment($statblock['alignment'])) ?>
        </p>
    </div>

    <?php if (!empty($statblock["desc"])): ?>
    <hr >

    <?// description ?>
    <div id="select-desc" class="row">
        <div class="col">
            <p class="md title txt"><?= implode('</p><p class="md title txt">', $statblock["desc"]) ?></p>
        </div>
        <?php if (file_exists(ROOT . "/assets/img/dnd/creatures/$creatureName.png")): ?>
            <img id="item-img" class="col-auto" src="/assets/img/dnd/creatures/<?= $creatureName ?>.png">
        <?php endif; ?>
    </div>
        
    <?php endif; ?>

    <div class="row blue box low-opac title" id="stats">
        <?php foreach ($statistics as $i => $a): ?>
        <div class="row no-margin">
            <?php foreach($a as $n => $b): ?>
            <div class="col">
                <p class="<?= $i < 2 ? "lg" : "md" ?>"><?= $n ?></p>
                <hr class="black">
                <p class="<?= $i < 2 ? "md" : "sm" ?>"><?= $b ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($i !== array_key_last($statistics)): ?>
        <hr >
        <?php endif; ?>

        <?php endforeach; ?>
    </div>

    <?php // actions, bonus actions and reactions
    $actions = [
        "abilities" => "Traits",
        "actions" => "Actions",
        "bonusActions" => "Bonus Actions",
        "reactions" => "Reactions"
    ];
    foreach ($actions as $type => $name): ?>
    <div class="row w-75"> 
        <?php if (!empty($statblock[$type])): ?>
        <div class="row spell-list align-items-center" style="padding: 0.5rem 0; margin: 0;">
            <p class="lg title"><?= $name ?></p>
            <hr class="black">
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
    
    <?php // legendary actions, mythic actions, lair actions 
    $specialActions = [
        "legendaryActions" => "Legendary Actions",
        "lairActions" => "Lair Actions"
    ];
    foreach ($specialActions as $type => $name): ?>
    <div class="row w-75">
        <?php if (!empty($statblock[$type])): ?>
        <div class="row no-margin" style="padding: 0.5rem 0;">
            <p class="lg title"><?= $name ?></p>
            <p class="md title"><?= $statblock[$type]["desc"] ?></p>
            <hr class="black">
            <?php foreach ($statblock[$type]["content"] as $action): ?>
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

<script>document.title = "<?= $statblock["name"] ?> - Bestiary - Azzy's Chaos Corner";</script>

<?php else: ?>
    
<div class="blue box">
<?php include ROOT . "/tpl/404.php"; ?>
</div>

<?php endif; ?>