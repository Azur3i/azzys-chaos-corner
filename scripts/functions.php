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

function fetchSubclasses($class) {
    $subclasses = json_decode(file_get_contents("../dnd/data/subclasses.json"), true);
    $result = [];
    foreach ($subclasses as $subclass) {
        if ($subclass["mainClass"] === $class) {
            $result[] = $subclass;
        }
    }
    return $result;
}

function renderAbility($ability, $z, $cls=null) {
    if (!empty($ability["name"]) && $z !== 0) {
        echo '<p class="md"><b>' . $ability["name"] . '. </b>' . renderText($ability["desc"]) . '</p>';
    } else {
        echo '<p class="md">' . renderText($ability["desc"]) . '</p>';
    }
    if (!empty($ability["type"])) {
        switch ($ability["type"]):
            case "list":
                echo "<ul>";
                    foreach ($ability["content"] as $newAbility) {
                        echo '<li class="md">';
                        renderAbility($newAbility, $z + 1);
                        echo '</li>';
                    }
                echo "</ul>";
                break;
            
            case "table":
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
                break;
            
            case "subclass":
                $sbclss = fetchSubclasses($cls); 
                $i = 0; ?>
                <div class='accordion' id="subclass-list">
                    <?php foreach ($sbclss as $sbcls):
                        $id = "sbcls-$i"; ?>
                        <div class="accordion-item blue low-opac">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?= $id ?>">
                                    <?= $sbcls["name"]; ?>
                                </button>
                            </h2>

                            <div id="<?= $id ?>"
                                class="accordion-collapse collapse"
                                data-bs-parent="#subclass-list">
                                <div class="accordion-body">
                                    <div class="row">
                                        <p class="md"><?= implode('</p><br ><p class="md">', $sbcls["desc"]); ?></p>
                                    </div>
                                    <hr >
                                    <div class="row">
                                        <a class="ms-auto sm button" href="/dnd/subclass/<?= strtolower($sbcls["name"]) ?>">Go to subclass page -></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                    <?php endforeach; ?>
                </div>
                <?php break;
        endswitch;
    }
}

?>