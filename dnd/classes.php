<?php 
define("ROOT",  __DIR__ . "/..");
$classes = json_decode(file_get_contents(ROOT . "/dnd/data/classes.json"), true);
$types = [
    "Full casters" => "full",
    "Half casters" => "half",
    "Martial" => "none"
]
?>

<?php include ROOT . "/tpl/header.php"; ?>
<?php include ROOT . "/tpl/header-dnd.php"; ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="main-content col-6 mx-auto">
            <h1 class="title">Classes</h1>
            <p class="lg title">Details about the various combat specializations for player characters!</p>
            <div class="text-center content-list blue low-opac">
                <?php foreach ($types as $title => $type): ?>
                    <h2><?= $title ?></h2>
                    <div class="row mx-auto">
                        <?php foreach ($classes as $name => $attr): ?>
                            <?php if ($attr["magic"]["caster"] == $type): ?>
                                <a class="col button" href="class/<?= $name ?>"><?= $attr["name"] ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($title !== array_key_last($types)): ?>
                        <hr >
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include ROOT . "/tpl/footer.php"; ?>