<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>
<?php $target = $_GET["class"]; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-9 mx-auto">
            <?php include ROOT . "/scripts/construct-class.php"; ?>
        </div>
    </div>
</div>

<?php include ROOT . "/tpl/footer.php"; ?>