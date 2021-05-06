<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\SiteController as SiteController;

class Site extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }

    public function visitCounter()
    {
        $data = array(session_id());

        $visit = $this->db->select('sessions', 'id', 'session = ?', $data);

        if (empty($visit)) {
            $data = array(session_id(), date('Y-m-d'));
            $this->db->create('sessions', 'session, firstvisit', $data);
        }
    }

    public function getCategories()
    {
        $categories = $this->db->select('categories', '*');

        return $categories;
    }
    
    public function getCategory(int $id)
    {
        $data = array($id);
        $category = $this->db->select('categories', '*', 'id = ?', $data);

        return $category;
    }

    public function getArticle(int $id)
    {
        $data = array($id);
        $category = $this->db->select('articles', '*', 'id = ?', $data);

        return $category;
    }

    public function getConfig()
    {
        $config = $this->db->select('config', '*');

        return $config;
    }
}
