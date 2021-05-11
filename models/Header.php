<?php
    namespace models;

    use \database\Query as Query;
    use \controllers\HeaderController as HeaderController;
    
class Header extends Model
{
    public function __construct()
    {
    }

    public function getMenu()
    {
        $menu = Query::select('pages')->where(['header' => 1])->done()->all();
        foreach ($menu as $k => $v) {
            $method = Query::select('methods')->fields('name, controller')->where(['id' => $v['method']])->done()->one();
            $controller = Query::select('controllers')->fields('name')->where(['id' => 1])->done()->one();
            $menu[$k]['class'] = $controller['name'].'/'.$method['name'];
        }
        if (!empty($menu)) {
            return $menu;
        }
        return 'Error';
    }
}
