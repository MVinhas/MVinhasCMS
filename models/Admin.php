<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\AdminController as AdminController;
    
class Admin
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }
        

    public function getUser($username, $password)
    {
        $user = $this->db->select('users', '*', 'username = ?', $username);
        
        $password_verify = password_verify($password, $user[0]['password']);
        
        if ($password_verify) {
            return true;
        }
        return false;
    }
}
