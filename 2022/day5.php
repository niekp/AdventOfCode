<?php

class Crate
{
	public string $content;

	public function __construct(string $content)
	{
		$this->content = $content;
	}
}

class Stack
{
	private array $crates;

	public function __construct(?array $crates = [])
	{
		$this->crates = $crates ?? [];
	}

	public function pop(): Crate
	{
		return array_pop($this->crates);
	}

	public function push(Crate $crate): void
	{
		$this->crates[] = $crate;
	}

	public function peek(): Crate
	{
		return current(array_reverse($this->crates));
	}
}

class Crane
{
	public static function move(Stack $origin, Stack $target)
	{
		$target->push($origin->pop());
	}
}

class Instruction
{
	public int $amount;

	public Stack $from;

	public Stack $to;

	public function __construct(int $amount, Stack $from, Stack $to)
	{
		$this->amount = $amount;
		$this->from = $from;
		$this->to = $to;
	}
}

class Parser
{
	private array $stacks;

	private array $instructions;

	public function __construct(string $input)
	{
		$rows = explode("\n", $input);
		$height = $this->getStackMaxHeight($rows);
		$width = $this->getWidth($rows[$height - 1]);

		$this->extractStacks($rows, $width, $height);
		$this->extractInstructions($rows, $height);
	}

	/**
	 * @param int $width
	 * @param array $rows
	 * @param int $height
	 */
	public function extractStacks(array $rows, int $width, int $height): void
	{
		$this->stacks = [];
		for ($i = 0; $i < $width; $i++) {
			$this->stacks[] = new Stack();
		}

		foreach (array_reverse(array_splice($rows, 0, $height)) as $row) {
			$matches = [];
			if (!preg_match(sprintf('/^%s$/', str_repeat('(?:.(.)..?)', $width)), $row, $matches)) {
				return;
			}

			for ($i = 0; $i < $width; $i++) {
				if (!empty(trim($matches[$i + 1]))) {
					$this->stacks[$i]->push(new Crate($matches[$i + 1]));
				}
			}
		}
	}

	/**
	 * @param array $rows
	 * @param int $height
	 */
	public function extractInstructions(array $rows, int $height): void
	{
		$instructions = array_filter(array_splice($rows, $height + 2));
		foreach ($instructions as $instruction) {
			$matches = [];
			preg_match("/(\d+).*(\d+).*(\d+)/", $instruction, $matches);

			$this->instructions[] = new Instruction(
				$matches[1],
				$this->stacks[$matches[2] - 1],
				$this->stacks[$matches[3] - 1]
			);
		}
	}

	private function getStackMaxHeight($rows): int
	{
		foreach ($rows as $i => $row) {
			if (preg_match('/\d/', $row)) {
				return $i;
			}
		}
	}

	private function getWidth(string $row): int
	{
		return substr_count($row, ']');
	}

	/** @return Stack[] */
	public function getStacks(): array
	{
		return $this->stacks;
	}

	/** @return Instruction[] */
	public function getInstructions(): array
	{
		return $this->instructions;
	}
}

$input = file_get_contents("input/day5.txt");
$parser = new Parser($input);

foreach ($parser->getInstructions() as $instruction) {
	for ($i = 0; $i < $instruction->amount; $i++) {
		Crane::move($instruction->from, $instruction->to);
	}
}

$result = array_map(function (Stack $stack) {
	return $stack->peek()->content ?? '-';
}, $parser->getStacks());

print_r([
	'part 1' => implode($result),
]);
