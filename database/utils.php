<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION["account"]);

$db = new DB();




class DB {
    private $connection;
    function __construct() {
        $secrets = json_decode(file_get_contents("/var/www/Informatica-CRUD/database/credentials.json"));
        $this->connection = new mysqli($secrets->host, $secrets->username, $secrets->passwd, $secrets->dbname);
        if ($this->connection->connect_error) {
            die("Error connecting database: $this->connection->connect_error");
        }
    }
    /**
     * Ga terug naar de vorige pagina met een error bericht.
     * @param String $type Het type, dus email, username of password
     * @param String $msg Wat er mis is met 
     */
    public function goBack($type = false, $msg = false) {
        $referer = $_SERVER['HTTP_REFERER'];
        if (isset($referer)) {
            $referer = preg_replace("/\?.*/", "", $referer);
            if ($type) {
                $referer .= "?error=$type&msg=$msg";
            }
            die(header("Location: $referer"));
        } else {
            die(header("Location: /"));
        }
        exit;
    }

    /**
     * Bekijkt of alle items van de array die je meegeeft zijn meegegeven in de request
     * Als een item niet word meegegeven, ga dan terug.
     * ```php
     * $db->checkIfSet([
     *      "username",
     *      "email"
     * ]);
     * ```
     * @param array $array de array die word gechecked
     * @param bool $ignore_length of er ook voor de lengte van het item moet worden gechecked. 
     */

    public function checkIfSet(array $array, bool $ignore_length) {

        foreach ($array as $item) {
            if (!isset($_POST[$item]) || (strlen($_POST[$item]) < 3 && !$ignore_length)) {
                $this->goBack($item, "is te kort");
            }
        }
    }

    /**
     * Haalt een account op van de database.
     * @param String $type email of username of id
     * @param String $var de variabele van boven 
     * @return array $account;
     */

