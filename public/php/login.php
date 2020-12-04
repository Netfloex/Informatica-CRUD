<?php
include "../../database/utils.php";
header('Content-Type: application/json');

$db->checkIfSet([
    "email",
    "password"
], false);
$email = $_POST["email"];

$password = $_POST["password"];
if ($db->check_exist("email", $email)) {
    $account = $db->account_from("email", $email);
    if (password_verify($password, $account["password"])) {
        $db->logged_on($account);
    } else {
        $db->goBack("password", "is incorrect");
    }
} else {
    $db->goBack("email", "is incorrect");
}