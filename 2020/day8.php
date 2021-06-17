<?php
class Program {
    private int $pointer = 0;
    private int $accumulator = 0;
    private array $history = array();

    public function __construct($commands)
    {
        $this->commands = $commands;
    }
    
    /**
     * Run the program. 
     *
     * @return array | [bool error, int accumulator]
     */
    public function run(): array {
        $error = false;
        while (true) {
            // Break if the command was already visited or is out of bounds
            if (count($this->commands) - 1 < $this->pointer) {
                break;
            } else if (in_array($this->pointer, $this->history)) {
                $error = true;
                break;
            }
            // Keep track of the history
            $this->history[] = $this->pointer;

            // Get the command and execute it
            $command = $this->commands[$this->pointer];

            switch ($command['command']) {
                case 'nop':
                    $this->pointer++;
                    break;
                case 'acc':
                    $this->accumulator += $command['amount'];
                    $this->pointer++;
                    break;
                case 'jmp':
                    $this->pointer += $command['amount'];
                    break;
            }
        }

        return [
            'accumulator' => $this->accumulator,
            'error' => $error
        ];
    }
}

class Debugger {
    private array $commands;
    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }
    
    /**
     * Get a copy of the commands to alter
     *
     * @return array
     */
    private function getCommandsCopy(): array {
        return (new ArrayObject($this->commands))->getArrayCopy();
    }
    
    /**
     * Try to swap each commandA for commandB (one at a time).
     * Check if this results in a combination that doesn't give an error
     *
     * @param  string $commandA
     * @param  string $commandB
     * @return int
     */
    private function trySwap(string $commandA, string $commandB): ?int {
        foreach (array_filter($this->commands, fn($c) => $c['command'] == $commandA) as $key => &$command) {
            $try_commands = $this->getCommandsCopy();
    
            $try_commands[$key]['command'] = $commandB;
    
            $program = new Program($try_commands);
            if (($output = $program->run())
                && !$output['error']) {
                return $output['accumulator'];
            }
        }
        return null;
    }
    
    /**
     * Debug and correct the input and return a valid output.
     *
     * @return void
     */
    public function debug() {
        if ($output = $this->trySwap('jmp', 'nop')) {
            return $output;
        }
        if ($output = $this->trySwap('nop', 'jmp')) {
            return $output;
        }
        return null;
    }
}

// Split the output into a 'command' array
$input = explode("\n", file_get_contents("input/day8.txt"));
$commands = array_map(function($command) {
    $splitted = explode(' ', $command);
    return [
        'command' => $splitted[0],
        'amount' => $splitted[1],
    ];
}, $input);

$debugger = new Debugger($commands);
print_r($debugger->debug());
