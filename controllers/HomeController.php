<?php

namespace controllers;

use models\Home as Home;
use models\Site as Site;

class HomeController extends Controller
{

    protected $path;
    protected $model;
    protected $site;
    public function __construct()
    {
        parent::__construct();
        $file = pathinfo(__FILE__, PATHINFO_FILENAME);
        $this->path = $this->getDirectory($file);
        $this->model = new Home();
        $this->site = new Site();
    }

    public function index()
    {
        $offset = 0;
        if ($this->globals->get('page')) {
            $offset += ($page * 5);
            $out['page'] = $page;
        }
        
        $out['articles'] = $this->model->getArticles($offset);
        $out['about'] = $this->model->getAbout();
        $out['archives'] = $this->model->getArchives();
        $out['social'] = $this->model->getSocial();
        $home = $this->getFile($this->path, __FUNCTION__);
        $this->view($home, $out);
    }

    public function register()
    {
        $fields = 'email, username, password, role, active';
        $password = password_hash($this->globals->post('password'), PASSWORD_DEFAULT);
        $admin_exists = $this->model->checkAdmin();
        $admin_exists === 1 ? $role = 'user' : $role = 'admin'; 

        $values = array(
            $this->globals->post('email'), 
            $this->globals->post('username'), 
            $password, 
            $role, 
            1
        );
        $createUser = $this->model->createUser($fields, $values);
        $createUser === 1 ? $this->login($this->globals->post('email'), $role) : $this->setup($createUser);
    }

    public function login($email, $role)
    {
        $username = $this->globals->post('username');
        if (empty($this->globals->post('email')) && $username)
            $_SESSION['users'] = array(
                'email' => $email,
                'username' => $username,
                'role' => $role
            );
    }

    public function logout()
    {
        unset($_SESSION['users']);
    }

    public function search()
    {
        if (!$this->globals->post('search')) 
            return $this->index();
        $out = array();
        $out['categories'] = $this->site->getCategories();
        $out['about'] = $this->model->getAbout();
        $out['archives'] = $this->model->getArchives();
        $out['social'] = $this->model->getSocial();
        $search_terms = explode(" ", $this->globals->post('search'));
        $out['articles'] = $this->model->getArticlesBySearch($search_terms);
        if (!isset($out['articles'][0]) && !empty($out['articles'])) {
            $temp = $out['articles'];
            unset($out['articles']);
            $out['articles'][0] = $temp;
            $out['number_results'] = count($out['articles']);
        } elseif (!empty($out['articles'])) {
            $out['number_results'] = count($out['articles']);
        }
        $search = $this->getFile($this->path, __FUNCTION__);
        $this->view($search, $out); 
    }

    public function setup($message = '')
    {
        
        $out = array();
        $out['debug_mode'] = $this->config_flags->debug_mode;
        $out['message'] = $message;
        if ($this->model->checkUsers() != 1) {
            $out['first_account'] = 1;
        }
        $setup = $this->getFile($this->path, __FUNCTION__);
        $this->view($setup, $out);
    }

}
