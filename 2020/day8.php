<?php
class Program {
    private int $pointer = 0;
    private int $accumulator = 0;
    private array $executed = array(); // history

    public function __construct($commands)
    {
        $this->commands = $commands;
    }

    public function run() {
        $error = false;
        while (true) {
            // Break if the command was already visited or is out of bounds
            if (count($this->commands) - 1 < $this->pointer) {
                break;
            } else if (in_array($this->pointer, $this->executed)) {
                $error = true;
                break;
            }
            // Keep track of the history
            $this->executed[] = $this->pointer;

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

function debug_program($commands) {
    $original_commands = (new ArrayObject($commands))->getArrayCopy();

    // JMP => NOP
    foreach (array_filter($commands, fn($c) => $c['command'] == 'jmp') as $key => &$command) {
        $try_commands = (new ArrayObject($original_commands))->getArrayCopy();

        $try_commands[$key]['command'] = 'nop';

        $program = new Program($try_commands);
        if (!$program->run()['error']) {
            return $program->run()['accumulator'];
        }
    }

    // NOP => JMP
    foreach (array_filter($commands, fn($c) => $c['command'] == 'nop') as $key => &$command) {
        $try_commands = (new ArrayObject($original_commands))->getArrayCopy();

        $try_commands[$key]['command'] = 'jmp';

        $program = new Program($try_commands);
        if (!$program->run()['error']) {
            return $program->run()['accumulator'];
        }
    }
}

$input = explode("\n", file_get_contents("input/day8.txt"));
$commands = array_map(function($command) {
    $splitted = explode(' ', $command);
    return [
        'command' => $splitted[0],
        'amount' => $splitted[1],
    ];
}, $input);


print_r(debug_program($commands));
