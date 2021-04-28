<?php
    chdir('../..');
    require_once 'config/conf.php';

    use \Engine\DbOperations as DbOperations;
    use \Engine\Superglobals as Superglobals;

class CheckLogin
{
        
    protected $db;
    public $globals;
    public function __construct()
    {
        $this->db = new DbOperations;
        $this->globals = new Superglobals();
    }


    public function username($username)
    {
        $data = array($username); 
        $getUsers = $this->db->select('users', 'username', "username = ?", $data);
        return $getUsers;
    }

    public function password($username)
    {
        $data = array($username); 
        $getUsers = $this->db->select('users', 'password', "username = ?", $data);
 
        return $getUsers;
    }
}

$check = new CheckLogin;
if ($check->globals->post('username')) { 
    $username_exists = $check->username($check->globals->post('username'));
    if (!empty($username_exists)) {
        if (in_array($check->globals->post('username'), $username_exists)) {
            $exists = 1;
            ob_clean();
            print_r('true');
        }
    }
    if (!isset($exists)) {
        ob_clean();
        print_r('false');
    }
}

if ($check->globals->post('password')) {
    $userpass = explode('||', $check->globals->post('password'));
    $username_exists = $check->password($userpass[0]);
    if (!empty($username_exists)) {
        $password = password_verify($userpass[1], $username_exists['password']);
        if ($password) {
            $exists = 1;
            ob_clean();
            print_r('true');
        }
    }
    if (!isset($exists)) {
        ob_clean();
        print_r('false');
    }
    
}
