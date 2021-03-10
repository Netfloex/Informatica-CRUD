<?php
$pages = json_decode(file_get_contents(jPath("config/pages.json")));
$req = $_SERVER["REQUEST_URI"];
require jPath("database/utils.php");

if (strlen($req) > 1) {
    $req = preg_replace("/^\//", "", $req);
    $req = preg_replace("/\?.*/", "", $req);
}

if (preg_match("/^u\//", $req) == 1) { // Pagina die begint met "u/" -> Profiel Pagina
    $req = preg_replace("/^u\//", "", $req);
    $page = $db->user_page($req);
} else if (isset($pages->$req)) {
    $page = $pages->$req;
} else {
    die(header("HTTP/1.0 404 Not Found"));
    $page = $pages->notFound;
}


$title = $page->title;
$doc = isset($page->doc) ? $page->doc : $req;
$document = jPath("{$paths->views}/$doc.php");

include jPath("{$paths->views}/layout.php");

// Logging
$db->log_request($_SERVER);