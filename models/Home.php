<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\HomeController as HomeController;
    
class Home extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }
        
    public function checkUsers()
    {
        $getUsers = $this->db->select('users');
  
        if (is_array($getUsers)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkAdmin()
    {
        $data = array('admin');
        $admin_exists = $this->db->select('users','id','role = ?', $data);
        if (!empty($admin_exists))
            return 1;
        return 0;
    }

    public function createUser(string $fields, array $values)
    {
        $createUser = $this->db->create('users', $fields, $values);

        if ($createUser === true) {
            return 1;
        } else {
            return $createUser;
        }
    }

    public function getArticles(int $offset = 0)
    {
        $data = array(1);
        $articles = $this->db->select('articles','*', 'status = ? ORDER BY id DESC LIMIT 5 OFFSET '.$offset, $data);
        foreach ($articles as $k => $v) {
            $data = array($v['category']);
            $category = $this->db->select('categories','*','id = ? ORDER BY id ASC', $data);
            !empty($category) ?? $articles[$k]['category_name'] = $category['name'] :: $articles[$k]['category_name'] = 'No Category';
        }
        return $articles;
    }

    public function getAbout()
    {   
        $data = array(1);
        $about = $this->db->select('about','*','id = ?', $data);
        return $about;
    }

    public function getArchives()
    {
        $data = array(1);
        $rows = $this->db->select('articles', 'COUNT(*) AS Total, DATE_FORMAT(date, "%M %Y") AS date, DATE_FORMAT(date, "%m") as month, DATE_FORMAT(date, "%Y") as year ', '1= ? GROUP BY DATE_FORMAT(date, "%M %Y"), DATE_FORMAT(date, "%m"), DATE_FORMAT(date, "%Y") ORDER BY year, month ASC', $data);
        $archives = array();
        array_key_exists('Total', $rows) ? $archives[0] = $rows : $archives = $rows;
        return $archives;
    }

    public function getSocial()
    {
        $data = array(1);
        $rows = $this->db->select('social', '*', 'visible = ?', $data);
        $social = array();
        array_key_exists('name', $rows) ? $social[0] = $rows : $social = $rows;
        return $social;
    }

    public function getArticlesBySearch(array $searchItems)
    {
        $sql = '';
        $data = array(1);
        foreach ($searchItems as $item) {
            if ($item !== "") {
                $sql .= " AND title LIKE CONCAT('%',?,'%') ";
                array_push($data, $item);
            }
        }
        $articles = $this->db->select('articles', '*', '1= ?'.$sql, $data);
        return $articles;
    }
}
