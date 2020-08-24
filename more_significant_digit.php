<?php
$fdescr = fopen('more_significant_digit.txt', 'r');
$length = (int)fgets($fdescr);
$charIndex = 0;
$sections = [];
$firstNames = [];
while ($row = fgets($fdescr)) {
    $firstName = sprintf("%-{$length}s", trim($row));
    $char = $firstName[$charIndex];
    $sectionId = ord($char);
    $sections[$firstName] = $sectionId;
    $firstNames[] = $firstName;
}
$counts = [];
foreach ($sections as $firstName => $sectionId) {
    $count = $counts[$sectionId] ?? 0;
    $counts[$sectionId] = ++$count;
}
ksort($counts);
$countsLength = count($counts);
$orderedSections = range(1, $countsLength);
$sectionsMapping = array_combine(array_keys($counts), array_keys($orderedSections));
$counts = array_combine($orderedSections, array_values($counts));
$counts[0] = 0;
for ($i = 0; $i < $countsLength; ++$i) {
    $counts[$i + 1] += $counts[$i];
}
$res = [];
ksort($counts);
$preCounts = $counts;
array_pop($counts);
foreach ($sections as $firstName => $sectionId) {
    $orderedSectionId = $sectionsMapping[$sectionId];
    $index = $counts[$orderedSectionId]++;
    $res[$index] = $firstName;
}
ksort($res);
$firstNames = $res;
++$charIndex;