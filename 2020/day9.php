<?php

class XmasExploiter {
    private array $input;
    private const PREAMBLE_LENGTH = 25;

    public function __construct($input)
    {
        $this->input = $input;
    }

    private function findSums($stack, $number) {
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

    public function getWeakness() {
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
}

$input = explode("\n", file_get_contents("input/day9.txt"));
$exploiter = new XmasExploiter($input);
print_r($exploiter->getWeakness());
