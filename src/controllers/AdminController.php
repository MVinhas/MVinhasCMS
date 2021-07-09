<?php

namespace controllers;

use \models\Admin as Admin;
use \engine\Superglobals as Superglobals;

class AdminController extends Controller
{
    protected $path;
    
    public function __construct()
    {
        parent::__construct();
        $file = pathinfo(__FILE__, PATHINFO_FILENAME);
        $this->path = $this->getDirectory($file);
    }

    public function index()
    {
        if (!$this->globals->session('user'))
            $this->login();
    }
    
    public function login()
    {
        $out = array();
        $out['debug_mode'] = $this->config_flags->debug_mode;
        $loginView = $this->getFile($this->path, __FUNCTION__);
        $this->view($loginView, $out);
    }

    public function createSession()
    {
        $admin = new Admin();
        $user = $admin->getUser(
            $this->globals->post('username'),
            $this->globals->post('password')
        );
        if (is_array($user)) {
            $home = new HomeController;
            $home->login($user['email'], $user['role']);
        }
    }
}
