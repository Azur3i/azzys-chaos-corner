<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php 
$target = $_GET["subclass"]; 
$mainClass = $_GET["class"];

include ROOT . "/tpl/header.php"; 
include ROOT . "/tpl/header-dnd.php";
?>

<div class="main-content container-fluid row no-margin">
    <div class="blue box col-md-12 col-lg-9 mx-auto">
        <?php include ROOT . "/scripts/constructors/subclass.php"; ?>
    </div>
</div>

<?php $className = ucwords(str_replace("-", " ", $mainClass)); ?>
<div id="title" data-id="<?= ucwords($className) . ": " . $target["name"] ?> - "></div>

<?php include ROOT . "/tpl/footer.php"; ?>