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
    if (!empty($ability["type"])):
        if (str_contains($ability["type"], "list")): ?>
            <ul>
                <?php foreach ($ability["content"] as $newAbility): ?>
                    <li class="md"><?= renderAbility($newAbility, $z + 1) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif;
            
        if (str_contains($ability["type"], "table")):
            $x = $ability["content"]["header"] ? " header" : ""; ?>
            <table class='ability$x'>
                <?php foreach ($ability["content"]["content"] as $i => $row):
                    if ($i === 0): ?>
                        <thead>
                            <tr><th><?= implode("</th><th>", $row) ?></th></tr>
                        </thead>
                        <tbody>
                    <?php else: ?>
                            <tr><td><?= implode("</td><td>", $row) ?></td></tr>
                    <?php endif;
                endforeach; ?>
                        </tbody>
            </table>
        <?php endif;

        if (str_contains($ability["type"], "subclass")):
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
                                    <a class="ms-auto sm button" href="/dnd/subclass/<?= strtolower(str_replace(" ", "-", $sbcls["name"])) ?>">Go to subclass page -></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++;
                endforeach; ?>
            </div>
        <?php endif;
    endif;
} ?>