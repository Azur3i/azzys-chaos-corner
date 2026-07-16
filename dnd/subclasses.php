<?php 

define("ROOT",  __DIR__ . "/..");
$classes = json_decode(file_get_contents(ROOT . "/dnd/data/subclasses.json"), true);

require_once ROOT . "/scripts/functions.php";

?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-md-12 col-lg-6 mx-auto">
            <h1 class="title">Subclasses</h1>

            <div class="content-list blue low-opac">
                <?php $i = 0; ?>
                <?php foreach ($classes as $clsName => $subclasses): ?>

                    <h2 class="lg title"><?= ucwords(str_replace("-", " ", $clsName)) ?></h2>
                    <div class="row mx-auto col-10">
                        <div class='accordion' id="sbcls-list-<?= $clsName ?>">
                            <?php foreach ($subclasses as $sbclsName => $subclass): ?>
                            
                                <?php $id = "sbcls-$i"; ?>
                                <div class="accordion-item blue low-opac shadow-lg">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed subclass-accordion"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#<?= $id ?>"
                                                style="grid-template-columns: 1fr auto;">
                                            <span><?= $subclass["name"]; ?></span>
                                            <span class="sm ms-auto" style="opacity: 0.7;"><?= $subclass["source"] ?></span>
                                        </button>
                                    </h2>

                                    <div id="<?= $id ?>"
                                        class="accordion-collapse collapse"
                                        data-bs-parent="#sbcls-list-<?= $clsName ?>">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <p class="md"><?= implode('</p><br ><p class="md">', renderText($subclass["desc"])); ?></p>
                                            </div>
                                            <hr >
                                            <div class="row">
                                                <a class="mx-auto sm button" href="/dnd/<?= $clsName ?>/<?= strtolower(str_replace(" ", "-", $subclass["name"])) ?>">Go to subclass page →</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if ($clsName !== array_key_last($classes)): ?>
                        <hr >
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include ROOT . "/tpl/footer.php"; ?>