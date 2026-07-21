<?php
define("ROOT",  __DIR__ . "/../..");
require_once ROOT . "/scripts/php/general.php";

$spells = json_decode(file_get_contents(ROOT . "/dnd/data/spells.json"), true);

$targetSpell = $_POST["spell"];
$level = $_POST["level"];

echo json_encode(renderText($spells[$targetSpell]["levels"][$level]));
?>