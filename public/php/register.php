<?php
include "../../database/utils.php";
header('Content-Type: application/json');
$db->checkIfSet([
    "username",
    "email",
    "password"
], false);

$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

if (!$db->check_exist("email", $email)) {
    if (!$db->check_exist("username", $username)) {
        if (isset($_COOKIE["PHPSESSID"])) { // Als je nog niet op een andere pagina bent geweest doe het niet toevoegen aan de database.
            $db->register($email, $username, $password);
            $account = $db->account_from("username", $username);
        }
        $db->logged_on($account);
    } else {
        $db->goBack("username", "is al in gebruik.");
    }
} else {
    $db->goBack("email",  "is al in gebruik.");
}