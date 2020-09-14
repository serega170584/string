<?php

function dfa($str)
{
    $length = strlen($str);
    $chars = [];
    for ($i = 0; $i < $length; ++$i) {
        $chars[$str[$i]] = $str[$i];
    }
    $states = [];
    $prefixState = 0;
    for ($i = 0; $i < $length; ++$i) {
        foreach ($chars as $char) {
            $states[$i][$char] = 0;
            $states[$i][$char] = $states[$prefixState][$char];
        }
        $prefixState = $states[$prefixState][$str[$i]];
        $states[$i][$str[$i]] = $i + 1;
    }
    return $states;
}

function searchString($search, $str)
{
    $dfa = dfa($search);
    $dfaLength = count($dfa);
    $length = strlen($str);
    $state = 0;
    $searchIndex = false;
    for ($i = 0; $i < $length; ++$i) {
        $state = $dfa[$state][$str[$i]];
        if ($state == $dfaLength) {
            $searchIndex = $i - $dfaLength + 1;
            break;
        }
    }
    return $searchIndex;
}

var_dump(searchString('ABABAC', 'BAAABABABB'));