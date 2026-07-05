<?php define("ROOT", __DIR__) ?>

<?php include ROOT . "/tpl/header.php" ?>
<?php include ROOT . "/tpl/header-dnd.php" ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-md-9 col-lg-6">
            <h1 class="title xlg">Dungeons & Dragons</h1>
            <p class="title lg">
                This page includes directions towards a database of all
                things D&D, including my own homebrew things.
            </p>
            <hr >
            <div class="row mx-auto blue">
                <a class="col button md" href="/dnd/classes.php">Classes</a>
                <a class="col button md">Subclasses</a>
                <a class="col button md">Races</a>
                <a class="col button md">Spells</a>
                <a class="col button md">Creatures</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/tpl/footer.php" ?>