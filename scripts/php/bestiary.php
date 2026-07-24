<?php

if (!defined("ROOT")) {define("ROOT", "../../");}
require_once ROOT . "/scripts/php/general.php";

// to-do
function renderAlignment ($attr) {
    if ($attr === "unaligned") {return $attr;}

    switch ($attr[0]) {
        case "l": $result = "lawful "; break;
        case "n": $result = "neutral "; break;
        case "c": $result = "chaotic "; break;
        case "t": $result = "true "; break;
    }

    switch ($attr[1]) {
        case "g": return $result . "good";
        case "n": return $result . "neutral";
        case "e": return $result . "evil";
    }
}

// calculates attribute modifier based on score
function getMod($attr) {
    return $attr < 10 ? ceil(($attr - 10) / 2) : floor(($attr - 10) / 2);
}

// calculates proficiency bonus based on cr
function getProf ($cr) {
    if ($cr === 0) {return 2;}

    else {
        return ceil($cr / 4) + 1;
    }
}

// renders hp based on hit dice, size and CON mod, rendering it as N (NdN + N)
function renderHP ($dice, $size, $con) {
    $hitdice = [
        "tiny" => 4,
        "small" => 6,
        "medium" => 8,
        "large" => 10,
        "huge" => 12,
        "gargantuan" => 20
    ];

    switch (true) {
        case ($con == 0):
            $conAdd = "";
            break;
        case ($con > 0):
            $conAdd = " + " . ($con * $dice);
            break;
        case ($con < 0):
            $conAdd = " - " . abs($con * $dice);
    }

    return floor(($hitdice[$size] + 1) / 2 * $dice + $con * $dice) . " (" . $dice . "d" . $hitdice[$size] . $conAdd . ")";
    
}

// renders ac with optional source as N ({source})
function renderAC ($ac) {
    if (!empty($ac["source"])) {
        return $ac["amount"] . " (" . $ac["source"] . ")";
    } else {
        return $ac["amount"];
    }
}

// renders speeds
function renderSpeed ($spd) {
    $result = [$spd["walk"] . "ft."];
    foreach ($spd as $type => $n) {
        if ($type != "walk") {
            $result[] = $type . " " . $n . "ft.";
        }
    }
    return implode("<br >", $result);
}

// renders senses as Nft. {sense}, separating passive perception
function renderSenses ($senses) {
    $result = [];
    foreach ($senses as $sense => $n) {
        if ($sense != "passivePerception") {
            $result[] = $n . "ft. " . $sense;
        }
    }

    $result[] = "Passive Perception " . $senses["passivePerception"];
    return implode("<br >", $result);
}

// renders skills and calculates bonuses based on modifiers and proficiency bonus
// rendering it as +N to {skill} ({score})
function renderSkills ($skills, $stats, $prof) {
    if (empty($skills)) {return "None";}
    
    $result = [];
    $scores = [
        "athletics" => "STR",
        "acrobatics" => "DEX",
        "sleight of hand" => "DEX",
        "stealth" => "DEX",
        "arcana" => "INT",
        "history" => "INT",
        "investigation" => "INT",
        "nature" => "INT",
        "religion" => "INT",
        "animal handling" => "WIS",
        "insight" => "WIS",
        "medicine" => "WIS",
        "perception" => "WIS",
        "survival" => "WIS",
        "deception" => "CHA",
        "intimidation" => "CHA",
        "performance" => "CHA",
        "persuasion" => "CHA",
    ];

    foreach ($skills as $skill) {
        $score = $scores[$skill];
        $mod = getMod($stats[$score]) + $prof;

        $result[] = ($mod >= 0 ? "+" : "") . $mod . " to " . ucwords($skill) . " (" . $score . ")";
    }

    return implode("<br >", $result);
} 

// renders saving throws and calculates bonuses based on modifiers and proficiency bonus
// rendering it as +N to {skill} saves
function renderSaves ($saves, $stats, $prof) {
    if (empty($saves)) {return "None";}

    $result = [];
    foreach ($saves as $save) {
        $mod = getMod($stats[$save]) + $prof;

        $result[] = ($mod >= 0 ? "+" : "") . $mod . " to " . $save . " saves";
    }

    return implode("<br >", $result);
}

function renderCR ($cr) {
    $xp = [
        "0" => "10",
        "0.125" => "25",
        "0.25" => "50",
        "0.5" => "100",
        "1" => "200",
        "2" => "450",
        "3" => "700",
        "4" => "1.100",
        "5" => "1.800",
        "6" => "2.300",
        "7" => "2.900",
        "8" => "3.900",
        "9" => "5.000",
        "10" => "5.900",
        "11" => "7.200",
        "12" => "8.400",
        "13" => "10.000",
        "14" => "11.500",
        "15" => "13.000",
        "16" => "15.000",
        "17" => "18.000",
        "18" => "20.000",
        "19" => "22.000",
        "20" => "25.000",
        "21" => "33.000",
        "22" => "41.000",
        "23" => "50.000",
        "24" => "62.000",
        "25" => "75.000",
        "26" => "90.000",
        "27" => "105.000",
        "28" => "120.000",
        "29" => "135.000",
        "30" => "155.000"
    ];

    switch ($cr) {
        case 0.125: return "1/8 (" . $xp[$cr] . " XP)";
        case 0.25: return "1/4 (" . $xp[$cr] . " XP)";
        case 0.5: return "1/2 (" . $xp[$cr] . " XP)";
        default: return $cr . " (" . $xp[$cr] . " XP)";
    }
}

?>