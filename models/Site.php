<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\SiteController as SiteController;

class Site extends Model
{

    public function __construct()
    {
    }

    public function visitCounter()
    {
        $visit = Query::select('sessions')->fields('id')->where(['session' => session_id()])->done()->one();

        if (empty($visit)) {
            Query::insert('sessions')->set([
                'session' => session_id(),
                'firstvisit' => date('Y-m-d')
            ])->done();
        }
    }

    public function getCategories()
    {
        $categories = Query::select('categories')->done()->all();

        return $categories;
    }
    
    public function getCategory(int $id)
    {
        $category = Query::select('categories')->where(['id' => $id])->done()->one();

        return $category;
    }

    public function getArticle(int $id)
    {
        $category = Query::select('articles')->where(['id' => $id])->done()->one();

        return $category;
    }
}
