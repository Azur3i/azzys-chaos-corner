<?php

function get_level($level, $school) {
    switch ($level) {
        case 0:
            return ucfirst($school . " cantrip");
        case 1:
            return ("1st-level " . $school);
        case 2:
            return ("2nd-level " . $school);
        case 3:
            return ("3rd-level " . $school);
        default:
            return ($level . "th-level " . $school);
    }
}

function gets_time($time) {
    $times = [
        "a" => "action",
        "b" => "bonus action",
        "d" => "day",
        "h" => "hour",
        "m" => "minute",
        "re" => "reaction",
        "ro" => "round"
    ];

    // time[0] = type, time[1] = amount, time[2] = condition
    if ($time[0] === "i") {
        return "Instantaneous";
    } elseif ($time[0] === "s") {
        return "Special";
    } else {
        $result = ($time[1] ?? 1) . " " . $times[$time[0]] . (!empty($time[1]) ? "s" : "");
        $ritual = "";
        if (!empty($time[3])) {
            $ritual = " (ritual)";
        }

        if (empty($time[2])) {
            return $result . $ritual;
        } elseif ($time[2] === true) {
            return "Concentration, up to " . $result . $ritual;
        } else {
            return $result . ", which you take when " . $time[2];
        }
    }
}

function get_time($time) {
    if ($time["time"] === "instant") {return "Instantaneous";}
    if ($time["time"] === "special") {return "Special";}
    if ($time["time"] === "until dispelled") {return "Until dispelled";}
    if ($time["time"] === "unlimited") {return "Unlimited";}

    $result = $time["amount"] . " " . $time["time"] . ($time["amount"] !== 1 ? "s" : "");

    if (isset($time["concentration"])) {
        $result = "Concentration, up to " . $result;
    } elseif ($time["time"] === "reaction") {
        $result = $result . ", which you take when " . $time["condition"];
    }
    if (isset($time["ritual"])) {
        $result = $result . " (ritual)";
    }

    return $result;
}

function get_first_level($desc, $levels) {
    return str_replace("{*}", "<b><span style='color: rgb(var(--pink));' class='level-replace'>$levels[0]</span></b>", $desc);
}

?>