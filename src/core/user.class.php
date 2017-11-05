<?php
/**
 * USER CLASS
 * ==========
 * 
 * @author leonovich.pavel@gmail.com
 * @version 1.0.0
 * 
 */

class User
{
    
    protected $table = "site_users";
    protected $history_table = "auth_history";

	public $onAdmin = false;
	public $onManager = false;
    public $onModerator = false;
    
    protected $conn; 
    protected $sessionname;
    
    public $id = 0;
    public $name = "Guest";
    public $info = array();
    public $group;
    public $ip;
    
    function __construct(SafeMySQL $db)
    {
        if(session_id() == '') {
            session_start();
        }
        $this->conn = $db;
        $this->sessionname = "__".Config::APPID;
        $this->init_userip();
        $this->isAuth();
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
        if (!isset($_SESSION[$this->sessionname])) {
            return false;
        }
        $query = " SELECT * FROM ?n WHERE secret=?s ";
        $result = $this->conn->getRow($query, $this->table, $_SESSION[$this->sessionname]);
        if ($result) {
            $this->id = $result['user_id'];
            $this->name = $result['user_name'];
            $this->info = $result;
            $this->onAdmin = $result['admin'];
            $this->onManager = $result['manager'];
            $this->onModerator = $result['moderator'];
        } else {
            return false;
        }
    }

    public function __get ($name) {
        if(isset($this->info[$name])) return $this->info[$name];
        else return NULL;
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
        if (!isset($_SESSION[$this->sessionname])) {
            return false;
        }
        $result = $this->conn->getOne(" SELECT COUNT(*) FROM ?n WHERE secret=?s ", $this->table, $_SESSION[$this->sessionname]);
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
        try {
            $result = $this->conn->getOne(" SELECT COUNT(*) FROM ?n WHERE user_login=?s AND secret=?s ", $this->table, $login, $hash);
        } catch (Exception $e) {
            LOG::writeException($e);
        }
        if ($result) {
            //setcookie($this->sessionname, $hash, time()+86400, '/');
            $_SESSION[$this->sessionname] = $hash;
            $this->setAuthHistory();
            $this->init_user();
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
        if (isset($_SESSION[$this->sessionname])) {
            //setcookie($this->sessionname, "", time()-3600, '/');
            unset($_SESSION[$this->sessionname]);
            session_destroy();
        } else {
            return true;
        }
    }
    
    /**
     * Save auth history to db
     *
     * @return boolean
     */
    private function setAuthHistory()
    {
        $query = "INSERT INTO ?n (user_id, user_ip, login_date, login_time) VALUES (?s, ?s, CURDATE(), CURTIME())";
        $this->conn->query($query, $this->history_table, $this->id, $this->ip);
    }
    
    /**
     * Get auth history from db
     *
     * @return boolean
     */
    public function getAuthHistory()
    {
        return $this->conn->getAll("SELECT user_name,user_login,user_ip,login_date,login_time FROM ?n LEFT JOIN ?n USING(user_id) ORDER BY login_date,login_time", $this->history_table, $this->table);
    }
    
    /**
     * Check if user is isset
     *
     * @return boolean
     */
    public function _isset( $name, $login, $email )
    {
        return $this->conn->getOne(" SELECT COUNT(user_name) FROM ?n WHERE user_name = ?s OR user_login = ?s OR email = ?s ", $this->table, $name, $login, $email);
    }
    
    /**
     * Generate hash code
     *
     * @return string - hash code
     */
     final public function genHash($login, $password)
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
        $res = false;
        try {
            $res = $this->conn->query($query, $this->table, $user_name, $login, $email, $secret);
        } catch (Exception $e) {
            LOG::writeException($e);
        }
        return $res;
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
        $result = $this->conn->query($query, $this->table);
        $query = "CREATE TABLE IF NOT EXISTS ?n (
            id INT(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
            user_id INT(11) NOT NULL DEFAULT 0,
            user_ip VARCHAR(255) NULL,
            login_date DATE NOT NULL,
            login_time TIME NOT NULL 
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
        if($result) return $this->conn->query($query, $this->history_table);
    }
}