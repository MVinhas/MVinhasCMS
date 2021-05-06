<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\AdminController as AdminController;
    
class Admin extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new Query;
    }
        

    public function getUser(string $username, string $password)
    {
        $data = array($username);
        $user = $this->db::select('users')->fields('username', 'email', 'password', 'role')->where(['username' => $username])->done()->one();
        $password_verify = password_verify($password, $user['password']);
        
        if ($password_verify) {
            return $user;
        }
        return false;
    }
}
