<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\SiteController as SiteController;

class Site extends Model
{
    protected $db;
    public function __construct()
    {
        $this->db = new Query;
    }

    public function visitCounter()
    {
        $visit = $this->db::select('sessions')->fields('id')->where(['session' => session_id()])->done();

        if (empty($visit)) {
            Query::insert('sessions')->set([
                'session' => session_id(),
                'firstvisit' => date('Y-m-d')
            ])->done();
        }
    }

    public function getCategories()
    {
        return Query::select('categories')->done();
    }
    
    public function getCategory(int $id)
    {
        return Query::select('categories')->where(['id' => $id])->done();
    }

    public function getArticle(int $id)
    {
        return Query::select('articles')->where(['id' => $id])->done();
    }
}
