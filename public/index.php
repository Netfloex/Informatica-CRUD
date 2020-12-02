<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
session_start();
jsDump($_SESSION);
function jsDump($var) {
    echo "<script>console.log(" . json_encode($var) . ")</script>";
}

function jPath($str) {
    return realpath(getcwd() . "/../" . $str);
}
function errorClass($type) {
    if (isset($_GET["error"])) {
        if ($_GET["error"] == $type) {
            return "uk-form-danger";
        }
    }
}
function errorMsg($type) {
    if (isset($_GET["error"]) || isset($_GET["msg"])) {
        if ($_GET["error"] == $type) {
            return "<span class=\"uk-text-danger\">" . ucfirst($type) . " " . $_GET["msg"] . "</span>";
        }
    }
}
$paths = json_decode(file_get_contents(jPath("config/paths.json")));
include jPath("bin/routing.php");