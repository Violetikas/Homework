<?php


$data = "file.txt";
$data1 = "store/output.txt";
$input = fopen($data, "rb");
$output = fopen($data1, "wb");
stream_set_blocking($output, false);
$reverse = false;
$buffer = "";
$period = 1.0;
$start = microtime(true);

while (!feof($input)) {
    $line = rtrim(fgets($input), "\n");
//    var_dump(ftell($input));
    $needle = 'ZZ.ZZ.ZZ';
    $lineLenght = strlen($line);
    $needleLength = strlen($needle);
    $findNeedle = strrpos($line, $needle);

    if ($reverse) {
        $line = strrev($line);
        $reverse = false;
    }

    if ($findNeedle !== false && $findNeedle != ($lineLenght - $needleLength - 1)) {
        $reverse = true;
    }

    $buffer = $buffer . $line . PHP_EOL;

    $remainingTime = max($period - (microtime(true) - $start), 0.0);
    stream_set_timeout($output, $remainingTime);
    $written = fwrite($output, $buffer);
    $buffer = substr($buffer, $written);

    $start += $period;
    time_sleep_until($start);
}

fclose($input);
fclose($output);