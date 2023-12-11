<?php

declare(strict_types=1);

$start_time = microtime(true);

$scratchcards = explode("\n", file_get_contents('./input.txt'));

$test = explode("\n", "Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53
Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19
Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1
Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83
Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36
Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11");

$part1 = array_sum(array_map(function ($card) {
    $check = array_map(fn ($scratch) => preg_split("/\s+/", $scratch, -1, PREG_SPLIT_NO_EMPTY), explode("|", (explode(":", $card)[1])));
    $win = count(array_intersect($check[0], $check[1]));
    if ($win == 0) return 0;
    if ($win == 1) return 1;
    $points = 1;
    do {
        $points *= 2;
        $win--;
    } while ($win > 1);
    return $points;
}, $scratchcards));

$matches = array_map(function ($card) {
    $check = array_map(fn ($scratch) => preg_split("/\s+/", $scratch, -1, PREG_SPLIT_NO_EMPTY), explode("|", (explode(":", $card)[1])));
    $win = count(array_intersect($check[0], $check[1]));
    return $win;
}, $scratchcards);
$cardCounter = array_fill(0, count($scratchcards), 1);
foreach ($matches as $key => $match) {
    $duplicates = $cardCounter[$key];
    for ($card = 1; $card <= $duplicates; $card++) {
        $pos = $key + 1;
        for ($add = 1; $add <= $match; $add++) {
            $cardCounter[$pos] += 1;
            $pos++;
        }
    }
}
$part2 = array_sum($cardCounter);

echo "part1: " . $part1;
echo "\npart2: " . $part2;

echo "\nExecution time: " . round(microtime(true) - $start_time, 4) . " seconds";
echo "\nPeak memory: " . round(memory_get_peak_usage() / pow(2, 20), 4), " MiB";
