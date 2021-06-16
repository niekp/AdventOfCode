<?php
class validator
{
    private const REQUIRED_FIELDS = [
        'byr' => 'Birth Year',
        'iyr' => 'Issue Year',
        'eyr' => 'Expiration Year',
        'hgt' => 'Height',
        'hcl' => 'Hair Color',
        'ecl' => 'Eye Color',
        'pid' => 'Passport ID',
        //'cid' => 'Country ID',
    ];

    private array $passports;

    public function __construct($input)
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

        $this->passports = $input;
    }

    private function validate_field($field, $value)
    {
        switch ($field) {
            case 'byr':
                return is_numeric($value) && $value >= 1920 && $value <= 2002;
            case 'iyr':
                return is_numeric($value) && $value >= 2010 && $value <= 2020;
            case 'eyr':
                return is_numeric($value) && $value >= 2020 && $value <= 2030;
            case 'hgt':
                if (str_contains($value, 'cm')) {
                    $height = str_replace('cm', '', $value);
                    return $height >= 150 && $height <= 193;
                } else if (str_contains($value, 'in')) {
                    $height = str_replace('in', '', $value);
                    return $height >= 59 && $height <= 76;
                }

                return false;
            case 'hcl':
                return !!preg_match('/#(?:[0-9a-f]{6})/', $value);
            case 'ecl':
                return in_array($value, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth']);
            case 'pid':
                return !!preg_match('/\d{9}/', $value);
            default:
                return true;
        }
    }

    private function is_valid($passport)
    {
        return !array_filter(
            array_keys(self::REQUIRED_FIELDS),
            fn ($field) => !array_key_exists($field, $passport)
                || !$this->validate_field($field, $passport[$field])
        );
    }

    public function get_valid_passports()
    {
        return array_filter($this->passports, [$this, 'is_valid']);
    }
}

$validator = new validator(file_get_contents("input/day4.txt"));
$valid_passports = $validator->get_valid_passports();

var_dump(count($valid_passports));

//print_r($passports);
