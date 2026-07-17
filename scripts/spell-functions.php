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
    if (is_array($levels[0])) {
        
        foreach ($levels[0] as $i => $param) {
            $j = $i + 1;
            $desc = str_replace("{{$j}}", "<b><span style='color: rgb(var(--pink));' class='level-replace-$i'>$param</span></b>", $desc);
        }
    } else {
        $desc = str_replace("{*}", "<b><span style='color: rgb(var(--pink));' class='level-replace-0'>$levels[0]</span></b>", $desc);
    }

    return $desc;
}

?>