<?php 
$abilities = $target["levels"][$level]; 
require_once ROOT . "/scripts/functions.php";
?>

<h2 class="title lg">Level <?= $level ?></h2>
<div class="accordion" id="level-<?= $level ?>">
    <?php foreach ($abilities as $ability):
        $id = "level-$level-" . strtolower(preg_replace("/[^a-z0-9]/i", "", $ability["name"])); ?>

        <div class="accordion-item blue low-opac">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#<?= $id ?>">
                    <?= $ability["name"] ?>
                </button>
            </h2>

            <div id="<?= $id ?>"
                    class="accordion-collapse collapse">
                <div class="accordion-body">
                    <?= renderAbility($ability, 0, strtolower($target["name"])); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>