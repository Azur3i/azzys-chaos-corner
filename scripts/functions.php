<?php

function renderText($str) {
    $str = renderActions($str);
    $str = renderDamageColors($str);
    return $str;
}

function renderDamageColors($str) {
    $dmgTypes = [
        "fire", "cold", "acid", "poison", "lightning",
        "thunder", "necrotic", "radiant", "psychic",
        "force", "bludgeoning", "piercing", "slashing"
    ];
    foreach($dmgTypes as $type) {
        $str = str_replace("@$type damage", "<span class='$type-dmg'>$type damage</span>", $str);
        $str = str_replace("@$type", "<span class='$type-dmg'>$type</span>", $str);
    }
    return $str;
}

function renderActions($str) {
    $actions = [
        "dash", "disengage", "hide"
    ];
    foreach ($actions as $action) {
        $str = str_replace("@$action", "<i>$action</i>", $str);
    }
    return $str;
}

function renderAbility($ability, $z) {
    if (!empty($ability["name"]) && $z !== 0) {
        echo '<p class="md"><b>' . $ability["name"] . '. </b>' . renderText($ability["desc"]) . '</p>';
    } else {
        echo '<p class="md">' . renderText($ability["desc"]) . '</p>';
    }

    if (($ability["type"] ?? null) === "list") {
        echo "<ul>";
            foreach ($ability["content"] as $newAbility) {
                echo '<li class="md">';
                renderAbility($newAbility, $z + 1);
                echo '</li>';
            }
        echo "</ul>";
    } 
    
    elseif (($ability["type"] ?? null) === "table") {
        $x = $ability["content"]["header"] ? " header" : "";
        echo "<table class='ability$x'>";
            foreach ($ability["content"]["content"] as $i => $row) {
                if ($i === 0) {
                    echo "<thead>";
                        echo "<tr><th>";
                            echo implode("</th><th>", $row);
                        echo "</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";
                } else {
                    echo "<tr><td>";
                        echo implode("</td><td>", $row);
                    echo "</td></tr>";
                }
            }
            echo "</tbody>";
        echo "</table>";
    }
}

?>