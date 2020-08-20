<?php
$fdescr = fopen('count_method.txt', 'r');
$counts = [0];
$firstNames = [];
while ($row = fgets($fdescr)) {
    $parts = explode(' ', $row);
    $firstName = trim($parts[0]);
    $sectionNumber = (int)$parts[1];
    $count = $counts[$sectionNumber] ?? 0;
    $counts[$sectionNumber] = ++$count;
    $firstNameSections[$firstName] = $sectionNumber;
    $firstNames[] = $firstName;
}
$countsLength = count($counts);
for ($i = 0; $i < $countsLength - 1; ++$i) {
    $counts[$i + 1] += $counts[$i];
}
$firstNameCount = count($firstNames);
$res = [];
for ($i = 0; $i < $firstNameCount; ++$i) {
    $res[$counts[$firstNameSections[$firstNames[$i]] - 1]++] = $firstNames[$i];
}
var_dump($res);
die('asd');