<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>
<?php $target = $_GET["subclass"]; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-md-12 col-lg-9 mx-auto">
            <?php include ROOT . "/scripts/construct-subclass.php"; ?>
        </div>
    </div>
</div>

<?php include ROOT . "/tpl/footer.php"; ?>