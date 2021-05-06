<?php
namespace migrations;

class Setup
{
    protected $query;
    public function __construct()
    {
        $this->query = new \Database\Query;
    }

    public function index()
    {
        $this->config();
        $this->users();
        $this->articles();
        $this->comments();
        $this->categories();
        $this->pages();
        $this->controllers();
        $this->methods();
        $this->tags();
        $this->about();
        $this->social();
        $this->sessions();
        $this->insertConfig();
        $this->insertControllers();
        $this->insertMethods();
        $this->insertPages();
        $this->insertCategories();
        $this->insertArticles();
        $this->insertAbout();
        $this->insertAdmin();
        $this->insertSocial();
    }

    private function config()
    {
        $fields = array(
            'id' => 'INT(1) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'debugmode' => 'INT(1) NOT NULL DEFAULT 1',
            'sitename' => 'VARCHAR(50) NOT NULL',
            'email' => 'VARCHAR(50) NOT NULL',
            'siteversion' => 'VARCHAR(100) NOT NULL',
            'siteauthor' => 'VARCHAR(100) NOT NULL',
            'launchyear' => 'INT(4) NOT NULL DEFAULT '.date('Y')
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function users()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'email' => 'VARCHAR(50) NOT NULL UNIQUE KEY',
            'username' => 'VARCHAR(30) NOT NULL',
            'password' => 'VARCHAR(128) NOT NULL',
            'role' => 'VARCHAR(15) NOT NULL',
            'reg_date' => 'TIMESTAMP',
            'active' => 'INT(11) NOT NULL DEFAULT 0'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function articles()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'category' => 'INT(11) NOT NULL',
            'title' => 'VARCHAR(90) NOT NULL',
            'author' => 'VARCHAR(64) NOT NULL',
            'date' => 'DATE',
            'banner' => 'VARCHAR(60)',
            'short_content' => 'VARCHAR(100)',
            'content' => 'TEXT',
            'tags' => 'VARCHAR(255)',
            'comments' => 'INT(11) NOT NULL',
            'likes' => 'INT(11) NOT NULL',
            'status' => 'INT(1) NOT NULL',
            'featured' => 'INT(1) NOT NULL DEFAULT 0'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function comments()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'author' => 'VARCHAR(64) NOT NULL',
            'date' => 'DATE',
            'content' => 'TEXT',
            'likes' => 'INT(11) NOT NULL',
            'status' => 'INT(1) NOT NULL'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function categories()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(64) NOT NULL UNIQUE KEY'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function pages()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(64) NOT NULL',
            'short_content' => 'VARCHAR(512) NULL',
            'content' => 'TEXT NULL',
            'method' => 'INT(11) NOT NULL',
            'active' => 'INT(1) NOT NULL DEFAULT 1',
            'header' => 'INT(1) NOT NULL DEFAULT 0',
            'menu' => 'INT(1) NOT NULL DEFAULT 0',
            'footer' => 'INT(1) NOT NULL DEFAULT 0'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();

        $this->query::tableIndex(__FUNCTION__)->constraint('id_name')->type("UNIQUE")->value('id, name')->done();
    }

    private function controllers()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(64) NOT NULL UNIQUE KEY'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function methods()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(64) NOT NULL UNIQUE KEY',
            'controller' => 'INT(3) NOT NULL DEFAULT 0'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function tags()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(64) NOT NULL UNIQUE KEY'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();  
    }

