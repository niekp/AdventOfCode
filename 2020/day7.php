<?php
class Bag
{
    private array $contains = array();
    public string $color;
    private string $rule;

    public function __construct(
        string $color,
        string $rule,
    ) {
        $this->color = $color;
        $this->rule = $rule;
    }

    public function add_contains(Bag &$bag, int $amount)
    {
        $this->contains[] = [
            'bag' => $bag,
            'amount' => $amount
        ];
    }

    public function can_contain(string $color): bool
    {
        return !!array_filter($this->contains, function ($contains) use ($color) {
            return $contains['bag']->color == $color || $contains['bag']->can_contain($color);
        });
    }

    public function rules()
    {
        return array_map( // trim and return all rules
            'trim',
            explode( // split on ,
                ',',
                explode( // Get everythin after 'contain'
                    'contain',
                    $this->rule
                )[1]
            )
        );
    }
}

class Organizer
{
    public array $bags = array();

    public function __construct(
        array $rules
    ) {
        // Create bags
        $this->bags = array_map(
            fn ($rule) => new Bag($this->get_bag_color($rule), $rule),
            $rules
        );

        // Add the containing bags
        array_walk($this->bags, function ($bag) {
            $contain_rules = $bag->rules();
            array_walk(
                $contain_rules,
                function ($contains_rule) use (&$bag) {
                    if ($contains_rule == 'no other bags.') {
                        return;
                    }
                    if (preg_match_all('/(\d*) ([A-z ]*) (bag.?\.?)/', $contains_rule, $matches)) {
                        $matchedBag = $this->find_bag(reset($matches[2]));
                        $bag->add_contains($matchedBag, reset($matches[1]));
                    }
                }
            );
        });
    }

    private function get_bag_color(string $rule): string
    {
        return trim(explode("bags", $rule)[0]);
    }

    private function find_bag(string $color): Bag
    {
        $matches = array_filter($this->bags, fn ($bag) => $bag->color == $color);
        return reset($matches);
    }
}

$rules = explode("\n", file_get_contents("input/day7.txt"));

$organizer = new Organizer($rules);

print_r(count(
    array_filter(
        $organizer->bags,
        fn (&$bag) => $bag->can_contain('shiny gold')
    )
));
