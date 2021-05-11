<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\ArticleController as ArticleController;
    
class Article extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new Query;
    }

    public function getCurrentArticles(string $month = '01', int $year = 1970)
    {
        $articles = $this->db::select('articles')
        ->where(['DATE_FORMAT(date, "%m")' => $month], LIKE)
        ->andWhere(['DATE_FORMAT(date, "%Y")' => $year], LIKE)
        ->done()
        ->all();

        return $articles;   
    }

    public function getArticlesByCategory(string $category)
    {
        $articles = $this->db::select('articles')
        ->where(['category' => $category])
        ->andWhere(['status' => 1])
        ->done()
        ->all();

        return $articles;
    }
}