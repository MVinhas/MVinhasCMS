<?php

namespace controllers;

use \engine\SiteInfo;
use \models\CPanel as CPanel;
use \models\Site as Site;

class CPanelController extends Controller
{
    protected $path;
    protected $model;
    protected $site;
    
    public function __construct()
    {
        parent::__construct();
        $file = pathinfo(__FILE__, PATHINFO_FILENAME);
        $this->path = $this->getDirectory($file);
        $this->model = new CPanel();
        $this->site = new Site();
    }

    public function index()
    {
        $out['sessions'] = array();
        $out['sessions']['today'] = 0;
        $out['sessions']['week'] = 0;
        $out['sessions']['alltime'] = 0;
        $visits = $this->model->getVisits();
        foreach ($visits as $visit) {
            // Today
            if ($visit['date'] == date('Y-m-d 00:00:00')) 
                $out['sessions']['today'] = $visit['session']; 
            // Week
            if ($visit['date'] <= date('Y-m-d 00:00:00') && $visit['date'] >= date('Y-m-d 00:00:00', strtotime('-7 days'))) 
                $out['sessions']['week'] += $visit['session'];
            // All time
            $out['sessions']['alltime'] += $visit['session'];
        }
        $cpanel = $this->getFile($this->path, __FUNCTION__);
        $this->view($cpanel, $out);
    }

    public function header()
    {
        $header = $this->getFile($this->path, __FUNCTION__);
        $siteInfo = new SiteInfo();
        $out = array();
        if (null === filter_input(INPUT_GET, 'CPanel/index', FILTER_SANITIZE_STRING))
            $out['searchable'] = 1;

        $out['sitename'] = $siteInfo->getName();
        $this->view($header, $out);
    }

    public function footer()
    {
        $footer = $this->getFile($this->path, __FUNCTION__);
        $out = array();
        $out['debugmode'] = $this->config_flags->debugmode;
        $this->view($footer, $out);
    }

    public function articlesIndex()
    {
        $cpanel = $this->getFile($this->path, __FUNCTION__);
        $out = array();
        $out['articles'] = $this->model->getArticles();
        $this->view($cpanel, $out);
    }

    public function categoriesIndex()
    {
        $cpanel = $this->getFile($this->path, __FUNCTION__);
        $out = array();
        $out['categories'] = $this->site->getCategories();
        $this->view($cpanel, $out);
    }

    public function configEditor()
    {
        $cpanel = $this->getFile($this->path, __FUNCTION__);
        $out = array();
        $out['config'] = $this->site->getConfig();
        $this->view($cpanel, $out);
    }

    public function articleEditor()
    {
        $articleCreate = $this->getFile($this->path, __FUNCTION__);
        $out = array();
        $getid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!empty($getid)) {
            $out['post']['id'] = $getid;
            $out['post'] = $this->site->getArticle($getid);
        }
        $out['categories'] = $this->site->getCategories();
        $out['author'] = filter_var($_SESSION['users']['username'], FILTER_SANITIZE_STRING);
        $out['debugmode'] = $this->config_flags->debugmode;
        $this->view($articleCreate, $out); 
    }

    public function categoryEditor()
    {
        $categoryCreate = $this->getFile($this->path, __FUNCTION__);
        $out = array();
        if (!empty($getid)) {
            $out['category_id'] = $getid;
            $out['category'] = $this->site->getCategory($getid);
        }
        $out['debugmode'] = $this->config_flags->debugmode;
        $this->view($categoryCreate, $out); 
    }

    public function articleEditorSubmit()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        !empty($getid) ? $this->model->editPost($getid, $post) : $this->model->createPost($post);
        
        $cpanel = $this->getFile($this->path, 'postsIndex');
        $out = array();
        $out['articles'] = $this->model->getPosts();
        $this->view($cpanel, $out);
    }

    public function categoryEditorSubmit()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (!empty($getid)) {
            $category = array();
            $category['name'] = $post['name'];
            $this->model->editCategory($getid, $category);
        } else {
            $this->model->createCategory($post);
        }
        
        $cpanel = $this->getFile($this->path, 'categoriesIndex');
        $out = array();
        $out['categories'] = $this->site->getCategories();
        $this->view($cpanel, $out);
    }

    public function configEditorSubmit()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $this->model->editConfig($post);
        header('Location: ?CPanel/index');
    }

    public function articleDelete()
    {
        if (null !== ($getid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)))
            $this->model->deleteArticle($getid);
        $cpanel = $this->getFile($this->path, 'articlesIndex');
        $out = array();
        $out['articles'] = $this->model->getArticles();
        $this->view($cpanel, $out);
    }

    public function categoryDelete()
    {
        if (null !== ($getid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)))
            $this->model->deleteCategory($getid);
        $cpanel = $this->getFile($this->path, 'categoriesIndex');
        $out = array();
        $out['categories_list'] = $this->site->getCategories();
        $this->view($cpanel, $out);
    }
}