<?php

if (!empty($option["desc"])) {
    echo $option["desc"];
    echo "<hr >";
}
echo (!empty($option["asi"])) ? "<p><b>Alignment Score Increase. </b>" . renderASI($option['asi']) . "</p>" : "";

echo (!empty($option["age"])) ? "<p><b>Age. </b>" . $option['age'] . "</p>" : "";
echo (!empty($option["alignment"])) ? "<p><b>Alignment. </b>" . $option['alignment'] . "</p>" : "";
echo (!empty($option["creatureType"])) ?
    "<p><b>Creature Type. </b>You are a" .
    (startsWith($option["creatureType"], ["a", "e", "i", "o", "u"]) ? "n" : "") .
    " " .
    $option["creatureType"] .
    ".</p>" : "";
echo (!empty($option["languages"])) ? "<p><b>Languages. </b>" . $option['languages'] . "</p>" : "";
echo (!empty($option["size"])) ? "<p><b>Size. </b>" . $option['size'] . "</p>" : "";
echo (!empty($option["speed"])) ? "<p><b>Speed. </b>Your base walking speed is " . $option['speed'] . "ft.</p>" : "";

if (count($option["abilities"])) {
    echo "<hr >";
    foreach ($option["abilities"] as $ability) {renderAbility($ability, 1);}
}

?>
