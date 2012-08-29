<?php


$files = glob('*.jpg');

foreach ($files as $idx => $name) {
    $dd = implode('-', str_split(substr($name, 0, 6), 2));
    if (!file_exists($dd)){
        mkdir($dd);
        echo "making dir " . $dd . "<br>";
    }
    echo " ----- rename ".$name." to " . $dd.DIRECTORY_SEPARATOR.$name . "<br>";
    rename($name, $dd.'/'.$name);
}


?>