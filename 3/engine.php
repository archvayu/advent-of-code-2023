<?php

declare(strict_types=1);

$start_time = microtime(true);

$part_numbers = explode("\n", file_get_contents('./input.txt'));

$test = explode("\n", "467..114..
...*......
..35..633.
......#...
617*......
.....+.58.
..592.....
......755.
...$.*....
.664.598..");

$valid = array();
$invalid = array();
$test = array();
$total = count($part_numbers);
$gears = array();
foreach ($part_numbers as $key => $row) {
    preg_match_all('/(\d+)/', $row, $matches);
    if (count($matches[0])) {
        $matches = array_shift($matches);
        $previous = 0;
        foreach ($matches as $itr => $match) {
            $adjacent = '';
            $pos = strpos($row, $match, $previous); // offset was required for single digit part numebrs
            $match_index = $pos + strlen($match);
            $start = ($pos > 0) ? $pos - 1 : $pos; // array index
            $limit = $pos + strlen($match); // array index
            ($limit >= strlen($row)) ? $limit -= 1 : $limit;
            $previous = $limit;
            if ($start > 0) {
                $adjacent .= $row[$start];
                if ($row[$start] == '*') {
                    $gears[$key . 'r' . $start][] = $match; // r to distinguish row and index of *
                }
            }
            if ($limit < strlen($row) - 1) {
                $adjacent .= $row[$limit];
                if ($row[$limit] == '*') {
                    $gears[$key . 'r' . $limit][] = $match;
                }
            }
            do {
                if ($key > 0) {
                    $adjacent .= $part_numbers[$key - 1][$start];
                    if ($part_numbers[$key - 1][$start] == '*') {
                        $gears[($key - 1) . 'r' . $start][] = $match;
                    }
                }
                if ($key < $total - 1) {
                    $adjacent .= $part_numbers[$key + 1][$start];
                    if ($part_numbers[$key + 1][$start] == '*') {
                        $gears[($key + 1) . 'r' . $start][] = $match;
                    }
                }
                $start++;
            } while ($start <= $limit);
            if ($adjacent === str_repeat(".", strlen($adjacent))) {
                $invalid[] = $match;
            } else {
                $valid[] = $match;
            }
        }
    }
}

$part1 = array_sum($valid);
$part2 = array_sum(array_map(fn ($ratio) => array_product($ratio), array_filter($gears, fn ($gear) => count($gear) == 2)));

echo "part1: " . $part1;
echo "\npart2: " . $part2;

echo "\nExecution time: " . round(microtime(true) - $start_time, 4) . " seconds";
echo "\nPeak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB";
