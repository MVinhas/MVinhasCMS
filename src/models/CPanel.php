<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\CPanelController as CPanelController;
    use \engine\Superglobals as Superglobals;
    
class CPanel extends Model
{
    public function __construct()
    {
    }

    public function getArticles()
    {
        $articles = Query::select('articles')->done();

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

        $insert_id = Query::insert('articles')
        ->set([
            'title' => $article['title'],
            'category' => $article['category'],
            'author' => $article['author'],
            'short_content' => $article['short_content'],
            'content' => $article['content'],
            'featured' => $article['featured'],
            'date' => $article['date'],
            'comments' => $article['comments'],
            'likes' => $article['likes'],
            'status' => $article['status']
        ])
        ->done();

        if (isset($files['avatar'])) {
            $directory = 'images/article';
            $img = explode('.', $files['avatar']['name']);
            $name = 'article_'.$insert_id.'.'.$img[1];
            $tmp_name = $files['avatar']['tmp_name'];
            move_uploaded_file($tmp_name, "$directory/$name");
            $data['banner'] = "$directory/$name";
            Query::update('articles')->set(['banner' => $data['banner']])->where(['id' => $insert_id])->done();
        }

    }
    
    public function editArticle(int $id, array $article)
    {
        $globals = new Superglobals();
        $files = $globals->files();
        $article['banner'] = '';
        if (isset($files['avatar'])) {
            $directory = 'images/article';
            $img = explode('.', $files['avatar']['name']);
            $name = 'article_'.$id.'.'.$img[1];
            $tmp_name = $files['avatar']['tmp_name'];
            move_uploaded_file($tmp_name, "$directory/$name");
            $article['banner'] = "$directory/$name";    
        }

        Query::update('articles')
        ->set([
            'title' => $article['title'],
            'category' => $article['category'],
            'author' => $article['author'],
            'short_content' => $article['short_content'],
            'content' => $article['content'],
            'featured' => $article['featured'],
            'banner' => $article['banner']
        ])
        ->where(['id' => $id])
        ->done();
        
    }

    public function createCategory(array $article)
    {
        Query::insert('categories')
        ->set(['name' => $article['name']])
        ->done();
    }
    
    public function editCategory(int $id, array $article)
    {
        Query::update('categories')
        ->set(['name' => $article['name']])
        ->where(['id' => $id])
        ->done();
    }

    public function deleteArticle(int $id)
    {
        Query::delete('articles')
        ->where(['id' => $id])
        ->done();
    }

    public function deleteCategory(int $id)
    {
        Query::delete('articles')
        ->where(['id' => $id])
        ->done();
    }

    public function getVisits()
    {
        $dates_query = Query::select('sessions')
        ->fields('COUNT(session) AS session', 'firstvisit AS date')
        ->where(['1' => 1])
        ->groupBy('firstvisit')
        ->done();
                       
        return $dates_query; 
    }
}