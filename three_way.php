<?php
$fdescr = fopen('three_way.txt', 'r');
$length = (int)fgets($fdescr);
$firstNames = [];
while ($row = fgets($fdescr)) {
    $firstName = sprintf("%-{$length}s", trim($row));
    $firstNames[] = $firstName;
}
$code = ord($firstNames[0][0]);
$firstNamesCount = count($firstNames);
$leftIndex = 0;
$rightIndex = $firstNamesCount;
while (true) {
    ++$leftIndex;
    $leftCode = ord($firstNames[$leftIndex][0]);
    while ($leftCode < $code) {
        ++$leftIndex;
        if ($leftIndex == $firstNamesCount) {
            break;
        }
        $leftCode = ord($firstNames[$leftIndex][0]);
    }
    --$rightIndex;
    $rightCode = ord($firstNames[$rightIndex][0]);
    while ($rightCode > $code) {
        --$rightIndex;
        if ($rightIndex == 0) {
            break;
        }
        $rightCode = ord($firstNames[$rightIndex][0]);
    }
    if ($rightIndex <= $leftIndex) {
        break;
    }
    $leftFirstName = $firstNames[$leftIndex];
    $rightFirstName = $firstNames[$rightIndex];
    $firstNames[$leftIndex] = $rightFirstName;
    $firstNames[$rightIndex] = $leftFirstName;
}
$firstName = $firstNames[0];
$rightFirstName = $firstNames[$rightIndex];
$firstNames[0] = $rightFirstName;
$firstNames[$rightIndex] = $firstName;
var_dump($firstNames);
die('asd');