<?php

function getSeatHashcode($input) {
    $rows = range(0, 127);
    $seats = range(0, 7);

    for ($i = 0; $i < strlen($input); $i++) {
        $char = ($input[$i]);

        if ($char == 'F') {
            $rows = array_slice($rows, 0, count($rows) / 2);
        } else if ($char == 'B') {
            $rows = array_slice($rows, count($rows) / 2);
        } else if ($char == 'R') {
            $seats = array_slice($seats, count($seats) / 2);
        } else if ($char == 'L') {
            $seats = array_slice($seats, 0, count($seats) / 2);
        }
    }

    return $rows[0] * 8 + $seats[0];
}

$input = explode("\n", file_get_contents("input/day5.txt"));
$codes = array_map('getSeatHashcode', $input);
rsort($codes);
print_r($codes[0]);
