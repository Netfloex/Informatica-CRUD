<?php
$secrets = json_decode(file_get_contents("/var/www/Informatica-CRUD/database/credentials.json"));
$connection = new mysqli($secrets->host, $secrets->username, $secrets->passwd, $secrets->dbname);

if ($connection->connect_error) {
    die("Error connecting database: $connection->connect_error");
}



function goBack($error, $msg) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        $referer = preg_replace("/\?.*/", "", $referer);
        header("Location: $referer?error=$error&msg=$msg");
    } else {
        header("Location: /");
    }
    exit;
}

function checkIfSet(array $array) {
    foreach ($array as $i => $item) {
        if (!isset($_POST[$item]) || strlen($_POST[$item]) < 3) {
            goBack($item, "is te kort");
        }
    }
}
function account_from($type, $var) {
    global $connection;
    $sql = $connection->prepare("SELECT * FROM accounts WHERE $type=?");
    if ($sql == false) {
        die("<br>$type kan niet uit accounts worden gehaald: var is: $var");
    }
    $sql->bind_param("s", $var);
    $sql->execute();

    $result = $sql->get_result();
    return $result->fetch_assoc();
}
function check_exist($type, $var) {
    return account_from($type, $var) != null;
}
function register($email, $username, $password) {
    global $connection;
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $connection->prepare("INSERT INTO accounts (email, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $username, $password);
    $stmt->execute();
}

function user_page($user) {
    if (check_exist("username", $user)) {
        $page = json_decode('{
            "title": "' . $user . '",
            "doc": "profilepage"
        }');
    } else {
        $page = json_decode('{
            "title": "404, This user was not found.",
            "doc": "profilepage"
        }');
    }
    return $page;
}