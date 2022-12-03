<?php

class prioritizer
{
	public static function prioritize(string $item): int
	{
		return $item > 'Z' ? ord($item) - 96 : ord($item) - 38;
	}
}

class rucksack
{
	private array $compartment1;

	private array $compartment2;

	public function __construct($input)
	{
		[$this->compartment1, $this->compartment2] = array_map(
			static fn($p) => str_split($p), // Split compartment into parts
			str_split($input, strlen($input) / 2) // Split in 2
		);
	}

	public function part1(): int
	{
		$intersect = array_unique(array_intersect($this->compartment1, $this->compartment2));

		return array_reduce($intersect, static function ($carry, $item) {
			return $carry + prioritizer::prioritize($item);
		}, 0);
	}

	public function getItems(): array
	{
		return array_merge($this->compartment1, $this->compartment2);
	}
}

class inspector
{
	private array $rucksacks;

	public function __construct(array $rucksacks)
	{
		$this->rucksacks = $rucksacks;
	}

	public function findBadge()
	{
		$badges = array_unique(
			array_intersect(
				...array_map(static fn(rucksack $rucksack) => $rucksack->getItems(),
					$this->rucksacks)
			)
		);

		return reset($badges);
	}
}

$rucksacks = array_map(
	static fn($rucksack) => new rucksack($rucksack),
	array_filter(explode("\n", file_get_contents("input/day3.txt")))
);

// Part 1
$part1 = array_reduce($rucksacks, static fn($carry, rucksack $rucksack) => $carry + $rucksack->part1(), 0);

// Part 2
$groups = array_chunk($rucksacks, 3);
$inspectors = array_map(static fn($group) => new inspector($group), $groups);
$badges = array_map(static fn(inspector $inspector) => $inspector->findBadge(), $inspectors);

$part2 = array_reduce($badges, static function ($carry, $badge) {
	return $carry + prioritizer::prioritize($badge);
}, 0);

print_r([
	'part 1' => $part1,
	'part 2' => $part2,
]);
