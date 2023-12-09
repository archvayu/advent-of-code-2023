<?php

declare(strict_types=1);

$start_time = microtime(true);

$game_stats = explode("\n", file_get_contents('./input.txt'));
$condition = "only 12 red cubes, 13 green cubes, and 14 blue cubes";

$test1 = explode("\n", "Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red
Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red
Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green");

$condition1 = "only 12 red cubes, 13 green cubes, and 14 blue cubes";

$cubes = explode(",", $condition);
$cubes_count = array();
foreach ($cubes as $cube) {
    preg_match('/(\d+) (\w+)/', $cube, $matches);
    if (count($matches)) {
        array_shift($matches);
        $cubes_count[$matches[1]] = $matches[0];
    }
}


$part1 = array_reduce(array_map(function ($stat) use ($cubes_count) {
    $stats = explode(":", $stat);
    $id = filter_var($stats[0], FILTER_SANITIZE_NUMBER_INT);
    preg_match_all('/(\d+) (\w+)/', $stats[1], $matches);
    $reveals = array();
    $check = true;
    if (count($matches) > 0) {
        foreach ($matches[0] as $match) {
            $parts = explode(" ", $match);
            if (count($parts)) {
                if (array_key_exists($parts[1], $reveals)) {
                    $reveals[$parts[1]] = max($reveals[$parts[1]], $parts[0]);
                } else {
                    $reveals[$parts[1]] = $parts[0];
                }
                if ($reveals[$parts[1]] > $cubes_count[$parts[1]])
                    $check = false;
            }
        }
    }
    return ($check) ? $id : 0;
}, $game_stats), fn ($sum = 0, $id) => $sum += $id);

$part2 = array_reduce(array_map(function ($stat) {
    $stats = explode(":", $stat);
    preg_match_all('/(\d+) (\w+)/', $stats[1], $matches);
    $reveals = array();
    if (count($matches) > 0) {
        foreach ($matches[0] as $match) {
            $parts = explode(" ", $match);
            if (count($parts)) {
                if (array_key_exists($parts[1], $reveals)) {
                    $reveals[$parts[1]] = max($reveals[$parts[1]], $parts[0]);
                } else {
                    $reveals[$parts[1]] = $parts[0];
                }
            }
        }
    }
    return  array_reduce($reveals, fn ($power, $reveal) => $power *= $reveal, 1);
}, $game_stats), fn ($sum, $power) => $sum += $power);

echo "part1: " . $part1;
echo "\npart2: " . $part2;

echo "\nExecution time: " . round(microtime(true) - $start_time, 4) . " seconds";
echo "\nPeak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB";
