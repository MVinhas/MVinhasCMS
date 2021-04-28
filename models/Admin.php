<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\AdminController as AdminController;
    
class Admin extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }
        

    public function getUser(string $username, string $password)
    {
        $data = array($username);
        $user = $this->db->select('users', 'username, email, password, role', 'username = ?', $data);
        $password_verify = password_verify($password, $user['password']);
        
        if ($password_verify) {
            return $user;
        }
        return false;
    }
}
