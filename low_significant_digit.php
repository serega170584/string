<?php
$fdescr = fopen('low_significant_digit.txt', 'r');
$length = (int)fgets($fdescr);
$counts = [];
$firstNames = [];
$charIndex = $length - 1;
while ($charIndex >= 0) {
    while ($row = fgets($fdescr)) {
        $firstName = trim($row);
        $lastChar = $firstName[$charIndex];
        $sectionNumber = ord($lastChar);
        $count = $counts[$sectionNumber] ?? 0;
        $counts[$sectionNumber] = ++$count;
        $firstNameSections[$firstName] = $sectionNumber;
        $firstNames[] = $firstName;
    }
    ksort($counts);
    $sectionMapping = array_combine(array_keys($counts), range(1, count($counts)));
    $counts = array_merge([0], array_values($counts));
    $countsLength = count($counts);
    for ($i = 0; $i < $countsLength - 1; ++$i) {
        $counts[$i + 1] += $counts[$i];
    }
    $firstNameCount = count($firstNames);
    $res = [];
    for ($i = 0; $i < $firstNameCount; ++$i) {
        $sectionId = $sectionMapping[$firstNameSections[$firstNames[$i]]];
        $res[$counts[$sectionId - 1]++] = $firstNames[$i];
    }
    ksort($res);
}