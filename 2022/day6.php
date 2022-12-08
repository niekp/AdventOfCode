<?php

$input = file_get_contents("input/day6.txt");

$buffer = [];
foreach (str_split($input) as $index => $char) {
	$buffer[] = $char;
	$buffer_length = count($buffer);

	if ($buffer_length < 4) {
		continue;
	}
	if ($buffer_length > 4) {
		array_shift($buffer);
	}

	if (count(array_unique($buffer)) === 4) {
		print_r([
			'part 1' => $index + 1,
		]);
		break;
	}
}
