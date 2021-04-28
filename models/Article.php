<?php
    namespace models;

    use \engine\DbOperations as DbOperations;
    use \controllers\ArticleController as ArticleController;
    
class Article extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new DbOperations;
    }

    public function getCurrentArticles(string $month = '01', int $year = 1970)
    {
        $data = array($month, $year);
        $articles = $this->db->select('articles', '*', 'DATE_FORMAT(date, "%m") LIKE ? AND DATE_FORMAT(date, "%Y") LIKE ?', $data);
        return $articles;   
    }

    public function getArticlesByCategory(string $category)
    {
        $data = array(
            'category' => $category,
            'status'   => 1
        );

        $articles = $this->db->select('articles', '*', 'category = ? AND status = ?', $data);

        return $articles;
    }
}