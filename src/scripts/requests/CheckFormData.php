<?php
    chdir('../..');
    require_once 'config/conf.php';

    use \engine\DbOperations as DbOperations;

class CheckFormData
{
        
    protected $db;
    public function __construct()
    {
        
        $this->db = new DbOperations;
    }
        
    public function email($email)
    {
        $data = array($email);
        $getUsers = $this->db->select('users', 'email', "email = ?", $data);
        return $getUsers;
    }

    public function username($username)
    {
        $data = array($username); 
        $getUsers = $this->db->select('users', 'username', "username = ?", $data);
 
        return $getUsers;
    }
}

    $check = new CheckFormData;
if (null !== ($email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
    $email_exists = $check->email($email);
    if (!empty($email_exists)) {
        foreach ($email_exists as $k => $v) {
            if (in_array($email, $v)) {
                $exists = 1;
                print_r('false');
            }
        }
    }
    if (!isset($exists)) {
        print_r('true');
    }
}

if (null !== ($username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING))) {
    //$username = preg_replace('/[^\w]/','',$username);
    $username_exists = $check->username($username);
    if (!empty($username_exists)) {
        foreach ($username_exists as $k => $v) {
            if (in_array($username, $v)) {
                $exists = 1;
                print_r('false');
            }
        }
    }
    if (!isset($exists)) {
        print_r('true');
    }
}
