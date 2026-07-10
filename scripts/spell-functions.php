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
    if ($time[0] == "i") {
        return "Instantaneous";
    } else {
        $result = ($time[1] ?? 1) . " " . $times[$time[0]] . (!empty($time[1]) ? "s" : "");
        if (empty($time[2])) {
            return $result;
        } elseif ($time[2] === true) {
            return "Concentration, up to " . $result;
        } else {
            return $result . ", which you take when " . $time[2];
        }
    }
}

function get_dist($dist) {
    switch ($dist) {
        case "t": return "Touch"; break;
        case "s": return "Self"; break;
        default: return $dist;
    }
}

function get_first_level($desc, $levels) {
    return str_replace("{*}", "<span class='level-replace'>$levels[0]</span>", $desc);
}

?>