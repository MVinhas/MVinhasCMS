<?php

namespace controllers;

use \engine\SiteInfo;
use \config\Dispatcher;
use \models\Header as Header;
use \models\Footer as Footer;
use \models\Site as Site;
use \models\Home as Home;
use \controllers\CPanelController as CPanelController;

class SiteController extends Controller
{
    protected $path, $home, $model;

    public function __construct()
    {
        parent::__construct();
        $file = pathinfo(__FILE__, PATHINFO_FILENAME);
        $this->path = $this->getDirectory($file);
        $this->model = new Site();
    }
    public function index()
    {
        $get = $this->globals->get();
        try {
            $this->model->visitCounter();
        } catch (\Error $e) {
            return $this->migrations();
        }
        Dispatcher::metadata();
        $this->head();

        $getKeys = array_keys($get);
        if (!empty($getKeys[0])) {
            $key_method = substr($getKeys[0], strpos($getKeys[0], "/") + 1);
            $key_func = substr($getKeys[0], 0, strpos($getKeys[0], "/"));   
        }

        if (isset($key_method) && $key_method === 'createSession') 
            header('Location: ?CPanel/index');
        if (isset($key_method) && $key_method === 'logout') 
            header('Location: ?');
        
        if (isset($key_func) && $key_func === 'CPanel') {
            $cpanelController = new CPanelController;
            $cpanelController->header();
        } else {
            $this->header();    
        }
        Dispatcher::dispatch();
        $this->footer();   
    }

    private function head()
    {
        $out = array();
        $out['debug_mode'] = $this->config_flags->debugmode;
        $out['page_title'] = $this->globals->session('page_title'); 
        $headTemplate = $this->getFile($this->path, __FUNCTION__);
        $this->view($headTemplate, $out);
    }

    private function header()
    {
        $out = array();
        $siteInfo = new SiteInfo();
        $header = new Header();
        $out['sitename'] = $siteInfo->getName();
        $out['header'] = $header->getMenu();
        $out['categories'] = $this->model->getCategories();
        if (!empty($users = filter_var_array($_SESSION['users'])))
            $out['session'] = $users;
        $headerTemplate = $this->getFile($this->path, __FUNCTION__);
        $this->view($headerTemplate, $out);
    }

    protected function footer()
    {
        $out = array();
        $site = new SiteInfo();
        $out['copyleft'] = $site->getCopyright();
        $out['siteversion'] = $site->getVersion();
        $out['debug_mode'] = $this->config_flags->debugmode;
        $footerTemplate = $this->getFile($this->path, __FUNCTION__);
        $this->view($footerTemplate, $out);
    }
    public function terms()
    {
        $out = array();
        $out['site_name'] = $this->config_flags->sitename;
        $out['email'] = $this->config_flags->email;
        $termsTemplate = $this->getFile($this->path, __FUNCTION__);
        $this->view($termsTemplate, $out);
    }

    public function subscribe()
    {
        $subscribe = $this->getFile($this->path, __FUNCTION__);
        $this->view($subscribe);
    }

    private function migrations()
    {
        $migrations = new \migrations\Setup();
        $migrations->index();
        $home = $this->getFile($this->path, 'first_setup');
        $this->view($home);
    }
}
