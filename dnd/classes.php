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

<div class="main-content container-fluid row">
    <div class="blue box col-md-10 col-lg-6 mx-auto">
        <h1 class="title">Classes</h1>
        <p class="md title">Details about the various combat specializations for player characters!</p>

        <hr >
        
        <div class="text-center content-list blue low-opac">
            <?php foreach ($types as $title => $type): ?>
                <h2 class="lg"><?= $title ?></h2>
                <div class="row mx-auto">
                    <?php foreach ($classes as $name => $attr): ?>
                        <?php if ($attr["magic"]["caster"] == $type): ?>
                            <a class="col button md shadow-lg" href="<?= $name ?>"><?= $attr["name"] ?></a>
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

<div id="title" data-id="class"></div>
<script>document.title = "Classes - Azzy's Chaos Corner";</script>

<?php include ROOT . "/tpl/footer.php"; ?>