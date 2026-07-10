<?php
define("ROOT",  __DIR__ . "/..");

$spells = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

$targetSpell = $_POST["spell"];
$level = $_POST["level"];

echo $spells[$targetSpell]["levels"][$level];
?>