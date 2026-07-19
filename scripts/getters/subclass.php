<?php 
define("ROOT",  __DIR__ . "/..");
require_once(ROOT . "/scripts/functions.php");

$subclasses = json_decode(file_get_contents(ROOT . "/dnd/data/subclasses.json"), true);
$cls = $_POST["cls"];
$sbcls = $_POST["sbcls"];

$levels = $subclasses[$cls][$sbcls]["levels"];
$result = [];

foreach ($levels as $i => $abilities):
    ob_start();
    foreach ($abilities as $abilityId => $ability): ?>
    <div class="accordion-item blue low-opac">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sbcls-<?= $i ?>-<?= $abilityId ?>">
                <?= $ability["name"] ?>
            </button>
        </h2>

        <div id="sbcls-<?= $i ?>-<?= $abilityId ?>"
                class="accordion-collapse collapse">
            <div class="accordion-body">
                <?= renderAbility($ability, 0); ?>
            </div>
        </div>
    </div>
    <?php endforeach;
    $result[] = ob_get_clean();
endforeach;

echo json_encode($result);
?>