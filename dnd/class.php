<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>
<?php $target = $_GET["class"]; ?>

<div class="main-content bottom-margin container-fluid row">
    <div class="blue box col-md-12 col-lg-9 mx-auto">
        <?php include ROOT . "/scripts/constructors/class.php"; ?>
    </div>
</div>

<div id="title" data-id="<?= $target["name"] ?> - "></div>

<script src="/scripts/js/class.js"></script>

<?php include ROOT . "/tpl/footer.php"; ?>