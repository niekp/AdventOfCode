<?php

$input = explode("\n", file_get_contents("input/day8.txt"));

$pointer = 0;
$accumulator = 0;
$executed = array(); // history

while (true) {
    // Break if the command was already visited or is out of bounds
    if (
        count($input) - 1 < $pointer
        || in_array($pointer, $executed)
    ) {
        break;
    }
    // Keep track of the history
    $executed[] = $pointer;

    // Get the command and execute it
    $command = explode(' ', $input[$pointer]);

    switch ($command[0]) {
        case 'nop':
            $pointer++;
            break;
        case 'acc':
            $accumulator += $command[1];
            $pointer++;
            break;
        case 'jmp':
            $pointer += $command[1];
            break;
    }

}

print_r($accumulator);
