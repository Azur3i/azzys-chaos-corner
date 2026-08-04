<?php

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