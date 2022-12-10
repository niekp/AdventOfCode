<?php

class Folder
{
	public ?Folder $parent;

	public string $name;

	/** @var File[] */
	public array $files = [];

	/** @var Folder[] */
	public array $folders = [];

	public function __construct(string $name, ?Folder $parent)
	{
		$this->name = $name;
		$this->parent = $parent;
	}

	public function size(): int
	{
		$size = 0;
		foreach ($this->files as $file) {
			$size += $file->size;
		}
		foreach ($this->folders as $folder) {
			$size += $folder->size();
		}

		return $size;
	}

	public function getSizeOfFoldersSmallerThen(int $minimum): int
	{
		$size = 0;
		if ($this->parent && ($folder_size = $this->size()) && $folder_size < $minimum) {
			$size += $folder_size;
		}

		foreach ($this->folders as $folder) {
			$size += $folder->getSizeOfFoldersSmallerThen($minimum);
		}

		return $size;
	}
}

class File
{
	public string $name;

	public int $size;

	public function __construct(string $name, int $size)
	{
		$this->name = $name;
		$this->size = $size;
	}
}

class Computer
{
	public ?Folder $tree = null;

	private string $last_command;

	public function execute($command): void
	{
		$parts = explode(' ', $command);
		$this->last_command = $parts[1];

		switch ($parts[1]) {
			case 'cd':
				$this->cd($parts[2]);
				break;
			case 'ls':
				break;
		}
	}

	private function cd($path): void
	{
		if ($path === '..') {
			$this->tree = $this->tree->parent;

			return;
		}

		$match = array_filter($this->tree?->folders ?? [], static function (Folder $folder) use ($path) {
			return $folder->name === $path;
		});

		if ($match) {
			$folder = reset($match);
			$this->tree = $folder;

			return;
		}

		$this->tree = new Folder($path, $this->tree);
	}

	public function buffer($input): void
	{
		if ($this->last_command !== 'ls') {
			return;
		}

		$parts = explode(' ', $input);

		if ($parts[0] === 'dir') {
			$path = $parts[1];

			$match = array_filter($this->tree->folders, static function (Folder $folder) use ($path) {
				return $folder->name === $path;
			});
			if (!$match) {
				$this->tree->folders[] = new Folder($path, $this->tree);
			}
		} else {
			$this->tree->files[] = new File($parts[1], (int)$parts[0]);
		}
	}

	public function moveToRoot(): void
	{
		while ($this->tree->parent) {
			$this->tree = $this->tree->parent;
		}
	}
}

$input = array_filter(explode("\n", file_get_contents("input/day7.txt")));

$computer = new Computer();
foreach ($input as $command) {
	if (str_starts_with($command, '$')) {
		$computer->execute($command);
		continue;
	}

	$computer->buffer($command);
}

$computer->moveToRoot();
$total = $computer->tree->getSizeOfFoldersSmallerThen(100000);

print_r([
	'part 1' => $total,
]);
