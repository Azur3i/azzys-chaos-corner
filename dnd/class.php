<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>
<?php $target = $_GET["class"]; ?>

<div class="main-content container-fluid row">
    <?php include ROOT . "/scripts/constructors/class.php"; ?>
</div>

<script>document.title = "<?= $target["name"] ?> - Azzy's Chaos Corner";</script>
<script src="/scripts/js/class.js"></script>

<?php include ROOT . "/tpl/footer.php"; ?>