<?php

class Plane
{
    private array $seats = [];
    private array $rows;
    private array $columns;

    public function __construct($rows, $columns)
    {
        $this->rows = range(0, $rows);
        $this->columns = range(0, $columns);

        foreach ($this->rows as &$row) {
            foreach ($this->columns as &$column) {
                $this->seats[] = new Seat($row, $column);
            }
        }
    }

    public function occupySeat($input): void
    {
        $rows = $this->rows;
        $columns = $this->columns;

        for ($i = 0; $i < strlen($input); $i++) {
            $char = ($input[$i]);

            if ($char == 'F') {
                $rows = array_slice($rows, 0, count($rows) / 2);
            } else if ($char == 'B') {
                $rows = array_slice($rows, count($rows) / 2);
            } else if ($char == 'R') {
                $columns = array_slice($columns, count($columns) / 2);
            } else if ($char == 'L') {
                $columns = array_slice($columns, 0, count($columns) / 2);
            }
        }

        $matchingSeats = array_filter($this->seats, fn ($seat) => $seat->row == $rows[0] && $seat->column == $columns[0]);
        array_walk($matchingSeats, fn ($seat) => $seat->full = true);
    }

    public function getHighestId(): int
    {
        $full_seats = $this->getFilteredSeats(true);
        usort($full_seats, function ($a, $b) {
            return $b->seat_id() - $a->seat_id();
        });
        return $full_seats[0]->seat_id();
    }

    private function getFilteredSeats($full = false): array
    {
        return array_filter($this->seats, fn ($seat) => $seat->full == $full);
    }

    private function seat_id_full($id): bool
    {
        return !!array_filter($this->seats, fn ($seat) => $seat->full && $seat->seat_id() == $id);
    }

    public function getEmptySeat(): Seat
    {
        $empty_seats = $this->getFilteredSeats();

        $filtered_seats = array_filter(
            $empty_seats,
            fn ($seat) => $this->seat_id_full($seat->seat_id() - 1) && $this->seat_id_full($seat->seat_id() + 1)
        );

        return reset($filtered_seats);
    }
}

class Seat
{
    public int $row;
    public int $column;
    public bool $full = false;

    public function __construct($row, $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    public function seat_id(): int
    {
        return $this->row * 8 + $this->column;
    }
}

$input = explode("\n", file_get_contents("input/day5.txt"));

$plane = new Plane(127, 7);
foreach ($input as $seat) {
    $plane->occupySeat($seat);
}

print_r(
    [
        'Highest occupied seat' => $plane->getHighestId(),
        'Empty seat' => $plane->getEmptySeat()->seat_id(),
    ],
);
