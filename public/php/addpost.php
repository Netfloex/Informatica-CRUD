<?php

include "../../database/utils.php";
if (!isset($_SESSION["account"]["id"])) {
    $db->goBack();
}

$db->checkIfSet([
    "title",
    "content"
], false);

$title = $_POST["title"];
$content = $_POST["content"];

$db->add_post($_SESSION["account"]["id"], $title, $content);