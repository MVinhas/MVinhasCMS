<?php

namespace Engine;

class Superglobals
{
    private $_post;
    private $_get;
    private $_files;
    private $_session;
    private $_server;

    public function __construct()
    {
        $this->_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $this->_get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $this->_files = filter_var_array($_FILES, FILTER_SANITIZE_STRING);
        if (isset($_SESSION))
            $this->_session = filter_var_array($_SESSION, FILTER_SANITIZE_STRING);
        $this->_server = filter_var_array($_SERVER, FILTER_SANITIZE_STRING);
    }

    public function post($key = null, $default = null)
    {
        return $this->checkGlobal($this->_post, $key, $default);
    }

    public function get($key = null, $default = null)
    {
        return $this->checkGlobal($this->_get, $key, $default);
    }

    public function files($key = null, $default = null)
    {
        return $this->checkGlobal($this->_files, $key, $default);
    }

    public function session($key = null, $default = null)
    {
        if (isset($this->_session))
            return $this->checkGlobal($this->_session, $key, $default);
        return false;
    }

    public function server($key = null, $default = null)
    {
        return $this->checkGlobal($this->_server, $key, $default);
    }

    private function checkGlobal($global, $key = null, $default = null)
    {
        if ($key) {
            if (isset($global[$key]))
                return $global[$key];
            else
                return $default ?: null;
        }
        return $global;
    }
}