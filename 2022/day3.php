<?php

class rucksack
{
	private array $compartment1;

	private array $compartment2;

	public function __construct($input)
	{
		if (strlen($input) % 2 !== 0) {
			throw new \InvalidArgumentException('Invalid number of items in rucksack');
		}

		$this->compartment2 = str_split($input);
		$this->compartment1 = array_splice($this->compartment2, 0, strlen($input) / 2);
	}

	public function part1(): int
	{
		$intersect = array_unique(array_intersect($this->compartment1, $this->compartment2));
		return array_reduce($intersect, function ($carry, $item) {
			return $carry + $this->getPriority($item);
		}, 0);
	}

	private function getPriority($item): int
	{
		$priority = ord($item);
		return $priority > 90 ? $priority - 96 : $priority - 38;
	}
}

$rucksacks = array_filter(explode("\n", file_get_contents("input/day3.txt")));

$part1 = array_reduce($rucksacks, function ($carry, $rucksack) {
	$sack = new rucksack($rucksack);
	return $carry + $sack->part1();
}, 0);

print_r([
	'part 1' => $part1,
]);
