<?php

include "../../database/utils.php";
if (!isset($_SESSION["account"]["id"])) {
    $db->goBack("image", "Niet ingelogd");
}

if (isset($_FILES["profilepicture"])) {
    $uploadOk = true;
    $file = $_FILES["profilepicture"];

    $check = getimagesize($file["tmp_name"]);
    if (!$check) {
        $uploadOk = false;
    }

    if ($file["size"] > 1000000) {
        $uploadOk = false;
    }

    if ($uploadOk) {
        $path = $file["tmp_name"];

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $db->update_picture($base64);
    } else {
        $db->goBack("image", "Das net iets te groot voor mij");
    }
} else {
    $db->goBack("image", "Volgensmij heb ik niks gekregen?");
}

$db->goBack();