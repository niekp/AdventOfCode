<?php

class Ship
{
    private array $input;
    private array $map;
    private array $position;
    private array $speed;
    private const TREE = '#';
    private const FREE = 'O';
    private const ENCOUNTER = 'X';

    public function __construct($map, $speedX, $speedY)
    {
        $this->input = $map;
        $this->map = $map;
        $this->position = [
            'X' => 0,
            'Y' => 0,
        ];
        $this->speed['X'] = $speedX;
        $this->speed['Y'] = $speedY;
    }

    private function enlargeMap()
    {
        foreach ($this->map as $key => &$row) {
            $row .= $this->input[$key];
        }
    }

    private function mark()
    {
        $this->map[$this->position['Y']][$this->position['X']] =
            $this->map[$this->position['Y']][$this->position['X']] == self::TREE ? self::ENCOUNTER : self::FREE;
    }

    private function fly()
    {
        $this->position['X'] += $this->speed['X'];
        $this->position['Y'] += $this->speed['Y'];

        return $this->position;
    }

    public function execute()
    {
        $height = count($this->map);
        $this->mark();

        do {
            $this->fly();

            if ($this->position['X'] >= strlen($this->map[0])) {
                $this->enlargeMap();
            }

            $this->mark();

        } while ($this->position['Y'] < $height - 1);

        return implode("\n", $this->map);
    }

    public function countEncounters()
    {
        $characters = array_merge(...array_map('str_split', $this->map));
        return array_count_values($characters)[self::ENCOUNTER];
    }
}

$input = explode("\n", file_get_contents("input/day3.txt"));
$outputs = array();

foreach ([[1, 1], [3, 1], [5, 1], [7, 1], [1, 2]] as &$speed) {
    $ship = new Ship($input, $speed[0], $speed[1]);
    $ship->execute();
    $outputs[] = $ship->countEncounters();
}

print(array_product($outputs));