    private function about()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'TEXT NULL' 
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function social()
    {
        $fields = array(
            'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'name' => 'VARCHAR(64) NOT NULL UNIQUE KEY',
            'link' => 'VARCHAR(256) NOT NULL',
            'visible' => 'INT(1) NOT NULL DEFAULT 1'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function sessions()
    {
        $fields = array(
            'id' => 'BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'session' => 'VARCHAR(32) NOT NULL',
            'firstvisit' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
        );
        $this->query::create(__FUNCTION__)->set($fields)->done();
    }

    private function insertConfig()
    {
        $table = 'config';

        $this->query::insert($table)->set([
            'sitename' => 'My CMS Blog',
            'email' => 'jackbogle@example.com',
            'siteversion' => '1.0.0',
            'siteauthor' => 'Jack Bogle'
        ])->done();
    }

    private function insertControllers()
    {
        $table = 'controllers';

        $this->query::insert($table)->set([
            'name' => 'Home'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Admin'
        ])->done();
    }

    private function insertMethods()
    {
        $table = 'methods';

        $this->query::insert($table)->set([
            'name' => 'setup',
            'controller' => 1
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'login',
            'controller' => 2
        ])->done();
    }

    private function insertPages()
    {
        $table = 'pages';

        $this->query::insert($table)->set([
            'name' => 'Register',
            'method' => 1,
            'active' => 1,
            'header' => 1,
            'menu' => 1,
            'footer' => 0
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Login',
            'method' => 2,
            'active' => 1,
            'header' => 1,
            'menu' => 1,
            'footer' => 0
        ])->done();
    }

    private function insertCategories()
    {
        $table = 'categories';

        $this->query::insert($table)->set([
            'name' => 'Programming'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Hardware'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Mobility'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Software'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Linux'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'macOS'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Windows'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Gaming'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Music'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'Lifestyle'
        ])->done();

        $this->query::insert($table)->set([
            'name' => 'PC Buyers Guide'
        ])->done();
    }

    private function insertArticles()
    {
        $table = 'articles';

        $this->query::insert($table)->set([
            'category' => 1,
            'title' => 'Fusce sit amet consectetur risus.',
            'author' => 'Micael Vinhas',
            'date' => '2020-04-07',
            'short_content' => 'Integer consequat interdum egestas.',
            'content' => 'Integer consequat interdum egestas. Sed mollis ornare erat non varius. Mauris congue, nunc quis porta condimentum, ligula tellus commodo velit, at cursus diam arcu in odio. Cras nisl quam, aliquam sit amet aliquam a, fermentum sit amet arcu. Integer molestie at tortor vel malesuada.',
            'comments' => 0,
            'likes' => 0,
            'status' => 1,
            'featured' => 2
        ])->done();

        $this->query::insert($table)->set([
            'category' => 2,
            'title' => 'Vestibulum molestie efficitur facilisis.',
            'author' => 'Micael Vinhas',
            'date' => '2020-04-22',
            'short_content' => 'Nunc non vestibulum ipsum, a vulputate enim.',
            'content' => 'Nulla hendrerit lacus at elit viverra malesuada. Aliquam ut mattis velit. Etiam consequat mattis dapibus. Etiam cursus arcu in sodales gravida. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'comments' => 0,
            'likes' => 0,
            'status' => 1,
            'featured' => 1
        ])->done();

        $this->query::insert($table)->set([
            'category' => 3,
            'title' => 'Praesent in pretium arcu.',
            'author' => 'Micael Vinhas',
            'date' => '2020-04-30',
            'short_content' => 'Aliquam erat volutpat.',
            'content' => 'Morbi maximus mauris sed dolor fringilla, in accumsan augue tempus. Ut pharetra tincidunt magna at imperdiet. Ut faucibus felis nulla, sit amet bibendum ex fermentum non. ',
            'comments' => 0,
            'likes' => 0,
            'status' => 1,
            'featured' => 1
        ])->done();

        $this->query::insert($table)->set([
            'category' => 4,
            'title' => 'Curabitur sit amet lobortis purus.',
            'author' => 'Micael Vinhas',
            'date' => '2020-04-19',
            'short_content' => 'Morbi non mattis nisi.',
            'content' => 'Vestibulum molestie efficitur facilisis. Sed finibus feugiat odio et blandit. Aenean at enim eget augue egestas pretium. Nunc eget tellus eget risus aliquam malesuada sed at turpis. Donec hendrerit ullamcorper mi, in rutrum tortor bibendum quis. Donec luctus consectetur turpis at sodales. Curabitur sit amet lobortis purus.',
            'comments' => 0,
            'likes' => 0,
            'status' => 1,
            'featured' => 0
        ])->done();
        
        $this->query::insert($table)->set([
            'category' => 5,
            'title' => 'Ut auctor consequat arcu, at accumsan sem semper quis.',
            'author' => 'Micael Vinhas',
            'date' => '2020-04-11',
            'short_content' => 'Nam vehicula blandit lorem, at gravida lorem rutrum sit amet.',
            'content' => 'Curabitur sit amet lobortis purus. Donec luctus, libero vitae faucibus dapibus, ante ligula iaculis libero, a ornare sapien urna at nunc.',
            'comments' => 0,
            'likes' => 0,
            'status' => 1,
            'featured' => 0
        ])->done();

        $this->query::insert($table)->set([
            'category' => 6,
            'title' => 'Aliquam pretium odio ac lorem mattis pellentesque.',
            'author' => 'Micael Vinhas',
            'date' => '2020-04-04',
            'short_content' => 'Donec tincidunt venenatis venenatis.',
            'content' => 'Ut sollicitudin, dolor in interdum cursus, felis ante suscipit ante, non laoreet ex velit ac ligula. Maecenas turpis enim, luctus nec eleifend a, consequat in orci. Maecenas egestas accumsan lacinia. Duis a elit eget justo finibus dapibus sed at augue. Fusce porttitor ut nisl eu posuere.',
            'comments' => 0,
            'likes' => 0,
            'status' => 1,
            'featured' => 0
        ])->done();

    }

    private function insertAbout()
    {
        $table = 'about';

        $this->query::insert($table)->set([
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at enim ut.'
        ])->done();
    }

    private function insertAdmin()
    {
        $table = 'users';
        
        $splitemail = explode('@', OWNER);

        $this->query::insert($table)->set([
            'email' => OWNER,
            'username' => $splitemail[0],
            'password' => password_hash($splitemail[0], PASSWORD_DEFAULT),
            'role' => 'admin',
            'reg_date' => date('Y-m-d H:i:s'),
            'active' => 1
        ])->done();
    }

    private function insertSocial()
    {
        $table = 'social';
        $fields = '`name`, `link`, `visible`';
        $values = array('LinkedIn', 'https://www.linkedin.com/in/micael-vinhas-74bab1112', 1);

        $this->query::insert($table)->set([
            'name' => 'LinkedIn',
            'link' =>'https://www.linkedin.com/in/micael-vinhas-74bab1112',
            'visible' => 1
        ])->done();
    }
}
