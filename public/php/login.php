<?php
include "../../database/utils.php";
header('Content-Type: application/json');

checkIfSet([
    "email",
    "password"
]);
$email = $_POST["email"];

$password = $_POST["password"];
if (check_exist("email", $email)) {
    $account = account_from("email", $email);
    if (password_verify($password, $account["password"])) {
        logged_on($account);
    } else {
        goBack("password", "is incorrect");
    }
} else {
    goBack("email", "is incorrect");
}