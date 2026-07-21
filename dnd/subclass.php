<?php define("ROOT",  __DIR__ . "/.."); ?>

<?php 
$target = $_GET["subclass"]; 
$mainClass = $_GET["class"];

include ROOT . "/tpl/header.php"; 
include ROOT . "/tpl/header-dnd.php";
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-md-12 col-lg-9 mx-auto">
            <?php include ROOT . "/scripts/constructors/subclass.php"; ?>
        </div>
    </div>
</div>

<div id="title" data-id="<?= $target["name"] ?> - "></div>

<?php include ROOT . "/tpl/footer.php"; ?>