<div class="main-content container-fluid row no-margin">
    <div id="select-view" class="blue box col-md-12 col-lg-9 row">
        <?// scroll-list ?>
        <div id="select-select" class="blue box col-md-12 col-lg-4">
            
            <?// searchbar ?>
            <div id="select-search" class="row">
                <p class="sm col-auto">Search:</p>
                <div class="col align-items-center" style="position: relative;">
                    <input id="searchbar" class="sm py-1 px-3" style="width: 100%;"></input>
                    <a id="clear-button" class="sm button col-aut white" style="">X</a>
                </div>
                <a class="sm button-toggle pink col-auto high-opac" style="height: 2rem; margin-left: 1rem; padding: 0 1rem;" id="filter-button">Filters</a>
            </div>

            <?// filter menu ?>
            <div id="select-filter" class="pink box scroll">
                <div class="row no-margin">
                    <p id="counter" class="md title">Filters:</p>
                </div>

                <hr >

                <div class="row no-margin">
                    <?php 
                    $andor = []; $or = []; $line = false;

                    foreach ($filterOptions as $typeName => $type): ?>
                    <div class="select-filter-option <?= $type["type"] == "half" ? "col" : "" ?> row pink">
                        <div class="row no-margin">
                            <p class="md title col-auto"><?= ucwords(str_replace("-", " ", $typeName)) ?></p>
                            <?php if ($type["andor"]): $andor[] = $typeName; ?>
                            <div class="col-auto">
                                <a class="button-switch sm andor <?= $typeName ?>-andor active" data-id="and">AND</a><a class="button-switch sm andor <?= $typeName ?>-andor" data-id="or">OR</a>
                            </div>
                            <?php else: $or[] = $typeName; endif; ?>
                        </div>

                        <?php switch ($type["type"]) {
                            case "half":
                                $n = 5; break;
                            case "width":
                                $n = "auto"; break;
                            case "height":
                                $n = 11; break;
                        }
                        foreach ($type["var"] as $i => $t): ?>
                        <a data-id="<?= strtolower($t) ?>" class="sm button-toggle-2 high-opac filter-toggle toggle-<?= $typeName ?> col-<?= $n ?>"><?= $i ?></a>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php  
                    if ($type["type"] == "half") {
                        if ($line) {
                            $line = false;
                            echo "<hr >";
                        } else {
                            $line = true;
                        }
                    } else {
                        echo "<hr >";
                    } ?>

                    <?php endforeach;?>
                </div>
            </div>
            
            <?// spell list ?>
            <div id="select-list" class="scroll row">
                <ul class="lavender" id="pinned">
                </ul>
                <ul class="blue" id="unpinned">
                    <?php $i = 0;
                    // x -> id of spell; y -> spell properties
                    foreach ($items as $x => $y): ?>
                        <a 
                            class="button-list high-opac"
                            style="grid-template-columns: 1fr auto;"
                            id="<?= $x ?>"
                            href="#<?= $x ?>"

                            data-name="<?= $y["name"] ?>"
                            <?php $q = []; foreach ($filterOptions as $filter) {
                                $q[] = is_array($y[$filter["key"]]) ? implode(' ', $y[$filter["key"]]) : $y[$filter["key"]];
                            }?>
                            data-filter="<?= implode(" ", $q) ?>"
                        >
                            <span class="md txt"><?= $y["name"] ?></span>
                            <span class="sm txt-2"><?= $subHead[$y["name"]] ?></span>
                        </a>
                        <?php $i++;
                    endforeach; ?>
                </ul>
            </div>

        </div>

        <?// display ?>
        <div id="select-box" class="scroll col-md-12 col-lg-8"></div>
    </div>
</div>

<data id="andor" data-or='<?= json_encode($or) ?>' data-and='<?= json_encode($andor) ?>'></data>
<data id="title" data-id="<?= $title ?>"></data>
<script src="/scripts/js/list-view.js"></script>