<?php
/**
 * USER CLASS 1.0.0
 */

class USER
{
    
    const TABLE = "data_userlist";
    const HISTORY_TABLE = "data_userhistory";

    protected $conn;    
    protected $cookiename;
    protected $onManager = false;
    protected $onModerator = false;
    
    public $id = 0;
    public $name = "Guest";
    public $info = array();
    public $group;
    public $ip;
    
    function __construct(SafeMySQL $db)
    {
        $this->conn = $db;
        $this->cookiename = "__".Config::APPID;
        $this->init_userip();
    }
    
    /**
     * Get user ip
     *
     * @return string - user ip
     */
    private function init_userip()
    {
        $ip = getenv('HTTP_CLIENT_IP') or $ip = getenv('HTTP_X_FORWARDED_FOR') or $ip = getenv('REMOTE_ADDR');
        $this->ip = $ip;
    }
    
    /**
     * User initialisation
     *
     * @return boolean
     */
    public function init_user()
    {
        if (!isset($_COOKIE[$this->cookiename])) {
            return false;
        }
        $query = " SELECT * FROM ?n WHERE secret=?s ";
        $result = $this->conn->getRow($query, self::TABLE, $_COOKIE[$this->cookiename]);
        if ($result) {
            $this->id = $result['id'];
            $this->name = $result['user_name'];
            $this->info = $result;
            $this->onManager = $result['manager'];
            $this->onModerator = $result['moderator'];
        } else {
            return false;
        }
    }
    
    /**
     * User access
     */
    public function access()
    {
        return true;
    }

    /**
     * Check if user is authorised
     *
     * @return boolean
     */
    public function isAuth()
    {
        if (!isset($_COOKIE[$this->cookiename])) {
            return false;
        }
        $result = $this->conn->getOne(" SELECT COUNT(*) FROM ?n WHERE secret=?s ", self::TABLE, $_COOKIE[$this->cookiename]);
        if ($result) {
            $this->init_user();
        }
        return $result;
    }
    
    /**
     * Try to get authorised
     *
     * @return boolean
     */
    public function auth($login, $password)
    {
        $login = self::clean($login);
        $password = self::clean($password);
        $hash = $this->genHash($login, $password);   //echo "hash: ".$hash;
        $result = $this->conn->getOne(" SELECT COUNT(*) FROM ?n WHERE user_login=?s AND secret=?s ", self::TABLE, $login, $hash);
        if ($result) {
            setcookie($this->cookiename, $hash, time()+86400, '/');
            $this->set_auth_history();
        }
        return $result;
    }

    /**
     * Clean user input
     *
     * @return string
     */
    private static function clean($string)
    {
        $string = trim($string);
        $string = htmlspecialchars($string);
        return $string;
    }

    /**
     * Loging out
     *
     * @return boolean
     */
    public function logout()
    {
        if (isset($_COOKIE[$this->cookiename])) {
            setcookie($this->cookiename, "", time()-3600, '/');
        } else {
            return true;
        }
    }
    
    /**
     * Save auth history to db
     *
     * @return boolean
     */
    private function set_auth_history()
    {
        $query = "INSERT INTO ?n (user_id, user_ip, login_date, login_time) VALUES (?s, ?s, CURDATE(), CURTIME())";
        $this->conn->query($query, self::HISTORY_TABLE, $this->id, $this->ip);
    }
    
    /**
     * Get auth history from db
     *
     * @return boolean
     */
    public function get_auth_history()
    {
        return $this->conn->getAll("SELECT * FROM ?n ORDER BY login_date,login_time", self::HISTORY_TABLE);
    }
    
    /**
     * Generate hash code
     *
     * @return string - hash code
     */
    private function genHash($login, $password)
    {
        $hash = md5(md5(trim($login)));
        $hash.= md5(md5(trim($password)));
        $hash.= md5(md5("Here comes secret frase"));
        return $hash;
    }

    /**
     * Add new user
     *
     * @return string - hash code
     */
    final public function addUser($user_name, $login, $password, $email)
    {
        $secret = $this->genHash($login, $password);
        $query = "INSERT INTO ?n (user_name, user_login, email, secret) VALUES (?s, ?s, ?s, ?s)";
        $this->conn->query($query, self::TABLE, $user_name, $login, $email, $secret);
    }

    /**
     * Create db tables
     *
     * @return string - hash code
     */
    final public function setup()
    {
        $query = " CREATE TABLE IF NOT EXISTS ?n (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_name VARCHAR(255) NOT NULL,
            user_login VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            secret TEXT NULL,
            authorisation INT(1) NOT NULL DEFAULT 0,
            content INT(1) NOT NULL DEFAULT 0,
            plugins INT(1) NOT NULL DEFAULT 0,
            contacts INT(1) NOT NULL DEFAULT 0,
            sistem_options INT(1) NOT NULL DEFAULT 0,
            manager INT(1) NOT NULL DEFAULT 0,
            moderator INT(1) NOT NULL DEFAULT 0
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
        $result = $this->conn->query($query, self::TABLE);
        $query = "CREATE TABLE IF NOT EXISTS ?n (
            id INT(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
            user_id INT(11) NOT NULL DEFAULT 0,
            user_ip VARCHAR(255) NULL,
            login_date DATE NOT NULL,
            login_time TIME NOT NULL 
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
        if($result) return $this->conn->query($query, self::HISTORY_TABLE);
    }
}