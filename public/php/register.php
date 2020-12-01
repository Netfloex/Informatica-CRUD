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

if (check_unique("email", $email)) {
    if (check_unique("username", $username)) {
        register($email, $username, $password);
        header("Location: /u/$username");
    } else {
        goBack("username", "is al in gebruik.");
    }
} else {
    goBack("email",  "is al in gebruik.");
}