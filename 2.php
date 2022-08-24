<?php

function check($num) {
    if ($num < 123) return true;

    $array = str_split($num);
    $iteration = 1;
    for ($i=1; $i<count($array); $i++) {
        if ($array[$i] == $array[$i-1] + 1) {
            $iteration++;
        } else {
            if ($i+2 > count($array)) break;
            $iteration = 1;
        }
        if ($iteration == 3) break;
    }

    return $iteration < 3;
}

$sum = 0;

for ($n=1; $n<=10000; $n++) {
    if (check($n)) $sum += $n;
}

echo "Сумма: $sum";
