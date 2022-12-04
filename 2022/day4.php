<?php

$input = array_filter(explode("\n", file_get_contents("input/day4.txt")));

// Setup pairs
$pairs = array_map(static function ($pair) {
	return array_map(static function ($elf) {
		return range(...explode('-', $elf));
	}, explode(',', $pair));
}, $input);

// Filter containing sets
$fully_containing_sections = array_filter($pairs, static function ($pair) {
	if (count(array_intersect($pair[0], $pair[1])) === count($pair[0])) {
		return true;
	}
	if (count(array_intersect($pair[1], $pair[0])) === count($pair[1])) {
		return true;
	}

	return false;
});

// Find partial matches
$partially_overlapping_sections = array_filter($pairs, static function ($pair) {
	return array_intersect($pair[0], $pair[1]);
});

print_r([
	'part 1' => count($fully_containing_sections),
	'part 2' => count($partially_overlapping_sections),
]);
