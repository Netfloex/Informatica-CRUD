<?php

include "../../database/utils.php";
header('Content-Type: application/json');
echo json_encode($_POST);
$db->checkIfSet([
    "firstname",
    "lastname",
    "country",
    "address",
    "bio"
], true);
$db->checkIfSet([
    "username",
    "email",
], false);

$p = $_POST;
$db->update_profile($p);
$db->goBack();