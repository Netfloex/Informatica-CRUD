<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();
function jsDump($var) {
    echo "<script>console.log(" . json_encode($var) . ")</script>";
}

function jPath($str) {
    return realpath(getcwd() . "/../" . $str);
}

$paths = json_decode(file_get_contents(jPath("config/paths.json")));
include jPath("bin/routing.php");