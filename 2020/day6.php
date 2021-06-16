<?php

$input = array_map(
    fn ($group) => array_map(
        'str_split', // Split person into answers (each character)
        explode("\n", $group) // Explode group into persons (each line)
    ),
    explode(
        "\n\n",
        file_get_contents("input/day6.txt") // Explode groups (blank line)
    )
);

function flatten(array $array)
{
    $return = array();
    array_walk_recursive($array, function ($a) use (&$return) {
        $return[] = $a;
    });
    return $return;
}

function countDistinctAnswers(array $answers): int
{
    return count(array_unique(flatten($answers)));
}

function countUnanimousAnswers(array $answers): int
{
    $persons = count($answers);

    return count(
        array_filter( // Filter the answers where the amount equals the number of persons in the group
            array_count_values(
                flatten($answers)
            ),
            fn ($amount) => $amount == $persons
        )
    );
}

$distinct = array_reduce($input, function ($carry, $item) {
    $carry += countDistinctAnswers($item);
    return $carry;
});

$unanimous = array_reduce($input, function ($carry, $item) {
    $carry += countUnanimousAnswers($item);
    return $carry;
});

print_r([
    'Distinct' => $distinct,
    'Unanimous' => $unanimous,
]);
