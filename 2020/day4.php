<?php
const REQUIRED_FIELDS = [
    'byr' => 'Birth Year',
    'iyr' => 'Issue Year',
    'eyr' => 'Expiration Year',
    'hgt' => 'Height',
    'hcl' => 'Hair Color',
    'ecl' => 'Eye Color',
    'pid' => 'Passport ID',
    //'cid' => 'Country ID',
];

function get_passports($input)
{
    // Parse the input. Split on blank line
    $input = explode("\n\n", $input);
    // Correct the input so each value is on a new line and split in into an array
    $input = array_map(fn ($r) => explode("\n", str_replace(' ', "\n", $r)), $input);

    // Map the passport items to an associative array (split the row on :)
    $input = array_map(function ($passport) {
        $result = array();
        array_walk($passport, function (&$item) use (&$result) {
            $splitted = explode(':', $item);
            $result[$splitted[0]] = $splitted[1];
        });

        return $result;
    }, $input);

    return $input;
}

function is_valid($passport) {
    return !array_filter(
        array_keys(REQUIRED_FIELDS), 
        fn ($field) => !array_key_exists($field, $passport)
    );
}

$valid_passports = count(array_filter(get_passports(file_get_contents("input/day4.txt")), 'is_valid'));

print_r($valid_passports);

//print_r($passports);
