<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\CPanelController as CPanelController;
    use \engine\Superglobals as Superglobals;
    
class CPanel extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }

    public function getArticles()
    {
        $articles = $this->db->select('articles', '*');

        return $articles;    
    }

    public function createArticle(array $article)
    {
        $globals = new Superglobals();
        $files = $globals->files(); 
        $article['date'] = date('Y-m-d');
        $article['comments'] = 0;
        $article['likes'] = 0;
        $article['status'] = 1;
        $insert_id = $this->db->create('articles', 'title, category, author, short_content, content, featured, date, comments, likes, status', $article);
        if (isset($files['avatar'])) {
            $directory = 'images/article';
            $img = explode('.', $files['avatar']['name']);
            $name = 'article_'.$insert_id.'.'.$img[1];
            $tmp_name = $files['avatar']['tmp_name'];
            move_uploaded_file($tmp_name, "$directory/$name");
            $data['banner'] = "$directory/$name";
            $this->db->update('articles', 'banner = ?', $data, 'id = ?', array($insert_id));
        }

    }
    
    public function editArticle(int $id, array $article)
    {
        $globals = new Superglobals();
        $files = $globals->files(); 
        $data = array($id);
        if (isset($files['avatar'])) {
            $directory = 'images/article';
            $img = explode('.', $files['avatar']['name']);
            $name = 'article_'.$id.'.'.$img[1];
            $tmp_name = $files['avatar']['tmp_name'];
            move_uploaded_file($tmp_name, "$directory/$name");
            $article['banner'] = "$directory/$name";
            $this->db->update('articles', 'title = ?, category = ?, author = ?, short_content = ?, content = ?, featured = ?, banner = ?', $article, 'id = ?', $data);
        } else {
            $this->db->update('articles', 'title = ?, category = ?, author = ?, short_content = ?, content = ?, featured = ?', $article, 'id = ?', $data);
        }
        
    }

    public function createCategory(array $article)
    {
        $this->db->create('categories', 'name', $article);
    }
    
    public function editCategory(int $id, array $article)
    {
        $data = array($id);
        $this->db->update('categories', 'name = ?', $article, 'id = ?', $data); 
    }

    public function editConfig(array $article)
    {
        $exists = $this->db->select('config', '*');

        if (!empty($exists)) {
            $data = array(1);
            $this->db->update('config', 'debugmode = ?, sitename = ?, email = ?, siteversion = ?, siteauthor = ?, launchyear = ?', $article, 'id = ?', $data);
        } else {
            $article['id'] = 1;
            $this->db->create('config', 'debugmode, sitename, email, siteversion, siteauthor, launchyear, id', $article); 
        }
        
    }

    public function deleteArticle(int $id)
    {
        $data = array($id);
        $this->db->delete('articles', 'id = ?', $data);
    }

    public function deleteCategory(int $id)
    {
        $data = array($id);
        $this->db->delete('categories', 'id = ?', $data);
    }

    public function getVisits()
    {      
        $data = array(1);
        $dates_query = $this->db->select('sessions', 'COUNT(session) AS session, firstvisit AS date', '1=? GROUP BY firstvisit', $data);
                       
        return $dates_query; 
    }
}