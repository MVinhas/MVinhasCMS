<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\HomeController as HomeController;
    
class Home
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }
        
    public function checkUsers()
    {
        $getUsers = $this->db->select('users');
        $tableExists = false;
            
        if ($getUsers === false) {
            $tableExists = $this->db->checkTable('users') ?? false;
        }

        if ($tableExists === true) {
            return false;
        } else {
            if (!empty($getUsers)) {
                return true;
            }
            return '-1';
        }
    }

    public function checkAdmin()
    {
        $admin_exists = $this->db->select('users','*','role = ?','admin');
        if (!empty($admin_exists))
            return '1';
        return '0';
    }

    public function createUser($table = 'users', $fields, $values)
    {
        $createUser = $this->db->create('users', $fields, $values);

        if ($createUser === true) {
            return "1";
        } else {
            return $createUser;
        }
    }

    public function getCategories()
    {
        $categories = $this->db->select('categories','*');
        return $categories;
    }

    public function getPosts($offset = '0')
    {
        $posts = $this->db->select('posts','*', 'status = ? ORDER BY id DESC LIMIT 5 OFFSET '.$offset,'1');
        foreach ($posts as $k => $v) {
            $category = $this->db->select('categories','*','id = ?',$v['category']);
            $posts[$k]['category_name'] = $category[0]['name'];
        }
        return $posts;
    }

    public function getPost($id)
    {
        $post = $this->db->select('posts', '*', 'id = ?', "$id");

        return $post;
    }

    public function getAbout()
    {   
        $about = $this->db->select('about','*','id = ?','1');
        return $about;
    }

    public function getArchives()
    {
        $archives = $this->db->select('posts', 'COUNT(*) AS Total, DATE_FORMAT(date, "%M %Y") AS date, DATE_FORMAT(date, "%m") as month, DATE_FORMAT(date, "%Y") as year ', '1= ? GROUP BY DATE_FORMAT(date, "%M %Y"), DATE_FORMAT(date, "%m"), DATE_FORMAT(date, "%Y")','1');
        return $archives;
    }

    public function getSocial()
    {
        $social = $this->db->select('social', '*', 'visible = ?','1');
        return $social;
    }

    public function getPostsBySearch($search)
    {
        $sql = '';
        $field = '';
        foreach ($search as $k => $v) {
            if ($v !== "") {
                $sql .= " AND title LIKE CONCAT('%',?,'%') ";
                $field .= $v.", ";
            }
        }
        $field = rtrim($field, ',');
        $posts = $this->db->select('posts', '*', '1= ?'.$sql, "1,".$field);
        return $posts;
    }
}
