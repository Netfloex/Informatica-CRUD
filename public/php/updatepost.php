<?php

include "../../database/utils.php";
if (!isset($_SESSION["account"]["id"])) {
    $db->goBack();
}

$db->checkIfSet([
    "title",
    "content",
], false);
$db->checkIfSet([
    "id"

], true);

$title = $_POST["title"];
$content = $_POST["content"];
$id = $_POST["id"];
$db->edit_post($id, $title, $content);