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
        if (!$this->globals->get('CPanel/index')) 
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
        $getid = (int)$this->globals->get('id'); 
        if (!empty($getid)) {
            $out['article']['id'] = $getid;
            $out['article'] = $this->site->getArticle($getid);
        }
        $out['categories'] = $this->site->getCategories();
        $out['author'] = $this->globals->session(['users', 'username']); 
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
        $post = $this->globals->post();
        !empty($this->globals->get('id')) ? $this->model->editArticle($getid, $post) : $this->model->createArticle($this->globals->post());
        
        $cpanel = $this->getFile($this->path, 'articlesIndex');
        $out = array();
        $out['articles'] = $this->model->getArticles();
        $this->view($cpanel, $out);
    }

    public function categoryEditorSubmit()
    {
        $post = $this->globals->post();
        if (!empty($this->globals->get('id'))) {
            $category = array();
            $category['name'] = $post['name'];
            $this->model->editCategory($this->globals->get('id'), $category);
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
        $post = $this->globals->post();
        $this->model->editConfig($post);
        header('Location: ?CPanel/index');
    }

    public function articleDelete()
    {
        if ($this->globals->get('id'))
            $this->model->deleteArticle($getid);
        $cpanel = $this->getFile($this->path, 'articlesIndex');
        $out = array();
        $out['articles'] = $this->model->getArticles();
        $this->view($cpanel, $out);
    }

    public function categoryDelete()
    {
        if ($this->globals->get('id'))
            $this->model->deleteCategory($getid);
        $cpanel = $this->getFile($this->path, 'categoriesIndex');
        $out = array();
        $out['categories_list'] = $this->site->getCategories();
        $this->view($cpanel, $out);
    }
}