    public function account_from($type, $var) {
        $sql = $this->connection->prepare("SELECT * FROM accounts WHERE $type=?");
        if ($sql == false) {
            die("<br>$type kan niet uit accounts worden gehaald: var is: $var");
        }
        $sql->bind_param("s", $var);
        $sql->execute();

        $result = $sql->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Test of een account bestaat in de database
     * @param String $type email of username of id
     * @param String $var de variabele van boven 
     * @return bool;
     */

    public function check_exist($type, $var) {
        return $this->account_from($type, $var) != null;
    }

    /**
     * Registreert een account
     * @param String $email
     * @param String $username
     * @param String $password
     * 
     */
    public function register($email, $username, $password) {
        $email = htmlspecialchars($email);
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO accounts (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password);
        $stmt->execute();
    }

    /**
     * Verwijderd het account uit de database en logt dan uit;
     * @param array $account Het account dat word verwijderd
     */

    public function delete_account($account) {
        $stmt = $this->connection->prepare("DELETE FROM `accounts` WHERE `accounts`.`id` = ?");
        $stmt->bind_param("i", $account["id"]);
        $stmt->execute();
        redirect_to("/logout");
    }

    /**
     * Maakt een user pagina als de user bestaat
     * @param String $user username
     * @return array $page;
     */

    public function user_page($user) {
        $acc = $this->account_from("username", $user);
        if ($acc != null) {
            $page = json_decode('{
                "title": "' . $acc["username"] . '",
                "doc": "profilepage"
            }');
        } else {
            $page = json_decode('{
                "title": "404, This user was not found.",
                "doc": "404"
            }');
        }
        return $page;
    }

    /**
     * Wat er moet gebeuren na een inlog/registratie
     * @param account $account;
     */

    public function logged_on($account) {
        global $is_logged_in;
        $_SESSION["account"] = $account;
        $is_logged_in = true;
        $this->redirect_if_logged_in();
    }
    /**
     * Functie voor paginas waar je registreert of inlogt
     * Als je al bent ingelogt, redirect dan naar je eigen pagina
     */
    public function redirect_if_logged_in() {
        global $is_logged_in;
        if ($is_logged_in) {
            redirect_to("/u/{$_SESSION['account']['username']}");
        }
    }

    /**
     * Update je eigen profiel in de database
     * @param array $p Post data van de request
     */

    public function update_profile(array $p) {
        $p = array_map('htmlspecialchars', $p);
        $p["username"] = str_replace(" ", "_", $p["username"]);
        $insert_details = $this->connection->prepare("UPDATE accounts SET firstname=?, lastname=?, country=?, address=?, bio=?, username=?, email=? WHERE id=?");
        $insert_details->bind_param("sssssssi", $p["firstname"], $p["lastname"], $p["country"], $p["address"], $p["bio"], $p["username"], $p["email"], $_SESSION["account"]["id"]);

        $insert_details->execute();

        $account = $this->account_from("id", $_SESSION["account"]["id"]);
        $_SESSION["account"] = $account;
    }

    /**
     * Update je profielfoto in de database
     * @param String $base64 base64 encoded image
     */
    public function update_picture(String $base64 = null) {
        $base64 = htmlspecialchars($base64);
        $update_picture = $this->connection->prepare("UPDATE accounts SET profile_picture=? WHERE id=?");
        $update_picture->bind_param("si", $base64, $_SESSION["account"]["id"]);
        $update_picture->execute();
        $_SESSION["account"]["profile_picture"] = $base64;
    }

    /**
     * Haalt alle users uit de database
     * @param int $max Hoeveel
     * @return array Lijst van de users
     */
    public function all_users(int $max = 10) {
        $sql = $this->connection->prepare('SELECT * FROM `accounts` WHERE firstname != "" LIMIT ?');
        $sql->bind_param("i", $max);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all();
    }

    /**
     * Logt een request in de database
     * @param $_SERVER $req Object met data
     */
    public function log_request($req) {
        $url = "http://$req[HTTP_HOST]$req[REQUEST_URI]";
        $useragent = $req["HTTP_ACCEPT_LANGUAGE"];
        $ip = $req["HTTP_X_FORWARDED_FOR"] ?? $req["REMOTE_ADDR"];
        $referer = $req["HTTP_REFERER"] ?? "";
        $accept = $req["HTTP_ACCEPT"];
        $username = $_SESSION["account"]["username"] ?? "";
        $stmt = $this->connection->prepare("INSERT INTO requests (url, `user-agent`, ip, referer, accept, username) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $url, $useragent, $ip, $referer, $accept, $username);
        $stmt->execute();
    }

    /**
     * Bekijkt wanneer username voor het laatst online was
     * @param string $username Wie?
     * @return string Timestamp van laatst online
     */
    public function last_online(string $username) {
        $sql = $this->connection->prepare('SELECT `timestamp` FROM `requests` WHERE `username` = ? ORDER BY `id` DESC  LIMIT 1');
        $sql->bind_param("s", $username);
        $sql->execute();
        $result = $sql->get_result();
        $obj = $result->fetch_object();
        if (isset($obj)) {
            return $obj->timestamp;
        }
        return "Nog Niet?";
    }

    /**
     * Voegt een post toe van een user
     * @param int $id Wie?
     * @param string $title Titel van de post
     * @param string $content Content van de post
     */
    public function add_post(int $id, string $title, string $content) {
        $title = htmlspecialchars($title);
        $content = htmlspecialchars($content);
        $stmt = $this->connection->prepare("INSERT INTO posts (userid, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $title, $content);
        $stmt->execute();
        $this->goBack();
    }


    /**
     * Krijgt alle posts van een user
     * @param int $id Wie?
     * @return string Timestamp van laatst online
     */
    public function user_posts(int $userid, int $max = 10) {
        $sql = $this->connection->prepare('SELECT `title`, `content`, `timestamp`, `id` FROM `posts` WHERE `userid` = ? LIMIT ?');
        $sql->bind_param("ii", $userid, $max);
        $sql->execute();
        $result = $sql->get_result();
        return $result->fetch_all();
    }

    /**
     * Edit een post van een user
     * @param int $id Id van de post?
     * @param string $title Titel van de post
     * @param string $content Content van de post
     */
    public function edit_post(int $id, string $title, string $content) {
        $title = htmlspecialchars($title);
        $content = htmlspecialchars($content);
        $stmt = $this->connection->prepare("UPDATE `posts` SET `title` = ?, `content` = ? WHERE `posts`.`id` = ?;");
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        $this->goBack();
    }
}

/**
 * Verstuurt een header om naar een andere pagina te gaan, exit daarna
 * @param String $url de url
 */

function redirect_to(String $url) {
    die(header("Location: $url"));
    exit;
}

/**
 * Als $type momenteel een error geeft, geef dan error class terug
 * @param String $type email, username, image
 * @return String? "uk-form-danger" || null
 */

function errorClass($type) {
    if (isset($_GET["error"])) {
        if ($_GET["error"] == $type) {
            return "uk-form-danger";
        }
    }
}

/**
 * Als $type momenteel een error geeft, geef dan error bericht terug
 * @param String $type email, username, image
 * @return String? "error msg" || null
 */

function errorMsg($type) {
    if (isset($_GET["error"]) && isset($_GET["msg"])) {
        if ($_GET["error"] == $type) {
            return "<span class=\"uk-text-danger\">" . ucfirst($type) . " " . $_GET["msg"] . "</span>";
        }
    }
}