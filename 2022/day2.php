<?php

class day2
{
	private int $score = 0;

	private array $turns;

	public function __construct(array $turns)
	{
		$this->turns = array_filter($turns);
	}

	public function execute(): day2
	{
		$this->score = 0;
		foreach ($this->turns as $turn) {
			[$opponent, $own] = explode(' ', $turn);
			$own = chr(ord($own) - 23);
			$this->score += $this->calculateScore($opponent, $own);
		}

		return $this;
	}

	public function getScore(): int
	{
		return $this->score;
	}

	private function calculateScore(string $opponent, string $own): int
	{
		$score = ord($own) - 64;

		if ($opponent === $own) {
			$score += 3;
		} else {
			$score += $this->isWin($opponent, $own) ? 6 : 0;
		}

		return $score;
	}

	public function isWin($opponent, $own): bool
	{
		if ($opponent === 'A' && $own === 'B') {
			return true;
		}
		if ($opponent === 'B' && $own === 'C') {
			return true;
		}
		if ($opponent === 'C' && $own === 'A') {
			return true;
		}

		return false;
	}
}

$turns = explode("\n", file_get_contents("input/day2.txt"));
$day2 = (new day2($turns))->execute();

print_r([
	'score' => $day2->getScore(),
]);

