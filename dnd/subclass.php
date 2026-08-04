<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php 
$target = $_GET["subclass"]; 
$mainClass = $_GET["class"];

include ROOT . "/tpl/header.php"; 
include ROOT . "/tpl/header-dnd.php";
?>

<div class="main-content container-fluid row">
    <?php include ROOT . "/scripts/constructors/subclass.php"; ?>
</div>

<?php $className = ucwords(str_replace("-", " ", $mainClass)); ?>

<script>document.title = "<?= ucwords($className) . ": " . $target["name"] ?> - Azzy's Chaos Corner";</script>

<?php include ROOT . "/tpl/footer.php"; ?>