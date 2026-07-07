<?php if ($target["magic"]["caster"] != "none"): ?>
    <hr >
    <div class="row">
        <h2 class="title"><?= $target["name"] ?>'s spell slot table:</h2>

        <table class="col spells <?= $target["magic"]["caster"] == "full" ? "" : $target["magic"]["caster"] ?>">
            
            <thead>
                <tr>
                    <th class="md">Levels</th>
                    <?php foreach (["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th"] as $num): ?>
                        <th class="md"><?= $num ?></th>
                    <?php endforeach; ?>
                    <th class="md">Spells Known</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach (range(1, 20) as $i): ?>
                    <tr>
                        <td class="md"><?= $i ?></td>
                        <td class="md"><?= implode("</td><td>", $target["magic"]["slots"][$i]) ?></td>
                        <?php if ($target["magic"]["type"] == "prep" && $i === 1): ?>
                            <td rowspan="20" class="md">
                                <?= $target["magic"]["mod"] ?> + <?= $target["magic"]["caster"] === "half" ? "1/2 * " : "" ?><?= strtolower($target["name"]) ?> level
                            </td>

                        <?php elseif ($target["magic"]["type"] == "known"): ?>
                            <td class="md"><?= $target["magic"]["amount"][$i - 1] ?></td>

                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            
        </table>
    </div>
<?php endif; ?>