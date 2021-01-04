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
    public function goBack($type, $msg) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            $referer = preg_replace("/\?.*/", "", $referer);
            header("Location: $referer?error=$type&msg=$msg");
        } else {
            header("Location: /");
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
        foreach ($array as $i => $item) {
            if (!isset($_POST[$item]) || (strlen($_POST[$item]) < 3 && !$ignore_length)) {
                $this->goBack($item, "is te kort");
            }
        }
    }

    /**
     * Haalt een account op van de database.
     * @param String $type email of username of id
     * @param String $var de variabele van boven 
     * @return $account;
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
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO accounts (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password);
        $stmt->execute();
    }

    /**
     * Maakt een user pagina als de user bestaat
     * @param String $user username
     * @return $page;
     */

    public function user_page($user) {
        if ($this->check_exist("username", $user)) {
            $page = json_decode('{
                "title": "' . $user . '",
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
            header("Location: /u/{$_SESSION['account']['username']}");
        }
    }
}

/**
 * Verstuurt een header om naar een andere pagina te gaan, exit daarna
 * @param String $url de url
 */

function redirect_to(String $url) {
    header("Location: $url");
    exit;
}