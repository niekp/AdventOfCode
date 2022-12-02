<?php

$elves = explode("\n\n", file_get_contents("input/day1.txt"));
$caloriesPerElf = array_map(function ($elf) {
    return array_sum(explode("\n", $elf));
}, $elves);

rsort($caloriesPerElf);

print_r([
    'part 1' => $caloriesPerElf[0],
    'part 2' => array_sum(array_slice($caloriesPerElf, 0, 3)),
]);
