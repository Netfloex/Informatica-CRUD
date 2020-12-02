<?php
include "../../database/utils.php";
header('Content-Type: application/json');
checkIfSet([
    "username",
    "email",
    "password"
]);

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

if (!check_exist("email", $email)) {
    if (!check_exist("username", $username)) {
        register($email, $username, $password);
        $account = account_from("username", $username);
        logged_on($account);
    } else {
        goBack("username", "is al in gebruik.");
    }
} else {
    goBack("email",  "is al in gebruik.");
}