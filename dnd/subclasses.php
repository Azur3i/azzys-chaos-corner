<?php 

define("ROOT",  __DIR__ . "/..");
$classes = json_decode(file_get_contents(ROOT . "/dnd/data/subclasses.json"), true);

require_once ROOT . "/scripts/php/general.php";

?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>

<div class="main-content container-fluid row">
    <div class="lavender box col-md-12 col-lg-10 mx-auto row">
        <h1 class="title">Subclasses</h1>
        <hr >

        <?php foreach ($classes as $clsName => $subclasses): ?>
        <div class="col-md-12 col-lg-4 no-margin">
            <h2 class="lg title"><?= ucwords(str_replace("-", " ", $clsName)) ?></h2>
            <div class="mx-auto col-11">
                <div class="accordion" id="sbcls-list-<?= $clsName ?>">
                    <?php foreach ($subclasses as $sbclsName => $subclass): ?>
                    
                    <div class="accordion-item blue low-opac shadow-lg">
                        
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed subclass-accordion"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#<?= "sbcls-$sbclsName" ?>"
                                    style="grid-template-columns: 1fr auto;">
                                <span><?= $subclass["name"]; ?></span>
                                <span class="sm ms-auto" style="opacity: 0.7;"><?= $subclass["source"] ?></span>
                            </button>
                        </h2>

                        <div id="<?= "sbcls-$sbclsName" ?>"
                            class="accordion-collapse collapse"
                            data-bs-parent="#sbcls-list-<?= $clsName ?>">
                            <div class="accordion-body">
                                <div class="row">
                                    <p class="md title"><?= implode('</p><p class="md title" style="margin-top: 1rem;">', renderText($subclass["desc"])); ?></p>
                                </div>
                                <hr >
                                <div class="row">
                                    <a class="mx-auto sm button" href="/dnd/<?= $clsName ?>/<?= strtolower(str_replace(" ", "-", $subclass["name"])) ?>">Go to subclass page →</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                        
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if ((array_search($clsName, array_keys($classes)) + 1) % 3 === 0 && $clsName !== array_key_last($classes)): ?>
        <hr >
        <?php endif; ?>
        
        <?php endforeach; ?>
    </div>
</div>

<data id="title" data-id="subclass"></data>
<script>document.title = "Subclasses - Azzy's Chaos Corner";</script>

<?php include ROOT . "/tpl/footer.php"; ?>