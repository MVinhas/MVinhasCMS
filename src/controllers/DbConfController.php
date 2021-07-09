<?php

namespace controllers;

class DbConfController extends Controller
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
        $out = array();
        $out['DB_SERVER'] = DB_SERVER;
        $out['DB_USERNAME'] = DB_USERNAME;
        $out['DB_PASSWORD'] = DB_PASSWORD;
        $out['DB_DATABASE'] = DB_DATABASE;
        $setup = $this->getFile($this->path, __FUNCTION__);
        $this->view($setup, $out);
    }
}
