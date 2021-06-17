<?php

class XmasExploiter {
    private array $input;
    private const PREAMBLE_LENGTH = 25;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    private function findSums(array $stack, int $number): array {
        $sums = array();
        foreach ($stack as &$itemA) {
            foreach ($stack as &$itemB) {
                if ($itemA + $itemB == $number) {
                    $sums[] = [$itemA, $itemB];
                }
            }
        }

        return $sums;
    }

    public function getWeakness(): int {
        foreach ($this->input as $key => &$number) {
            if ($key <= self::PREAMBLE_LENGTH - 1) {
                continue;
            }

            $stack = array_slice($this->input, $key - self::PREAMBLE_LENGTH, self::PREAMBLE_LENGTH);
            if (!$this->findSums($stack, $number)) {
                return $number;
            }

        }
    }

    private function getExploitStack(): array {
        $weakness = $this->getWeakness();

        for ($x = 0; $x < count($this->input); $x++) {
            $stack = [];
            for ($y = $x; $y < count($this->input); $y++) {
                $stack[] = $this->input[$y];
                $sum = array_sum($stack);

                if ($sum == $weakness) {
                    return $stack; 
                } else if ($sum > $weakness) {
                    break;
                }
            }
        }
    }

    public function getExploit(): int {
        $stack = $this->getExploitStack();
        sort($stack);
        return $stack[0] + $stack[count($stack)-1];
    }
}

$input = explode("\n", file_get_contents("input/day9.txt"));
$exploiter = new XmasExploiter($input);
print_r([
    'part 1' => $exploiter->getWeakness(),
    'part 2' => $exploiter->getExploit(),
]);
