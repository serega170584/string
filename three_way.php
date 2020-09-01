<?php
function getBorders($list, $firstIndex, $lastIndex, $charIndex = 0)
{
    $rightIndex = $lastIndex;
    $leftBorderIndex = $rightBorderIndex = $firstIndex;
    while ($rightIndex > $rightBorderIndex) {
        if (ord($list[$rightBorderIndex][$charIndex]) > ord($list[$rightBorderIndex + 1][$charIndex])) {
            $fn = $list[$rightBorderIndex + 1];
            $list[$rightBorderIndex + 1] = $list[$leftBorderIndex];
            $list[$leftBorderIndex] = $fn;
            ++$leftBorderIndex;
            ++$rightBorderIndex;
        } elseif (ord($list[$rightBorderIndex][$charIndex]) < ord($list[$rightBorderIndex + 1][$charIndex])) {
            $fn = $list[$rightBorderIndex + 1];
            $list[$rightBorderIndex + 1] = $list[$rightIndex];
            $list[$rightIndex] = $fn;
            --$rightIndex;
        } else {
            ++$rightBorderIndex;
        }
    }
    return [
        $list,
        $leftBorderIndex,
        $rightBorderIndex
    ];
}

$fdescr = fopen('three_way.txt', 'r');
$length = (int)fgets($fdescr);
$firstNames = [];
$charIndex = 0;
while ($row = fgets($fdescr)) {
    $firstNames[] = sprintf("%-{$length}s", trim($row));
}

$parts = [[0, count($firstNames) - 1]];
$indexQueue = [];
while ($charIndex < $length) {
    $generatedParts = [];
    while ($parts) {
        $part = array_shift($parts);
        $firstIndex = $part[0];
        $lastIndex = $part[1];
        $borders = getBorders($firstNames, $firstIndex, $lastIndex, $charIndex);
        $firstNames = $borders[0];
        $leftBorderIndex = $borders[1];
        $rightBorderIndex = $borders[2];
        if ($leftBorderIndex - 1 >= $firstIndex) {
            $indexQueue[] = [$firstIndex, $leftBorderIndex - 1];
        }
        if ($rightBorderIndex + 1 <= $lastIndex) {
            $indexQueue[] = [$rightBorderIndex + 1, $lastIndex];
        }
        $generatedParts[] = [$leftBorderIndex, $rightBorderIndex];
        while ($indexQueue) {
            $borderIndexes = array_shift($indexQueue);
            $firstIndex = $borderIndexes[0];
            $lastIndex = $borderIndexes[1];
            $borders = getBorders($firstNames, $firstIndex, $lastIndex, $charIndex);
            $firstNames = $borders[0];
            $leftBorderIndex = $borders[1];
            $rightBorderIndex = $borders[2];
            if ($leftBorderIndex - 1 >= $firstIndex) {
                $indexQueue[] = [$firstIndex, $leftBorderIndex - 1];
            }
            if ($rightBorderIndex + 1 <= $lastIndex) {
                $indexQueue[] = [$rightBorderIndex + 1, $lastIndex];
            }
            $generatedParts[] = [$leftBorderIndex, $rightBorderIndex];
        }
    }
    $parts = $generatedParts;
    ++$charIndex;
}
var_dump($firstNames);
die('asd');