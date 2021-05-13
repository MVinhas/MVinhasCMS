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
        $menu = Query::select('pages')->where(['header' => 1])->done();
        foreach ($menu as $k => $v) {
            $method = Query::select('methods')->fields('name, controller')->where(['id' => $v['method']])->done();
            $controller = Query::select('controllers')->fields('name')->where(['id' => 1])->done();
            $menu[$k]['class'] = $controller['name'].'/'.$method['name'];
        }
        if (!empty($menu)) {
            return $menu;
        }
        return 'Error';
    }
}
