<?php

class day2
{
	private array $beatingMove = [
		'A' => 'B',
		'B' => 'C',
		'C' => 'A',
	];

	private array $turns;

	public function __construct(array $turns)
	{
		$this->turns = $turns;
	}

	/**
	 * Execute the puzzle and return the result
	 * @param int $part puzzle part
	 * @return int
	 */
	public function execute(int $part = 1): int
	{
		$score = 0;
		foreach ($this->turns as $turn) {
			[$opponent, $own] = $this->getMoves($turn, $part);
			$score += $this->calculateScore($own, $opponent);
		}

		return $score;
	}

	/** Extract the moves from the data */
	public function getMoves(string $turn, int $part): array
	{
		[$opponent, $own] = explode(' ', $turn);
		$own = chr(ord($own) - 23); // Normalize own move (from X to A for easier comparison)

		if ($part === 2) {
			$own = $this->getMove($opponent, $own);
		}

		return [$opponent, $own];
	}

	/** Get the move based on a strategy */
	private function getMove(string $opponent, string $strategy): string
	{
		if ($strategy === 'A') {
			return array_values(
				       array_filter($this->beatingMove, static function ($move, $key) use ($opponent) {
					       return $key !== $opponent && $move !== $opponent;
				       }, ARRAY_FILTER_USE_BOTH)
			       )[0];
		}
		if ($strategy === 'B') {
			return $opponent;
		}
		if ($strategy === 'C') {
			return $this->beatingMove[$opponent];
		}

		throw new \InvalidArgumentException("Unknown strategy $strategy");
	}

	/** Calculate the score of a game */
	private function calculateScore(string $own, string $opponent): int
	{
		$score = ord($own) - 64;

		if ($opponent === $own) {
			$score += 3;
		} else {
			$score += $this->beatingMove[$opponent] === $own ? 6 : 0;
		}

		return $score;
	}
}

$turns = array_filter(explode("\n", file_get_contents("input/day2.txt")));

print_r([
	'part 1' => (new day2($turns))->execute(),
	'part 2' => (new day2($turns))->execute(2),
]);
