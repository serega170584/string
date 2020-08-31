<?php
function getBorders($list, $firstIndex, $lastIndex)
{
    $rightIndex = $lastIndex;
    $leftBorderIndex = $rightBorderIndex = $firstIndex;
    while ($rightIndex > $rightBorderIndex) {
        if (ord($list[$rightBorderIndex]) > ord($list[$rightBorderIndex + 1])) {
            $fn = $list[$rightBorderIndex + 1];
            $list[$rightBorderIndex + 1] = $list[$leftBorderIndex];
            $list[$leftBorderIndex] = $fn;
            ++$leftBorderIndex;
            ++$rightBorderIndex;
        } elseif (ord($list[$rightBorderIndex]) < ord($list[$rightBorderIndex + 1])) {
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
while ($row = fgets($fdescr)) {
    $firstNames[] = sprintf("%-{$length}s", trim($row));
}

$firstIndex = 0;
$lastIndex = count($firstNames) - 1;
$borders = getBorders($firstNames, $firstIndex, $lastIndex);
$firstNames = $borders[0];
$leftBorderIndex = $borders[1];
$rightBorderIndex = $borders[2];
if ($leftBorderIndex - 1 != $firstIndex) {
    $indexQueue[] = [$firstIndex, $leftBorderIndex - 1];
}
if ($rightBorderIndex + 1 != $lastIndex) {
    $indexQueue[] = [$rightBorderIndex + 1, $lastIndex];
}

while ($indexQueue) {
    $borderIndexes = array_shift($indexQueue);
    $firstIndex = $borderIndexes[0];
    $lastIndex = $borderIndexes[1];
    $borders = getBorders($firstNames, $firstIndex, $lastIndex);
    $firstNames = $borders[0];
    $leftBorderIndex = $borders[1];
    $rightBorderIndex = $borders[2];
    var_dump($leftBorderIndex);
    var_dump($rightBorderIndex);
    die('123');
    if ($leftBorderIndex - 1 != $firstIndex) {
        $indexQueue[] = [$firstIndex, $leftBorderIndex - 1];
    }
    if ($rightBorderIndex + 1 != $lastIndex) {
        $indexQueue[] = [$rightBorderIndex + 1, $lastIndex];
    }
}
var_dump($firstNames);
die('asd');