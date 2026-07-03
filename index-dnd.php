<?php define("ROOT", __DIR__) ?>

<?php include ROOT . "/tpl/header.php" ?>
<?php include ROOT . "/tpl/header-dnd.php" ?>

<div class="row">
    <div class="col-3"><br ></div>
    <div class="main-content col-6 text-center">
        <h1>Dungeons & Dragons</h1>
        <p class="lg">
            This page includes directions towards a database of all
            things D&D, including my own homebrew things.
        </p>
        <hr >
        <div class="row mx-auto blue">
            <a class="col button" href="/dnd/classes.php">Classes</a>
            <a class="col button">Subclasses</a>
            <a class="col button">Races</a>
            <a class="col button">Spells</a>
            <a class="col button">Creatures</a>
        </div>
    </div>
    <div class="col-3"><br ></div>
</div>

<?php include __DIR__ . "/tpl/footer.php" ?>