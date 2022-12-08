<?php

$input = file_get_contents("input/day6.txt");

function getStartMarker(string $input, $marker): int
{
	$buffer = [];
	foreach (str_split($input) as $index => $char) {
		$buffer[] = $char;
		$buffer_length = count($buffer);

		if ($buffer_length < $marker) {
			continue;
		}
		if ($buffer_length > $marker) {
			array_shift($buffer);
		}

		if (count(array_unique($buffer)) === $marker) {
			return ($index + 1);
		}
	}

	return 0;
}

print_r([
	'part 1' => getStartMarker($input, 4),
	'part 2' => getStartMarker($input, 14),
]);
