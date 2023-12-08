<?php

declare(strict_types=1);

$start_time = microtime(true);

$caliberation_values = explode("\n", file_get_contents('./input.txt'));
$test1 = explode("\n", "1abc2
pqr3stu8vwx
a1b2c3d4e5f
treb7uchet");

$test2 = explode("\n", "two1nine
eightwothree
abcone2threexyz
xtwone3four
4nineeightseven2
zoneight234
7pqrstsixteen");

$letters = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
// eightwothree == 823 not eigh23
$nums = ['z0o', 'o1e', 't2o', 't3e', 'f4r', 'f5e', 's6x', 's7n', 'e8t', 'n9e'];

$part1 = array_reduce(array_map(fn ($value) => filter_var($value, FILTER_SANITIZE_NUMBER_INT), $caliberation_values), fn ($sum = 0, string $x) => $sum += (int) substr($x, 0, 1) . substr($x, -1));
$part2 = array_reduce(array_map(fn ($value) => filter_var(str_replace($letters, $nums, $value), FILTER_SANITIZE_NUMBER_INT), $caliberation_values), fn ($sum = 0, string $x): int => $sum += (int) substr($x, 0, 1) . substr($x, -1));
echo "part1: " . $part1;
echo "\npart2: " . $part2;

echo "\nExecution time: " . round(microtime(true) - $start_time, 4) . " seconds";
echo "\nPeak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB";
