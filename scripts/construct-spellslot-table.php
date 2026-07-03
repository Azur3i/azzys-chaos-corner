<?php if ($class["magic"]["caster"] != "none"): ?>
    <hr >
    
    <h2 class="title"><?= $class["name"] ?>'s spell slot table:</h2>

    <table class="col spells <?= $class["magic"]["caster"] == "half" ? "half" : "" ?>">
        
        <thead>
            <tr>
                <th>Levels</th>
                <?php foreach (["1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th", "9th"] as $num): ?>
                    <th><?= $num ?></th>
                <?php endforeach; ?>
                <th>Spells Known</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach (range(1, 20) as $i): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= implode("</td><td>", $class["magic"]["slots"][$i]) ?></td>
                    <?php if ($class["magic"]["type"] == "prep" && $i === 1): ?>
                        <td rowspan="20">
                            <?= $class["magic"]["mod"] ?> + <?= $class["magic"]["caster"] === "half" ? "1/2 * " : "" ?><?= strtolower($class["name"]) ?> level
                        </td>

                    <?php elseif ($class["magic"]["type"] == "known"): ?>
                        <td><?= $class["magic"]["amount"][$i - 1] ?></td>

                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
        
    </table>
<?php endif; ?>