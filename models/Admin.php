<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\AdminController as AdminController;
    
class Admin extends Model
{
    public function __construct()
    {
    }

    public function getUser(string $username, string $password)
    {
        $user = Query::select('users')
        ->fields('username', 'email', 'password', 'role')
        ->where(['username' => $username])
        ->done()
        ->one();

        $password_verify = password_verify($password, $user['password']);
        
        if ($password_verify) {
            return $user;
        }
        return false;
    }
}
