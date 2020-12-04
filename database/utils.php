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
    public function goBack($error, $msg) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            $referer = preg_replace("/\?.*/", "", $referer);
            header("Location: $referer?error=$error&msg=$msg");
        } else {
            header("Location: /");
        }
        exit;
    }
    /*
     * Bekijkt of alle items van de array die je meegeeft zijn meegegeven in de request
     * @param $array
     */
    public function checkIfSet(array $array, bool $ignore_length) {
        foreach ($array as $i => $item) {
            if (!isset($_POST[$item]) || (strlen($_POST[$item]) < 3 && !$ignore_length)) {
                $this->goBack($item, "is te kort");
            }
        }
    }
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
    public function check_exist($type, $var) {
        return $this->account_from($type, $var) != null;
    }
    public function register($email, $username, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO accounts (email, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $username, $password);
        $stmt->execute();
    }

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

    public function logged_on($account) {
        global $is_logged_in;
        $_SESSION["account"] = $account;
        $is_logged_in = true;
        $this->redirect_if_logged_in();
    }

    public function redirect_if_logged_in() {
        global $is_logged_in;
        if ($is_logged_in) {
            header("Location: /u/{$_SESSION['account']['username']}");
        }
    }
}